<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class InquiriesController extends Controller {
    private $model;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/InquiriesModel.php';
        $this->model = new InquiriesModel();
    }

    public function index() {
        $inquiries = $this->model->get_all_inquiries();
        echo json_encode(['status'=>'success','inquiries'=>$inquiries]);
    }

    public function show($id) {
        $inquiry = $this->model->get_inquiry_by_id($id);
        echo json_encode($inquiry ? ['status'=>'success','inquiry'=>$inquiry] : ['status'=>'error','message'=>'Inquiry not found']);
    }

    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['message'])) {
            echo json_encode(['status'=>'error','message'=>'Message is required']);
            return;
        }
        $this->model->create_inquiry($input);
        echo json_encode(['status'=>'success','message'=>'Inquiry submitted']);
    }

    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        $updated = $this->model->update_inquiry($id, $input);
        echo json_encode($updated ? ['status'=>'success','message'=>'Updated'] : ['status'=>'error','message'=>'Update failed']);
    }

    public function destroy($id) {
        $deleted = $this->model->delete_inquiry($id);
        echo json_encode($deleted ? ['status'=>'success','message'=>'Deleted'] : ['status'=>'error','message'=>'Delete failed']);
    }
}
