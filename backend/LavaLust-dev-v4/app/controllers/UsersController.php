<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');


// ====== CORS Handling ======
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}
// ===========================

require_once __DIR__ . '/../models/UsersModel.php';

class UsersController extends Controller {
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UsersModel();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ================= Login =================
public function login()
{
    // Accept email and password from GET or POST
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? null;
    $password = $input['password'] ?? null;

    if (!$email || !$password) {
        echo json_encode(['status'=>'error','message'=>'Email and password required']);
        return;
    }

    // Find user by email
    $user = $this->model->get_user_by_email($email);

    if (!$user) {
        echo json_encode(['status'=>'error','message'=>'Invalid email or password']);
        return;
    }

    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        echo json_encode(['status'=>'error','message'=>'Invalid email or password']);
        return;
    }

    // Start session and store user info
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user'] = [
        'id' => $user['id'],
        'role' => $user['role'],
        'dealer_id' => $user['dealer_id']
    ];

    echo json_encode([
        'status' => 'success',
        'message' => 'Logged in successfully',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);
}


    // ================= Logout =================
    public function logout()
    {
        session_destroy();
        echo json_encode(['status'=>'success','message'=>'Logged out']);
    }

    // ================= Register =================
    public function register()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        $role = $input['buyer'] ?? 'buyer';
        $dealer_id = $input['NULL'] ?? null;

        if ($this->model->get_user_by_email($email)) {
            echo json_encode(['status'=>'error','message'=>'Email already exists']);
            return;
        }

        $this->model->create_user($name, $email, $password, $role, $dealer_id);
        echo json_encode(['status'=>'success','message'=>'User created successfully']);
    }

    // ================= List Users =================
    public function list_users()
    {
        $currentUser = $_SESSION['user'] ?? null;
        if (!$currentUser) {
            echo json_encode(['status'=>'error','message'=>'Unauthorized']);
            return;
        }

        if ($currentUser['role'] === 'admin') {
            $users = $this->model->get_all_users();
        } elseif ($currentUser['role'] === 'dealer') {
            $allUsers = $this->model->get_all_users();
            $users = array_filter($allUsers, function($u) use ($currentUser) {
                return $u['dealer_id'] == $currentUser['dealer_id'];
            });
        } else {
            $users = [$this->model->get_user_by_id($currentUser['id'])];
        }

        echo json_encode($users);
    }

    // ================= Update User =================
    public function update()
    {
        $user_id = $_POST['id'] ?? null;
        $data = [
            'name' => $_POST['name'] ?? null,
            'email' => $_POST['email'] ?? null,
            'role' => $_POST['role'] ?? null,
            'dealer_id' => $_POST['dealer_id'] ?? null,
            'phone' => $_POST['phone'] ?? null
        ];

        $currentUser = $_SESSION['user'] ?? null;
        if (!$currentUser) {
            echo json_encode(['status'=>'error','message'=>'Unauthorized']);
            return;
        }

        if ($currentUser['role'] === 'dealer') {
            $user = $this->model->get_user_by_id($user_id);
            if ($user['dealer_id'] != $currentUser['dealer_id']) {
                echo json_encode(['status'=>'error','message'=>'Cannot update this user']);
                return;
            }
        }

        $this->model->update_user($user_id, $data);
        echo json_encode(['status'=>'success','message'=>'User updated successfully']);
    }

    // ================= Delete User =================
    public function delete()
    {
        $user_id = $_POST['id'] ?? null;
        $currentUser = $_SESSION['user'] ?? null;
        if (!$currentUser) {
            echo json_encode(['status'=>'error','message'=>'Unauthorized']);
            return;
        }

        if ($currentUser['role'] === 'dealer') {
            $user = $this->model->get_user_by_id($user_id);
            if ($user['dealer_id'] != $currentUser['dealer_id']) {
                echo json_encode(['status'=>'error','message'=>'Cannot delete this user']);
                return;
            }
        }

        $this->model->delete_user($user_id);
        echo json_encode(['status'=>'success','message'=>'User deleted successfully']);
    }

    // ================= Get Logged-in User =================
    public function me()
    {
        $user = $this->model->get_logged_in_user();
        echo json_encode($user ?: ['status'=>'error','message'=>'No user logged in']);
    }

}