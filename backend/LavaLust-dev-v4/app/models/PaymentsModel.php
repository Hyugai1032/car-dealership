<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class PaymentsModel extends Model {
    protected $table = 'payments';
    protected $primary_key = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function get_payment_by_id($id) {
        return $this->db->table($this->table)->where('id', $id)->get();
    }

    public function get_all_payments() {
        return $this->db->table($this->table)->get_all();
    }

    public function create_payment($data) {
        return $this->db->table($this->table)->insert($data);
    }

    public function update_payment($id, $data) {
        return $this->db->table($this->table)->where('id', $id)->update($data);
    }

    public function delete_payment($id) {
        return $this->db->table($this->table)->where('id', $id)->delete();
    }
}
