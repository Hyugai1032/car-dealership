<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ServicesController extends Controller {

    private $model;
    private $carsModel;

    public function __construct()
    {
        parent::__construct();

        require_once __DIR__ . '/../models/ServicesModel.php';
        require_once __DIR__ . '/../models/CarModel.php';

        $this->model = new ServicesModel();
        $this->carsModel = new CarModel();
    }

    // GET /api/services
    public function index()
    {
        $services = $this->model->get_all_services();
        echo json_encode(['status'=>'success','services'=>$services]);
    }

    // GET /api/services/(:num)
    public function show($id)
    {
        $service = $this->model->get_service_by_id($id);
        if ($service) {
            echo json_encode(['status'=>'success','service'=>$service]);
        } else {
            echo json_encode(['status'=>'error','message'=>'Service not found']);
        }
    }

    // GET /api/cars/(:num)/services
    public function by_car($car_id)
    {
        if (!$this->carsModel->get_car_by_id($car_id)) {
            echo json_encode(['status'=>'error','message'=>'Car not found']);
            return;
        }

        $services = $this->model->get_services_by_car($car_id);
        echo json_encode(['status'=>'success','services'=>$services]);
    }

    // POST /api/services
    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car ID is required']);
            return;
        }

        if (!$this->carsModel->get_car_by_id($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car does not exist']);
            return;
        }

        $this->model->create_service($input);
        echo json_encode(['status'=>'success','message'=>'Service record created']);
    }

    // PUT /api/services/(:num)
    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['car_id']) && !$this->carsModel->get_car_by_id($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car does not exist']);
            return;
        }

        $updated = $this->model->update_service($id, $input);
        if ($updated) {
            echo json_encode(['status'=>'success','message'=>'Service record updated']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Update failed']);
        }
    }

    // DELETE /api/services/(:num)
    public function destroy($id)
    {
        $deleted = $this->model->delete_service($id);
        if ($deleted) {
            echo json_encode(['status'=>'success','message'=>'Service record deleted']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Delete failed']);
        }
    }
}
