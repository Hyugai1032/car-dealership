<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class PaymentsController extends Controller {
    private $model;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/PaymentsModel.php';
        $this->model = new PaymentsModel();
    }

    public function index() {
        $payments = $this->model->get_all_payments();
        echo json_encode(['status'=>'success','payments'=>$payments]);
    }

    public function show($id) {
        $payment = $this->model->get_payment_by_id($id);
        echo json_encode($payment ? ['status'=>'success','payment'=>$payment] : ['status'=>'error','message'=>'Payment not found']);
    }

    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['user_id']) || empty($input['amount'])) {
            echo json_encode(['status'=>'error','message'=>'User ID and amount required']);
            return;
        }
        $this->model->create_payment($input);
        echo json_encode(['status'=>'success','message'=>'Payment recorded']);
    }

    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        $updated = $this->model->update_payment($id, $input);
        echo json_encode($updated ? ['status'=>'success','message'=>'Updated'] : ['status'=>'error','message'=>'Update failed']);
    }

    public function destroy($id) {
        $deleted = $this->model->delete_payment($id);
        echo json_encode($deleted ? ['status'=>'success','message'=>'Deleted'] : ['status'=>'error','message'=>'Delete failed']);
    }
}
