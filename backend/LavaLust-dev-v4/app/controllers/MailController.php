<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class MailController extends Controller {
    public function __construct()
    {
        parent::__construct();
    }

    // ==================== FORGOT PASSWORD ====================
    public function sendForgotPassword() 
    {
        if ($this->io->method() === 'post') {
            $email = trim($this->io->post('recipient'));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->call->view('forgotPassword', ['error' => 'Invalid email format.']);
            }

            $user = $this->db->raw("SELECT id, name FROM users WHERE email = ?", [$email])->fetch();
            if (!$user) {
                return $this->call->view('forgotPassword', ['error' => 'Email not found.']);
            }

            $token = bin2hex(random_bytes(32));
            $expiresAt = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            // FIXED: May values na!
            $this->db->raw(
                "INSERT INTO password_resets (user_id, token, expires_at, created_at) 
                 VALUES (?, ?, ?, NOW())",
                [$user['id'], $token, $expiresAt]
            );

            $resetLink = base_url("reset-password?token={$token}");
            $subject = "Password Reset Request";
            $message = "
                <h2>Reset Your Password</h2>
                <p>Hi {$user['name']},</p>
                <p>Click the button below to reset your password. Link expires in <strong>5 minutes</strong>.</p>
                <p style='text-align:center;'>
                    <a href='{$resetLink}' style='background:#e74c3c;color:white;padding:12px 24px;text-decoration:none;border-radius:8px;font-weight:bold;'>
                        Reset Password
                    </a>
                </p>
                <p>If you didn't request this, ignore this email.</p>
            ";

            if (sendMail($email, $subject, $message)) {
                return $this->call->view('forgotPassword', ['success' => 'Reset link sent to your email!']);
            } else {
                return $this->call->view('forgotPassword', ['error' => 'Failed to send email. Try again.']);
            }
        }

        return $this->call->view('forgotPassword');
    }

    // ==================== RESET PASSWORD ====================
    public function resetPassword()
    {
        if ($this->io->method() === 'get') {
            $token = $this->io->get('token');
            if (!$token) {
                return $this->call->view('resetPassword', ['error' => 'Invalid link.']);
            }

            $tokenData = $this->db->raw(
                "SELECT * FROM password_resets WHERE token = ? AND expires_at >= NOW()",
                [$token]
            )->fetch();

            if (!$tokenData) {
                return $this->call->view('resetPassword', ['error' => 'Link expired or invalid.']);
            }

            return $this->call->view('resetPassword', ['token' => $token]);
        }

        // POST: Submit new password
        if ($this->io->method() === 'post') {
            $token = $this->io->post('token');
            $newPass = $this->io->post('password');
            $confirm = $this->io->post('confirm_password');

            // FIXED: Removed double $this->
            $tokenData = $this->db->raw(
                "SELECT * FROM password_resets WHERE token = ? AND expires_at >= NOW()",
                [$token]
            )->fetch();

            if (!$tokenData) {
                return $this->call->view('resetPassword', ['error' => 'Invalid or expired token.']);
            }

            if ($newPass !== $confirm) {
                return $this->call->view('resetPassword', ['error' => 'Passwords do not match.', 'token' => $token]);
            }

            if (strlen($newPass) < 8) {
                return $this->call->view('resetPassword', ['error' => 'Password must be at least 8 characters.', 'token' => $token]);
            }

            $hashed = password_hash($newPass, PASSWORD_DEFAULT);

            $this->db->raw("UPDATE users SET password_hash = ? WHERE id = ?", [$hashed, $tokenData['user_id']]);
            $this->db->raw("DELETE FROM password_resets WHERE token = ?", [$token]);

            return $this->call->view('login', ['success' => 'Password changed successfully! Please log in.']);
        }
    }

    // ==================== CREATE APPOINTMENT + EMAIL ====================
    // public function createAppointment()
    // {
    //     if (!$this->session->userdata('user_id')) {
    //         return redirect('login');
    //     }

    //     if ($this->io->method() === 'get') {
    //         return $this->call->view('appointmentForm');
    //     }

    //     if ($this->io->method() === 'post') {
    //         $userId    = $this->session->userdata('user_id');
    //         $userName  = $this->session->userdata('name');
    //         $userEmail = $this->session->userdata('email');

    //         $carId     = $this->io->post('car_id');
    //         $dealerId  = $this->io->post('dealer_id') ?? 1;
    //         $service   = $this->io->post('service');
    //         $date      = $this->io->post('date');
    //         $time      = $this->io->post('time');

    //         if (!$carId || !$date || !$time) {
    //             return $this->call->view('appointmentForm', ['error' => 'All fields are required.']);
    //         }

    //         $appointmentAt = "$date $time:00";

    //         // FIXED: Proper raw query syntax
    //         $result = $this->db->raw(
    //             "INSERT INTO appointments 
    //             (car_id, user_id, dealer_id, appointment_at, status, services, created_at) 
    //             VALUES (?, ?, ?, ?, 'pending', ?, NOW())",
    //             [$carId, $userId, $dealerId, $appointmentAt, $service]
    //         );

    //         $appointmentId = $this->db->insert_id(); // Correct way to get ID

    //         // Get car info for email
    //         $car = $this->db->raw("SELECT make, model, year FROM cars WHERE id = ?", [$carId])->fetch();

    //         $subject = "Appointment Confirmed – #{$appointmentId}";
    //         $message = "
    //             <h2>Hi {$userName}!</h2>
    //             <p>Your appointment has been scheduled:</p>
    //             <ul>
    //                 <li><strong>Car:</strong> {$car['make']} {$car['model']} ({$car['year']})</li>
    //                 <li><strong>Service:</strong> {$service}</li>
    //                 <li><strong>Date & Time:</strong> " . date('F j, Y \a\t g:i A', strtotime($appointmentAt)) . "</li>
    //                 <li><strong>Status:</strong> Pending Approval</li>
    //             </ul>
    //             <p>We will notify you once it's approved.</p>
    //             <p>Thank you for choosing us!</p>
    //         ";

    //         $sent = sendMail($userEmail, $subject, $message);

    //         return $this->call->view('appointmentSuccess', [
    //             'success' => 'Appointment created!',
    //             'email_sent' => $sent ? 'Email sent!' : 'Email failed (but appointment saved)'
    //         ]);
    //     }
    // }
}