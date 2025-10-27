<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DealersController extends Controller {

    private $model;

    public function __construct()
    {
        parent::__construct();

        // Initialize the model manually to avoid $this->call null issues
        require_once __DIR__ . '/../models/DealersModel.php';
        $this->model = new DealersModel();
    }

    // GET /api/dealers
    public function index()
    {
        $dealers = $this->model->get_all_dealers();
        echo json_encode(['status'=>'success','dealers'=>$dealers]);
    }

    // GET /api/dealers/(:num)
    public function show($id)
    {
        $dealer = $this->model->get_dealer_by_id($id);
        if ($dealer) {
            echo json_encode(['status'=>'success','dealer'=>$dealer]);
        } else {
            echo json_encode(['status'=>'error','message'=>'Dealer not found']);
        }
    }

    // POST /api/dealers
    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['name'])) {
            echo json_encode(['status'=>'error','message'=>'Dealer name required']);
            return;
        }

        $this->model->create_dealer($input);
        echo json_encode(['status'=>'success','message'=>'Dealer created']);
    }

    // PUT /api/dealers/(:num)
    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $updated = $this->model->update_dealer($id, $input);

        if ($updated) {
            echo json_encode(['status'=>'success','message'=>'Dealer updated']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Update failed']);
        }
    }

    // DELETE /api/dealers/(:num)
    public function destroy($id)
    {
        $deleted = $this->model->delete_dealer($id);

        if ($deleted) {
            echo json_encode(['status'=>'success','message'=>'Dealer deleted']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Delete failed']);
        }
    }
}
