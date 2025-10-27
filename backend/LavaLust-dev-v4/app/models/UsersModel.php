<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: UsersModel
 * 
 * Automatically generated via CLI.
 */
class UsersModel extends Model {
    protected $table = 'users';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }
    // Get a user by ID
    public function get_user_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    // Get a user by email (used for login)
    public function get_user_by_email($email)
    {
        return $this->db->table($this->table)
                        ->where('email', $email)
                        ->get();
    }

    // Update user password securely
    public function update_password($user_id, $new_password) 
    {
        return $this->db->table($this->table)
                        ->where('id', $user_id)
                        ->update([
                            'password_hash' => password_hash($new_password, PASSWORD_DEFAULT)
                        ]);
    }

    // Get all users
    public function get_all_users()
    {
        return $this->db->table($this->table)->get_all();
    }

    // Get currently logged-in user
    public function get_logged_in_user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user']['id'])) {
            return $this->get_user_by_id($_SESSION['user']['id']);
        }

        return null;
    }

    // Paginate and search users
    public function page($q = '', $records_per_page = null, $page = null)
    {
        $query = $this->db->table($this->table);

        if (!empty($q)) {
            $query->like('id', '%'.$q.'%')
                  ->or_like('name', '%'.$q.'%')
                  ->or_like('email', '%'.$q.'%')
                  ->or_like('role', '%'.$q.'%');
        }

        if (is_null($page)) {
            return $query->get_all();
        } else {
            // Count total rows for pagination
            $countQuery = clone $query;
            $data['total_rows'] = $countQuery->select_count('*', 'count')->get()['count'];

            // Fetch paginated records
            $data['records'] = $query->pagination($records_per_page, $page)->get_all();

            return $data;
        }
    }

    // Optional: Create a new user
    public function create_user($name, $email, $password, $role, $dealer_id = null)
    {
        return $this->db->table($this->table)
                        ->insert([
                            'name' => $name,
                            'email' => $email,
                            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                            'role' => $role,
                            'dealer_id' => $dealer_id
                        ]);
    }

    // Optional: Delete user
    public function delete_user($user_id)
    {
        return $this->db->table($this->table)
                        ->where('id', $user_id)
                        ->delete();
    }

    // Optional: Update user details
    public function update_user($user_id, $data)
    {
        // Only allow certain fields to be updated
        $allowed = ['name', 'email', 'role', 'dealer_id', 'phone'];
        $update_data = array_intersect_key($data, array_flip($allowed));

        return $this->db->table($this->table)
                        ->where('id', $user_id)
                        ->update($update_data);
    }
}