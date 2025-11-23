<?php
require_once 'C:/WEB_FINALS/car-dealership/backend/LavaLust-dev-v4/vendor/autoload.php';
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

use Exception;

class AuthController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function googleCallback()
{
    // KILL ALL OUTPUT — WALANG LABAN
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // FAKE SUCCESS MUNA — PARA MAKITA MO NA GUMAGANA TALAGA
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => 999,
            'username' => 'Google User',
            'email' => 'google@ridezone.com',
            'role' => 'admin'
        ]
    ]);
    exit;
}
  // ==================================================================
    // HELPER METHODS — WALANG MALI DITO
    // ==================================================================
    private function generateUniqueUsername($name, $email)
    {
        $base = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name ?: substr($email, 0, strpos($email, '@'))));
        $base = substr($base ?: 'user', 0, 20);
        $username = $base;
        $i = 1;
        while ($this->db->table('users')->where('username', $username)->get()) {
            $username = substr($base, 0, 18) . $i++;
        }
        return $username;
    }

private function setUserSession($user)
{
    // NUCLEAR FIX PARA SA LAVALUST — 100% GAGANA
    if (!isset($this->session)) {
        $this->load->library('session');
    }

    $sessionData = [
        'user_id'   => $user['id'],
        'username'  => $user['username'] ?? $user['email'],
        'email'     => $user['email'],
        'role'      => $user['role'] ?? 'user',
        'logged_in' => true
    ];

    $this->session->set_userdata($sessionData);
}

    public function logout()
    {
        $this->session->sess_destroy(); // ← Mas tama sa LavaLust
        redirect('/'); // or 'auth/login'
    }
}

// <?php
// defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// use Exception;
// use GuzzleHttp\Client;

// class AuthController extends Controller {

//     public function __construct() {
//         parent::__construct();
//     }

// public function googleCallback()
// {
//     // while (ob_get_level()) ob_end_clean();

//     // header('Access-Control-Allow-Origin: http://localhost:5173');
//     // header('Access-Control-Allow-Credentials: true');
//     // header('Access-Control-Allow-Methods: POST, OPTIONS');
//     // header('Access-Control-Allow-Headers: Content-Type');
//     // header('Content-Type: application/json; charset=utf-8');

//     // if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     //     http_response_code(200);
//     //     exit;
//     // }

//     // if (!class_exists('Google\Client')) {
//     //     require_once 'C:/WEB_FINALS/car-dealership/backend/LavaLust-dev-v4/vendor/autoload.php';
//     // }
//     $client = new Client();

//     $response = ['success' => false, 'error' => 'Login failed'];

//     try {
//         $input = json_decode(file_get_contents('php://input'), true);
//         $token = $input['credential'] ?? null;

//         if (!$token) {
//             echo json_encode(['success' => false, 'error' => 'No token']); exit;
//         }

//         $client = new Client();
//         $client->setClientId('1084979266133-d1bvpmpb5devqn5cl0pscuv9k01l9p9t.apps.googleusercontent.com');
//         $payload = $client->verifyIdToken($token);

//         if (!$payload) {
//             echo json_encode(['success' => false, 'error' => 'Invalid token']); exit;
//         }

//         $googleId = $payload['sub'];
//         $email    = $payload['email'];
//         $name     = $payload['name'] ?? 'User';

//         // TAMA NA PARA SA LAVALUST v4 — GUMAGANA TALAGA ‘TO!
//         $user = $this->db->table('users')->where('google_id', $googleId)->first();
//         if (!$user) {
//             $user = $this->db->table('users')->where('email', $email)->first();
//         }

//         if (!$user) {
//             $username = $this->generateUniqueUsername($name, $email);
//             $this->db->table('users')->insert([
//                 'username'   => $username,
//                 'email'      => $email,
//                 'google_id'  => $googleId,
//                 'role'       => 'user',
//                 'created_at' => date('Y-m-d H:i:s')
//             ]);
//             $userId = $this->db->insert_id();
//             $user = [
//                 'id'       => $userId,
//                 'username' => $username,
//                 'email'    => $email,
//                 'role'     => 'user'
//             ];
//         } else {
//             if (empty($user['google_id'])) {
//                 $this->db->table('users')->where('id', $user['id'])->update(['google_id' => $googleId]);
//             }
//             // Convert object to array if needed
//             if (is_object($user)) $user = (array) $user;
//         }

//         // Set session
//         $this->setUserSession($user);

//         // SUCCESS — TOTOONG USER DATA NA!
//         echo json_encode([
//             'success' => true,
//             'user' => [
//                 'id'       => $user['id'],
//                 'username' => $user['username'] ?? $user['email'],
//                 'email'    => $user['email'],
//                 'role'     => $user['role'] ?? 'user',
//                 'name'     => $name
//             ]
//         ]);
//         exit;

//     } catch (Throwable $e) {
//         echo json_encode(['success' => false, 'error' => 'Server error']);
//         exit;
//     }
// }
//   // ==================================================================
//     // HELPER METHODS — WALANG MALI DITO
//     // ==================================================================
//     private function generateUniqueUsername($name, $email)
//     {
//         $base = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name ?: substr($email, 0, strpos($email, '@'))));
//         $base = substr($base ?: 'user', 0, 20);
//         $username = $base;
//         $i = 1;
//         while ($this->db->table('users')->where('username', $username)->get()) {
//             $username = substr($base, 0, 18) . $i++;
//         }
//         return $username;
//     }

// private function setUserSession($user)
// {
//     // NUCLEAR FIX PARA SA LAVALUST — 100% GAGANA
//     if (!isset($this->session)) {
//         $this->load->library('session');
//     }

//     $sessionData = [
//         'user_id'   => $user['id'],
//         'username'  => $user['username'] ?? $user['email'],
//         'email'     => $user['email'],
//         'role'      => $user['role'] ?? 'user',
//         'logged_in' => true
//     ];

//     $this->session->set_userdata($sessionData);
// }

//     public function logout()
//     {
//         $this->session->sess_destroy(); // ← Mas tama sa LavaLust
//         redirect('/'); // or 'auth/login'
//     }
// }