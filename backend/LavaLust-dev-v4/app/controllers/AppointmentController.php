<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AppointmentController extends Controller {

    private $model;
    private $carsModel;
    private $usersModel;
    private $dealersModel;

    public function __construct()
    {
        parent::__construct();

        require_once __DIR__ . '/../models/AppointmentModel.php';
        require_once __DIR__ . '/../models/CarModel.php';
        require_once __DIR__ . '/../models/UsersModel.php';
        require_once __DIR__ . '/../models/DealersModel.php';

        $this->model = new AppointmentModel();
        $this->carsModel = new CarModel();
        $this->usersModel = new UsersModel();
        $this->dealersModel = new DealersModel();
    }

    // GET /api/appointments
    public function index()
    {
        $appointments = $this->model->get_all_appointments();
        echo json_encode(['status'=>'success','appointments'=>$appointments]);
    }

    // GET /api/appointments/(:num)
    public function show($id)
    {
        $appointment = $this->model->get_appointment_by_id($id);
        if ($appointment) {
            echo json_encode(['status'=>'success','appointment'=>$appointment]);
        } else {
            echo json_encode(['status'=>'error','message'=>'Appointment not found']);
        }
    }

    // POST /api/appointments
    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        if (empty($input['car_id']) || empty($input['user_id']) || empty($input['dealer_id']) || empty($input['appointment_at'])) {
            echo json_encode(['status'=>'error','message'=>'Car, user, dealer, and appointment date are required']);
            return;
        }

        // Check if car, user, dealer exist
        if (!$this->carsModel->get_car_by_id($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car does not exist']);
            return;
        }
        if (!$this->usersModel->get_user_by_id($input['user_id'])) {
            echo json_encode(['status'=>'error','message'=>'User does not exist']);
            return;
        }
        if (!$this->dealersModel->get_dealer_by_id($input['dealer_id'])) {
            echo json_encode(['status'=>'error','message'=>'Dealer does not exist']);
            return;
        }

        $this->model->create_appointment($input);
        echo json_encode(['status'=>'success','message'=>'Appointment created']);
    }

    // PUT /api/appointments/(:num)
    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['car_id']) && !$this->carsModel->get_car_by_id($input['car_id'])) {
            echo json_encode(['status'=>'error','message'=>'Car does not exist']);
            return;
        }
        if (isset($input['user_id']) && !$this->usersModel->get_user_by_id($input['user_id'])) {
            echo json_encode(['status'=>'error','message'=>'User does not exist']);
            return;
        }
        if (isset($input['dealer_id']) && !$this->dealersModel->get_dealer_by_id($input['dealer_id'])) {
            echo json_encode(['status'=>'error','message'=>'Dealer does not exist']);
            return;
        }

        $updated = $this->model->update_appointment($id, $input);
        if ($updated) {
            echo json_encode(['status'=>'success','message'=>'Appointment updated']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Update failed']);
        }
    }

    // DELETE /api/appointments/(:num)
    public function destroy($id)
    {
        $deleted = $this->model->delete_appointment($id);
        if ($deleted) {
            echo json_encode(['status'=>'success','message'=>'Appointment deleted']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Delete failed']);
        }
    }
}
