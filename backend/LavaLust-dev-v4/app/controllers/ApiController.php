<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class ApiController extends Controller {
    private $user_id;

    public function sendTestEmail() {
        $mail = new PHPMailer(true);

        try {
            // Gmail SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'johnrheynedamotamares2005@gmail.com';           // your Gmail address
            $mail->Password   = 'isebrtolhpyifuhh';              // Gmail app password
            $mail->SMTPSecure = 'tls';                            // encryption
            $mail->Port       = 587;

            // Sender and recipient
            $mail->setFrom('johnrheynedamotamares2005@gmail.com', 'LavaLust Test');
            $mail->addAddress('johnrheynedamotamares2005@gmail.com', 'Recipient Name');

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Gmail SMTP Test';
            $mail->Body    = 'Location: http://localhost:5173/';

            // Send it
            $mail->send();
            echo '✅ Email sent successfully!';
        } catch (Exception $e) {
            echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    }
    public function sendVerificationLink() {
    $this->api->require_method('POST');
    $input = $this->api->body();
    $email = trim($input['email'] ?? '');

    if (empty($email)) {
        return $this->api->respond_error('Email is required', 400);
    }

    // Prevent registration if already exists
    $stmt = $this->db->raw("SELECT id FROM users WHERE email = ?", [$email]);
    if ($stmt->fetch()) {
        return $this->api->respond_error('Email already registered', 400);
    }

    // Rate limiting
    $stmt = $this->db->raw("SELECT send_count, last_sent_at FROM email_verifications WHERE email = ? LIMIT 1", [$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $now = time();

    if ($row) {
        $last = $row['last_sent_at'] ? strtotime($row['last_sent_at']) : 0;

        if ($now - $last < 60) {
            return $this->api->respond_error('Please wait before requesting another link', 429);
        }

        if ($row['send_count'] >= 5 && ($now - $last) < 3600) {
            return $this->api->respond_error('Too many attempts; try again later', 429);
        }
    }

    // Generate a secure token
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    if ($row) {
        $this->db->raw(
            "UPDATE email_verifications SET token=?, expires_at=?, created_at=NOW(), last_sent_at=NOW(), send_count = send_count + 1 WHERE email=?",
            [$token, $expires_at, $email]
        );
    } else {
        $this->db->raw(
            "INSERT INTO email_verifications (email, token, expires_at, created_at, last_sent_at, send_count) VALUES (?, ?, ?, NOW(), NOW(), 1)",
            [$email, $token, $expires_at]
        );
    }

    // Send email with PHPMailer
    require_once __DIR__ . '/../../../vendor/autoload.php';
    $config = require __DIR__ . '/../../../app/config/email.php';
    $verificationLink = "https://yourdomain.com/api/verify-link?token=$token";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port = $config['port'];

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email';
        $mail->Body = "
            <p>Hello,</p>
            <p>Click the link below to verify your email and access the dashboard:</p>
            <a href='$verificationLink' style='font-size:18px;'>Verify Email</a>
            <p>This link will expire in 10 minutes.</p>
            <p>If you didn’t request this, ignore this email.</p>
        ";

        $mail->send();
        return $this->api->respond(['message' => 'Verification link sent successfully']);
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return $this->api->respond_error('Failed to send verification email: ' . $mail->ErrorInfo, 500);
    }
    }

    public function verifyLink() {
    $token = $_GET['token'] ?? '';
    if (!$token) {
        return $this->api->respond_error('Invalid link', 400);
    }

    $stmt = $this->db->raw("SELECT * FROM email_verifications WHERE token = ? LIMIT 1", [$token]);
    $verification = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$verification) {
        return $this->api->respond_error('Invalid or expired link', 400);
    }

    if (strtotime($verification['expires_at']) < time()) {
        $this->db->raw("DELETE FROM email_verifications WHERE token = ?", [$token]);
        return $this->api->respond_error('Link expired', 400);
    }

    $email = $verification['email'];

    // Automatically create user (or mark as verified if already exists)
    $stmt = $this->db->raw("SELECT * FROM users WHERE email = ?", [$email]);
    if (!$stmt->fetch()) {
        // Replace these with actual user details if available
        $this->db->raw(
            "INSERT INTO users (role, name, email, password_hash, created_at)
             VALUES (?, ?, ?, ?, NOW())",
            ['user', 'Default Name', $email, password_hash('defaultpass', PASSWORD_BCRYPT)]
        );
    }

    // Remove token after verification
    $this->db->raw("DELETE FROM email_verifications WHERE token = ?", [$token]);

    // Redirect to dashboard
    header('Location: http://localhost:8000/dashboard');
    exit;
    }



    public function login() {
        $this->api->require_method('POST');
        $input = $this->api->body();
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        $stmt = $this->db->raw('SELECT * FROM users WHERE email = ?', [$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $tokens = $this->api->issue_tokens(['id' => $user['id'], 'role' => $user['role']]);
            $this->api->respond($tokens);
        } else {
            $this->api->respond_error('Invalid credentials', 401);
        }
    }

    public function logout() {
        $this->api->require_method('POST');
        $input = $this->api->body();
        $token = $input['refresh_token'] ?? '';
        $this->api->revoke_refresh_token($token);
        $this->api->respond(['message' => 'Logged out']);
    }

    public function list() {
        $stmt = $this->db->table('users')
                         ->select('id, role, name, email, phone, dealer_id, created_at')
                         ->get_all();
        $this->api->respond($stmt);
    }

    public function create() {
        $input = $this->api->body();

        // Set default values
        $role = $input['role'] ?? 'buyer';          // default role
        $phone = $input['phone'] ?? '0927488292';          // default phone is null
        $dealer_id = $input['dealer_id'] ?? 1;  // default dealer_id is null

        $this->db->raw(
            "INSERT INTO users (role, name, email, password_hash, phone, dealer_id, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [
                $role,
                $input['name'],
                $input['email'],
                password_hash($input['password'], PASSWORD_BCRYPT),
                $phone,
                $dealer_id
            ]
        );

        $this->api->respond(['message' => 'User created']);
    }


    public function createCars() {
        $input = $this->api->body();

        $this->db->raw(
            "INSERT INTO cars (dealer_id, make, model, variant, year, type, price, mileage, fuel_type, transmission, color, main_image, description, warranty_id, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $input['dealer_id'],
                $input['make'],
                $input['model'],
                $input['variant'],
                $input['year'],
                $input['type'],
                $input['price'] ?? null,
                $input['mileage'] ?? null,
                $input['fuel_type'] ?? null,
                $input['transmission'] ?? null,
                $input['color'] ?? null,
                $input['main_image'] ?? null,
                $input['description'] ?? null,
                $input['warranty_id'] ?? null,
                $input['status'] ?? 'available'
            ]
        );

        $this->api->respond(['message' => 'Car created']);
    }


    public function update($id) {
        $input = $this->api->body();
        $this->db->raw("UPDATE users SET role=?, name=?, email=?, phone=?, dealer_id=? WHERE id=?",
            [$input['role'], $input['name'], $input['email'], $input['phone'], $input['dealer_id'], $id]);
        $this->api->respond(['message' => 'User updated']);
    }

    public function listCars() {
        try {
            // Query all cars with the specified fields
            $cars = $this->db->table('cars')
                            ->select('id, dealer_id, make, model, variant, year, type, price, mileage, fuel_type, transmission, color, main_image, description, warranty_id, status')
                            ->get_all();

            // Respond with the result in JSON format
            $this->api->respond([
                'status' => 'success',
                'cars' => $cars
            ]);
        } catch (Exception $e) {
            // Catch any errors and respond properly
            $this->api->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


        public function updateCars($id) {
            $input = $this->api->body();

            try {
                $this->db->raw(
                    "UPDATE cars SET dealer_id=?, make=?, model=?, variant=?, year=?, type=?, price=?, mileage=?, fuel_type=?, transmission=?, color=?, main_image=?, description=?, warranty_id=?, status=? WHERE id=?",
                    [
                        $input['dealer_id'], $input['make'], $input['model'], $input['variant'],
                        $input['year'], $input['type'], $input['price'], $input['mileage'],
                        $input['fuel_type'], $input['transmission'], $input['color'],
                        $input['main_image'], $input['description'], $input['warranty_id'],
                        $input['status'], $id
                    ]
                );

                $this->api->respond([
                    'status' => 'success',
                    'message' => 'Car updated successfully'
                ]);
            } catch (Exception $e) {
                $this->api->respond([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }


        public function deleteCars($id) {
            try {
                $this->db->table('cars')->where('id', $id)->delete();
                $this->api->respond([
                    'status' => 'success',
                    'message' => 'Car deleted successfully'
                ]);
            } catch (Exception $e) {
                $this->api->respond([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }


    public function delete($id) {
        $this->db->raw("DELETE FROM users WHERE id = ?", [$id]);
        $this->api->respond(['message' => 'User deleted']);
    }

    public function profile() {
        $auth = $this->api->require_jwt();
        $this->user_id = $auth['sub'];
        $stmt = $this->db->raw(
            "SELECT id, role, name, email, phone, dealer_id, created_at FROM users WHERE id = ?",
            [$this->user_id]
        );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->api->respond($user ?: ['message' => 'User not found']);
    }

    public function refresh() {
        $this->api->require_method('POST');
        $input = $this->api->body();
        $refresh_token = $input['refresh_token'] ?? '';
        $this->api->refresh_access_token($refresh_token);
    }

    public function listCarsPaginated() {
    try {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $offset = ($page - 1) * $limit;

        // Base query
        $baseSql = "FROM cars";
        $params = [];

        // Apply search to multiple columns
        if (!empty($search)) {
            $searchTerm = "%$search%";
            $baseSql .= " WHERE 
                make LIKE ? OR 
                model LIKE ? OR 
                variant LIKE ? OR 
                year LIKE ? OR 
                type LIKE ? OR 
                CAST(price AS CHAR) LIKE ? OR 
                CAST(mileage AS CHAR) LIKE ? OR 
                fuel_type LIKE ? OR 
                transmission LIKE ? OR 
                color LIKE ? OR 
                description LIKE ? OR 
                status LIKE ?";
            
            $params = array_fill(0, 12, $searchTerm);
        }

        // Main query with pagination
        $sql = "SELECT id, dealer_id, make, model, variant, year, type, price, mileage, fuel_type, transmission, color, main_image, description, warranty_id, status " 
             . $baseSql . " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->raw($sql, $params);
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count total records
        $countSql = "SELECT COUNT(*) AS total " . $baseSql;
        $countStmt = $this->db->raw($countSql, !empty($search) ? array_slice($params, 0, 12) : []);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Build response
        $response = [
            "status" => "success",
            "cars" => $cars,
            "pagination" => [
                "page" => $page,
                "limit" => $limit,
                "total_records" => (int)$total,
                "total_pages" => ceil($total / $limit)
            ]
        ];

        $this->api->respond($response);

    } catch (Exception $e) {
        $this->api->respond([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}


}