<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class WarrantiesModel extends Model {
    protected $table = 'warranties';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    // Get warranty by ID
    public function get_warranty_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    // Get all warranties
    public function get_all_warranties()
    {
        return $this->db->table($this->table)->get_all();
    }

    // Get warranties for a specific car
    public function get_warranties_by_car($car_id)
    {
        return $this->db->table($this->table)
                        ->where('car_id', $car_id)
                        ->get_all();
    }

    // Create warranty
    public function create_warranty($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    // Update warranty
    public function update_warranty($id, $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data);
    }

    // Delete warranty
    public function delete_warranty($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete();
    }
}
