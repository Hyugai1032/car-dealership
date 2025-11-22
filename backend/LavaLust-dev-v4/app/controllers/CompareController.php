<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CompareController extends Controller {

    // ADD TO COMPARE
    public function add() {
        $car_id = $this->input->post('car_id');
        if (!$car_id) return $this->api->respond_error('No car selected');

        // Get current compare list
        $list = $this->session->userdata('compare') ?: [];

        // Max 4 lang
        if (count($list) >= 4) {
            return $this->api->respond_error('Max 4 cars only');
        }

        // Wag madoble
        if (in_array($car_id, $list)) {
            return $this->api->respond(['count' => count($list)]);
        }

        $list[] = $car_id;
        $this->session->set_userdata('compare', $list);

        return $this->api->respond(['count' => count($list)]);
    }

    // REMOVE FROM COMPARE
    public function remove() {
        $car_id = $this->input->post('car_id');
        $list = $this->session->userdata('compare') ?: [];

        $list = array_values(array_diff($list, [$car_id]));
        $this->session->set_userdata('compare', $list);

        return $this->api->respond(['count' => count($list)]);
    }

    // SHOW ALL COMPARED CARS + WARRANTY
    public function list() {
        $ids = $this->session->userdata('compare') ?: [];

        if (empty($ids)) {
            return $this->api->respond(['cars' => [], 'count' => 0]);
        }

        $placeholders = str_repeat('?,', count($ids)-1) . '?';
        $sql = "SELECT c.*, w.provider, w.coverage, w.expiry_date 
                FROM cars c 
                LEFT JOIN warranties w ON c.id = w.car_id 
                WHERE c.id IN ($placeholders)";

        $cars = $this->db->raw($sql, $ids)->result();

        return $this->api->respond([
            'cars' => $cars,
            'count' => count($cars)
        ]);
    }

    // CLEAR ALL
    public function clear() {
        $this->session->unset_userdata('compare');
        return $this->api->respond(['message' => 'Cleared!']);
    }

    // COUNT LANG (para sa badge sa navbar)
    public function count() {
        $count = count($this->session->userdata('compare') ?: []);
        return $this->api->respond(['count' => $count]);
    }
}