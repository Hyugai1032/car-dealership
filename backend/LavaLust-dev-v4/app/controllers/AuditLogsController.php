<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuditLogsController extends Controller {
    private $model;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/AuditLogsModel.php';
        $this->model = new AuditLogsModel();
    }

    // GET /api/audit_logs
    public function index() {
        $logs = $this->model->get_all_logs();
        echo json_encode(['status'=>'success','logs'=>$logs]);
    }

    // Optional: GET /api/audit_logs/(:num)
    public function show($id) {
        $log = $this->model->db->table('audit_logs')->where('id', $id)->get();
        echo json_encode($log ? ['status'=>'success','log'=>$log] : ['status'=>'error','message'=>'Log not found']);
    }
}
