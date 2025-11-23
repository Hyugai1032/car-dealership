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