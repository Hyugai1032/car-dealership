<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AppointmentModel extends Model {
    protected $table = 'appointments';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    // Get appointment by ID
    public function get_appointment_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    // Get all appointments
    public function get_all_appointments()
    {
        return $this->db->table($this->table)->get_all();
    }

    // Create new appointment
    public function create_appointment($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    // Update appointment
    public function update_appointment($id, $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data);
    }

    // Delete appointment
    public function delete_appointment($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete();
    }

    // Optional: get appointments for a specific dealer or user
    public function get_appointments_by_dealer($dealer_id)
    {
        return $this->db->table($this->table)
                        ->where('dealer_id', $dealer_id)
                        ->get_all();
    }

    public function get_appointments_by_user($user_id)
    {
        return $this->db->table($this->table)
                        ->where('user_id', $user_id)
                        ->get_all();
    }
}
