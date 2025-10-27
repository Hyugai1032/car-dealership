<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DealersModel extends Model {
    protected $table = 'dealers';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    // Get a single dealer by ID
    public function get_dealer_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    // Get all dealers
    public function get_all_dealers()
    {
        return $this->db->table($this->table)->get_all();
    }

    // Create a new dealer
    public function create_dealer($data)
    {
        return $this->db->table($this->table)
                        ->insert($data);
    }

    // Update dealer by ID
    public function update_dealer($id, $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data);
    }

    // Delete dealer by ID
    public function delete_dealer($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete();
    }
}
