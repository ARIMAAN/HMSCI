<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    // Constructor to load the database
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Generic GET method for fetching data
    public function get($entity) {
        $data = $this->db->get($entity)->result_array();
        echo json_encode($data);
    }

    // Generic POST method for inserting data
    public function post($entity) {
        $input_data = json_decode(file_get_contents('php://input'), true);
        if ($this->db->insert($entity, $input_data)) {
            echo json_encode(['status' => 'success', 'message' => ucfirst($entity) . ' added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add ' . $entity]);
        }
    }

    // Example: GET request for doctors
    public function get_doctors() {
        $this->get('doctor');
    }

    // Example: POST request for doctors
    public function post_doctor() {
        $this->post('doctor');
    }

    // Example: GET request for patients
    public function get_patients() {
        $this->get('patient');
    }

    // Example: POST request for patients
    public function post_patient() {
        $this->post('patient');
    }

    // Example: GET request for nurses
    public function get_nurses() {
        $this->get('nurse');
    }

    // Example: POST request for nurses
    public function post_nurse() {
        $this->post('nurse');
    }

    // Example: GET request for pharmacists
    public function get_pharmacists() {
        $this->get('pharmacist');
    }

    // Example: POST request for pharmacists
    public function post_pharmacist() {
        $this->post('pharmacist');
    }

    // Example: GET request for laboratorists
    public function get_laboratorists() {
        $this->get('laboratorist');
    }

    // Example: POST request for laboratorists
    public function post_laboratorist() {
        $this->post('laboratorist');
    }

    // Example: GET request for PHCs
    public function get_phcs() {
        $this->get('phc');
    }

    // Example: POST request for PHCs
    public function post_phc() {
        $this->post('phc');
    }
}
