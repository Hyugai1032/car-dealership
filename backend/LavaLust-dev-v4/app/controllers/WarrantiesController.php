<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class WarrantiesController extends Controller {

    private $model;
    private $carsModel;

    public function __construct()
    {
        parent::__construct();

        require_once __DIR__ . '/../models/WarrantiesModel.php';
        require_once __DIR__ . '/../models/CarModel.php';

        $this->model = new WarrantiesModel();
        $this->carsModel = new CarModel();
    }

    // GET /api/warranties
    public function index()
    {
        $warranties = $this->model->get_all_warranties();
        echo json_encode(['status'=>'success','warranties'=>$warranties]);
    }

    // GET /api/warranties/(:num)
    public function show($id)
    {
        $warranty = $this->model->get_warranty_by_id($id);
        if ($warranty) {
            echo json_encode(['status'=>'success','warranty'=>$warranty]);
        } else {
            echo json_encode(['status'=>'error','message'=>'Warranty not found']);
        }
    }

    // POST /api/warranties
    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car ID is required']);
            return;
        }

        // Check if car exists
        if (!$this->carsModel->get_car_by_id($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car does not exist']);
            return;
        }

        $this->model->create_warranty($input);
        echo json_encode(['status'=>'success','message'=>'Warranty created']);
    }

    // PUT /api/warranties/(:num)
    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['car_id']) && !$this->carsModel->get_car_by_id($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car does not exist']);
            return;
        }

        $updated = $this->model->update_warranty($id, $input);
        if ($updated) {
            echo json_encode(['status'=>'success','message'=>'Warranty updated']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Update failed']);
        }
    }

    // DELETE /api/warranties/(:num)
    public function destroy($id)
    {
        $deleted = $this->model->delete_warranty($id);
        if ($deleted) {
            echo json_encode(['status'=>'success','message'=>'Warranty deleted']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Delete failed']);
        }
    }
}

