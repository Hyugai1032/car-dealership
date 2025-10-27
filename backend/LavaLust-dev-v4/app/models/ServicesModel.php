<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ServicesModel extends Model {
    protected $table = 'services';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    // Get service by ID
    public function get_service_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    // Get all services
    public function get_all_services()
    {
        return $this->db->table($this->table)->get_all();
    }

    // Get services for a specific car
    public function get_services_by_car($car_id)
    {
        return $this->db->table($this->table)
                        ->where('car_id', $car_id)
                        ->get_all();
    }

    // Create a service entry
    public function create_service($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    // Update service entry
    public function update_service($id, $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data);
    }

    // Delete service entry
    public function delete_service($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete();
    }
}
