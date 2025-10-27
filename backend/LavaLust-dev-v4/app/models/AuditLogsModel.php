<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuditLogsModel extends Model {
    protected $table = 'audit_logs';
    protected $primary_key = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function log($user_id, $action, $details = null) {
        return $this->db->table($this->table)->insert([
            'user_id' => $user_id,
            'action' => $action,
            'details' => $details
        ]);
    }

    public function get_all_logs() {
        return $this->db->table($this->table)->get_all();
    }
}
