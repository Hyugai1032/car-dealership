<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CarController extends Controller {

    private $model;
    private $dealersModel;

    public function __construct()
    {
        parent::__construct();

        // Load models manually
        require_once __DIR__ . '/../models/CarModel.php';
        require_once __DIR__ . '/../models/DealersModel.php';
        $this->model = new CarModel();
        $this->dealersModel = new DealersModel();
    }

    // GET /api/cars
    public function index()
    {
        $cars = $this->model->get_all_cars();

        // Convert relative image paths to full URLs
        foreach ($cars as &$car) {
            if (!empty($car['main_image'])) {
                $car['main_image'] = base_url($car['main_image']);
            }

            // Decode gallery JSON and prepend full URLs
            if (!empty($car['gallery'])) {
                $gallery = json_decode($car['gallery'], true);
                if (is_array($gallery)) {
                    $car['gallery'] = array_map(function($path) {
                        return base_url($path);
                    }, $gallery);
                }
            }
        }

        echo json_encode(['status' => 'success', 'cars' => $cars]);
    }

    // GET /api/cars/(:num)
    public function show($id)
    {
        $car = $this->model->get_car_by_id($id);
        if ($car) {
            echo json_encode(['status'=>'success','car'=>$car]);
        } else {
            echo json_encode(['status'=>'error','message'=>'Car not found']);
        }
    }

    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['dealer_id']) || empty($input['make']) || empty($input['model'])) {
            echo json_encode(['status'=>'error','message'=>'Dealer, make, and model are required']);
            return;
        }

        $dealer = $this->dealersModel->get_dealer_by_id($input['dealer_id']);
        if (!$dealer) {
            echo json_encode(['status'=>'error','message'=>'Selected dealer does not exist']);
            return;
        }

        $this->model->create_car($input);
        echo json_encode(['status'=>'success','message'=>'Car created']);
    }


    // PUT /api/cars/(:num)
    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Optional: validate dealer
        if (isset($input['dealer_id'])) {
            $dealer = $this->dealersModel->get_dealer_by_id($input['dealer_id']);
            if (!$dealer) {
                echo json_encode(['status'=>'error','message'=>'Selected dealer does not exist']);
                return;
            }
        }

        $updated = $this->model->update_car($id, $input);
        if ($updated) {
            echo json_encode(['status'=>'success','message'=>'Car updated']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Update failed']);
        }
    }

    public function destroy($id)
    {
        $deleted = $this->model->delete_car($id);
        if ($deleted) {
            echo json_encode(['status'=>'success','message'=>'Car deleted']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Delete failed']);
        }
    }


}
