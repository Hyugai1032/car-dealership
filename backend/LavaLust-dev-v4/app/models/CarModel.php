<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CarModel extends Model {
    protected $table = 'cars';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    // Get car by ID
    public function get_car_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    // Get all cars
    public function get_all_cars()
    {
        return $this->db->table($this->table)->get_all();
    }

    // Get cars by dealer
    public function get_cars_by_dealer($dealer_id)
    {
        return $this->db->table($this->table)
                        ->where('dealer_id', $dealer_id)
                        ->get_all();
    }

    // Create new car
    public function create_car($data)
    {
        return $this->db->table($this->table)
                        ->insert($data);
    }

    // Update car
    public function update_car($id, $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data);
    }

    // Delete car
    public function delete_car($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete();
    }
}
