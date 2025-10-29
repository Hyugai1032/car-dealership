<?php

class ApiController extends Controller {
    private $user_id;

    public function sendVerificationCode() {
        $this->api->require_method('POST');
        $input = $this->api->body();
        $email = trim($input['email'] ?? '');

        if (empty($email)) {
            $this->api->respond_error('Email is required', 400);
            return;
        }

        // Check if already registered
        $stmt = $this->db->raw("SELECT id FROM users WHERE email = ?", [$email]);
        if ($stmt->fetch()) {
            $this->api->respond_error('Email already registered', 400);
            return;
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        // Save or update OTP record
        $this->db->raw(
            "INSERT INTO email_verifications (email, code, expires_at, created_at)
             VALUES (?, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE code = VALUES(code), expires_at = VALUES(expires_at), created_at = NOW()",
            [$email, $otp, $expires_at]
        );

        // Send email via LavaLust Email library
        $this->call->library('email');
        $this->email->sender('no-reply@autoelite.com', 'AutoElite Verification');
        $this->email->recipient($email);
        $this->email->subject('Your AutoElite Verification Code');

        $htmlContent = "
            <p>Hello,</p>
            <p>Your AutoElite verification code is:</p>
            <h2 style='font-size:24px;letter-spacing:2px;'>$otp</h2>
            <p>This code will expire in <strong>10 minutes</strong>.</p>
            <p>If you didn’t request this, you can safely ignore this message.</p>
        ";

        $this->email->email_content($htmlContent, 'html');

        if ($this->email->send()) {
            $this->api->respond(['message' => 'Verification code sent successfully']);
        } else {
            $this->api->respond_error('Failed to send verification email', 500);
        }
    }

    public function verifyCode() {
        $this->api->require_method('POST');
        $input = $this->api->body();

        $email = trim($input['email'] ?? '');
        $code = trim($input['code'] ?? '');
        $name = trim($input['name'] ?? '');
        $password = trim($input['password'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $role = $input['role'] ?? 'user';
        $dealer_id = $input['dealer_id'] ?? null;

        if (!$email || !$code) {
            $this->api->respond_error('Email and code are required', 400);
            return;
        }

        // Validate OTP and expiration
        $stmt = $this->db->raw(
            "SELECT * FROM email_verifications WHERE email = ? AND code = ? LIMIT 1",
            [$email, $code]
        );
        $otpData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$otpData) {
            $this->api->respond_error('Invalid verification code', 400);
            return;
        }

        // Check expiration
        if (strtotime($otpData['expires_at']) < time()) {
            $this->api->respond_error('Verification code has expired', 400);
            // Optionally delete expired code
            $this->db->raw("DELETE FROM email_verifications WHERE email = ?", [$email]);
            return;
        }

        // Create user
        $this->db->raw(
            "INSERT INTO users (role, name, email, password_hash, phone, dealer_id, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$role, $name, $email, password_hash($password, PASSWORD_BCRYPT), $phone, $dealer_id]
        );

        // Delete OTP after use
        $this->db->raw("DELETE FROM email_verifications WHERE email = ?", [$email]);

        $this->api->respond(['message' => 'User registered successfully']);
    }

    public function googleLogin()
        {
            header("Access-Control-Allow-Origin: http://localhost:5173");
            header("Access-Control-Allow-Methods: POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                http_response_code(200);
                exit();
            }
            $this->api->require_method('POST');
            $input = $this->api->body();
            $credential = $input['credential'] ?? null;

            if (!$credential) {
                $this->api->respond_error('Missing Google credential', 400);
                return;
            }

            // ⬇️ Replace this old require line with this new one:
            require_once __DIR__ . '/../../../vendor/autoload.php';
            $client = new Google_Client(['client_id' => '1084979266133-d1bvpmpb5devqn5cl0pscuv9k01l9p9t.apps.googleusercontent.com']);

            try {
                $payload = $client->verifyIdToken($credential);
                if (!$payload) {
                    $this->api->respond_error('Invalid Google token', 401);
                    return;
                }

                // ✅ If payload is valid, continue login/registration logic...
                $google_id = $payload['sub'];
                $email     = $payload['email'];
                $name      = $payload['name'] ?? '';
                $picture   = $payload['picture'] ?? null;

                // Check or create user, issue tokens, etc.
                
            } catch (Exception $e) {
                $this->api->respond_error('Google verification failed: ' . $e->getMessage(), 500);
            }
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
        $this->db->raw(
            "INSERT INTO users (role, name, email, password_hash, phone, dealer_id, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$input['role'], $input['name'], $input['email'], password_hash($input['password'], PASSWORD_BCRYPT), $input['phone'], $input['dealer_id']]
        );
        $this->api->respond(['message' => 'User created']);
    }

    public function update($id) {
        $input = $this->api->body();
        $this->db->raw("UPDATE users SET role=?, name=?, email=?, phone=?, dealer_id=? WHERE id=?",
            [$input['role'], $input['name'], $input['email'], $input['phone'], $input['dealer_id'], $id]);
        $this->api->respond(['message' => 'User updated']);
    }

    public function updateCars($id) {
        $input = json_decode($this->api->body(), true);

        if (!$id || !$input) {
            return $this->api->respond(['error' => 'Invalid input or missing ID'], 400);
        }

        $updated = $this->db->query(
            "UPDATE cars SET dealer_id=?, make=?, model=?, variant=?, year=?, type=?, price=?, mileage=?, fuel_type=?, transmission=?, color=?, main_image=?, gallery=?, description=?, warranty_id=?, status=? WHERE id=?",
            [
                $input['dealer_id'], $input['make'], $input['model'], $input['variant'],
                $input['year'], $input['type'], $input['price'], $input['mileage'],
                $input['fuel_type'], $input['transmission'], $input['color'],
                $input['main_image'], $input['gallery'], $input['description'],
                $input['warranty_id'], $input['status'], $id
            ]
        );

        if ($updated) {
            $this->api->respond(['message' => 'Car updated successfully']);
        } else {
            $this->api->respond(['error' => 'Update failed'], 500);
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
}