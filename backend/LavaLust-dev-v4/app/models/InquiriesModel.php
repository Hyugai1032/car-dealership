<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class InquiriesModel extends Model {
    protected $table = 'inquiries';
    protected $primary_key = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function get_inquiry_by_id($id) {
        return $this->db->table($this->table)->where('id', $id)->get();
    }

    public function get_all_inquiries() {
        return $this->db->table($this->table)->get_all();
    }

    public function create_inquiry($data) {
        return $this->db->table($this->table)->insert($data);
    }

    public function update_inquiry($id, $data) {
        return $this->db->table($this->table)->where('id', $id)->update($data);
    }

    public function delete_inquiry($id) {
        return $this->db->table($this->table)->where('id', $id)->delete();
    }

    public function get_inquiries_by_car($car_id) {
        return $this->db->table($this->table)->where('car_id', $car_id)->get_all();
    }
}
