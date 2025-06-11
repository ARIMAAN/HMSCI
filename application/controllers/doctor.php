<?php

if (!defined('BASEPATH'))

	exit('No direct script access allowed');




class Doctor extends CI_Controller

{

	

	

	function __construct()

	{

		parent::__construct();

		$this->load->database();

		/*cache control*/

		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

		$this->output->set_header('Pragma: no-cache');

		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

	}

	

	/***Default function, redirects to login page if no admin logged in yet***/

	public function index()

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		if ($this->session->userdata('doctor_login') == 1)

			redirect(base_url() . 'index.php?doctor/dashboard', 'refresh');

	}

	

	/***DOCTOR DASHBOARD***/

	function dashboard()

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

			

		$page_data['page_name']  = 'dashboard';

		$page_data['page_title'] = ('Doctor Dashboard');

		$this->load->view('index', $page_data);

	}

	/***Manage patients**/

	function manage_patient($param1 = '', $param2 = '', $param3 = '')

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

			

		if ($param1 == 'create') {

			$data['name']                      = $this->input->post('name');

			$data['email']                     = $this->input->post('email');

			$data['password']                  = $this->input->post('password');

			$data['address']                   = $this->input->post('address');

			$data['phone']                     = $this->input->post('phone');

			$data['sex']                       = $this->input->post('sex');

			$data['birth_date']                = $this->input->post('birth_date');

			$data['age']                       = $this->input->post('age');

			$data['blood_group']               = $this->input->post('blood_group');

			$data['account_opening_timestamp'] = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));

			$this->db->insert('patient', $data);

			$this->email_model->account_opening_email('patient', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL

			$this->session->set_flashdata('flash_message', ('Account Opened'));

			

			redirect(base_url() . 'index.php?doctor/manage_patient', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['name']        = $this->input->post('name');

			$data['email']       = $this->input->post('email');

			$data['password']    = $this->input->post('password');

			$data['address']     = $this->input->post('address');

			$data['phone']       = $this->input->post('phone');

			$data['sex']         = $this->input->post('sex');

			$data['birth_date']  = $this->input->post('birth_date');

			$data['age']         = $this->input->post('age');

			$data['blood_group'] = $this->input->post('blood_group');

			

			$this->db->where('patient_id', $param3);

			$this->db->update('patient', $data);

			$this->session->set_flashdata('flash_message', ('Account Updated'));

			redirect(base_url() . 'index.php?doctor/manage_patient', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('patient', array(

				'patient_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('patient_id', $param2);

			$this->db->delete('patient');

			

			$this->session->set_flashdata('flash_message', ('Account Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_patient', 'refresh');

		}

		$page_data['page_name']  = 'manage_patient';

		$page_data['page_title'] = ('Manage Patient');

		$page_data['patients']   = $this->db->get('patient')->result_array();

		$this->load->view('index', $page_data);

	}

	

	/***MANAGE APPOINTMENTS******/

	function manage_appointment($param1 = '', $param2 = '', $param3 = '')

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		if ($param1 == 'create') {

			$data['doctor_id']             = $this->input->post('doctor_id');

			$data['patient_id']            = $this->input->post('patient_id');

			$data['appointment_timestamp'] = strtotime($this->input->post('appointment_timestamp'));

			$this->db->insert('appointment', $data);

			$this->session->set_flashdata('flash_message', ('Appointment Created'));

			redirect(base_url() . 'index.php?doctor/manage_appointment', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['doctor_id']             = $this->input->post('doctor_id');

			$data['patient_id']            = $this->input->post('patient_id');

			$data['appointment_timestamp'] = strtotime($this->input->post('appointment_timestamp'));

			$this->db->where('appointment_id', $param3);

			$this->db->update('appointment', $data);

			$this->session->set_flashdata('flash_message', ('Appointment Updated'));

			redirect(base_url() . 'index.php?doctor/manage_appointment', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('appointment', array(

				'appointment_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('appointment_id', $param2);

			$this->db->delete('appointment');

			$this->session->set_flashdata('flash_message', ('Appointment Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_appointment', 'refresh');

		}

		$page_data['page_name']    = 'manage_appointment';

		$page_data['page_title']   = ('Manage Appointment');

		$page_data['appointments'] = $this->db->get_where('appointment', array(

			'doctor_id' => $this->session->userdata('doctor_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

	

	/***MANAGE PRESCRIPTIONS******/

	function manage_prescription($param1 = '', $param2 = '', $param3 = '')

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

			

		

		if ($param1 == 'create') {

			$data['doctor_id']                  = $this->input->post('doctor_id');

			$data['patient_id']                 = $this->input->post('patient_id');

			$data['creation_timestamp']         = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));

			$data['case_history']               = $this->input->post('case_history');

			$data['medication']                 = $this->input->post('medication');

			$data['medication_from_pharmacist'] = $this->input->post('medication_from_pharmacist');

			$data['description']                = $this->input->post('description');

			

			$this->db->insert('prescription', $data);

			$this->session->set_flashdata('flash_message', ('Prescription Created'));

			

			redirect(base_url() . 'index.php?doctor/manage_prescription', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['doctor_id']                  = $this->input->post('doctor_id');

			$data['patient_id']                 = $this->input->post('patient_id');

			$data['case_history']               = $this->input->post('case_history');

			$data['medication']                 = $this->input->post('medication');

			$data['medication_from_pharmacist'] = $this->input->post('medication_from_pharmacist');

			$data['description']                = $this->input->post('description');

			

			$this->db->where('prescription_id', $param3);

			$this->db->update('prescription', $data);

			$this->session->set_flashdata('flash_message', ('Prescription Updated'));

			redirect(base_url() . 'index.php?doctor/manage_prescription', 'refresh');

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('prescription', array(

				'prescription_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('prescription_id', $param2);

			$this->db->delete('prescription');

			$this->session->set_flashdata('flash_message', ('Prescription Deleted'));

			

			redirect(base_url() . 'index.php?doctor/manage_prescription', 'refresh');

		}

		$page_data['page_name']     = 'manage_prescription';

		$page_data['page_title']    = ('Manage Prescription');

		$page_data['prescriptions'] = $this->db->get('prescription')->result_array();

		$this->load->view('index', $page_data);

	}

		
/******ALLOT / DISCHARGE BED TO PATIENTS*****/

	function manage_bed_allotment($param1 = '', $param2 = '', $param3 = '')

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		//create a new allotment only in available / unalloted beds. beds can be ward,cabin,icu,other types

		if ($param1 == 'create') {

			$data['bed_id']              = $this->input->post('bed_id');

			$data['patient_id']          = $this->input->post('patient_id');

			$data['allotment_timestamp'] = $this->input->post('allotment_timestamp');

			$data['discharge_timestamp'] = $this->input->post('discharge_timestamp');

			$this->db->insert('bed_allotment', $data);

			$this->session->set_flashdata('flash_message', ('Bed Alloted'));

			redirect(base_url() . 'index.php?doctor/manage_bed_allotment', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['bed_id']              = $this->input->post('bed_id');

			$data['patient_id']          = $this->input->post('patient_id');

			$data['allotment_timestamp'] = $this->input->post('allotment_timestamp');

			$data['discharge_timestamp'] = $this->input->post('discharge_timestamp');

			$this->db->where('bed_allotment_id', $param3);

			$this->db->update('bed_allotment', $data);

			$this->session->set_flashdata('flash_message', ('Bed Allotment Updated'));

			redirect(base_url() . 'index.php?doctor/manage_bed_allotment', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('bed_allotment', array(

				'bed_allotment_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('bed_allotment_id', $param2);

			$this->db->delete('bed_allotment');

			$this->session->set_flashdata('flash_message', ('Bed Allotment Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_bed_allotment', 'refresh');

		}

		$page_data['page_name']     = 'manage_bed_allotment';

		$page_data['page_title']    = ('Manage Bed Allotment');

		$page_data['bed_allotment'] = $this->db->get('bed_allotment')->result_array();

		$this->load->view('index', $page_data);

	}

	

	

	/***CREATE REPORT BIRTH,DEATH,OPERATION**/

	function manage_report($param1 = '', $param2 = '', $param3 = '')

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		//create a new report baby birth,patient death, operation , other types

		if ($param1 == 'create') {

			$data['type']        = $this->input->post('type');

			$data['description'] = $this->input->post('description');

			$data['timestamp']   = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));

			$data['doctor_id']   = $this->input->post('doctor_id');

			$data['patient_id']  = $this->input->post('patient_id');

			$this->db->insert('report', $data);

			$this->session->set_flashdata('flash_message', ('Report Created'));

			redirect(base_url() . 'index.php?doctor/manage_report', 'refresh');

		}

		if ($param1 == 'delete') {

			$this->db->where('report_id', $param2);

			$this->db->delete('report');

			$this->session->set_flashdata('flash_message', ('Report Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_report', 'refresh');

		}

		$page_data['page_name']  = 'manage_report';

		$page_data['page_title'] = ('Manage Report');

		$page_data['reports']    = $this->db->get('report')->result_array();

		$this->load->view('index', $page_data);

	}

	

	

	/******MANAGE OWN PROFILE AND CHANGE PASSWORD***/

	function manage_profile($param1 = '', $param2 = '', $param3 = '')

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == 'update_profile_info') {

			$data['name']    = $this->input->post('name');

			$data['email']   = $this->input->post('email');

			$data['address'] = $this->input->post('address');

			$data['phone']   = $this->input->post('phone');

			$data['profile'] = $this->input->post('profile');

			

			$this->db->where('doctor_id', $this->session->userdata('doctor_id'));

			$this->db->update('doctor', $data);

			$this->session->set_flashdata('flash_message', ('Profile Updated'));

			redirect(base_url() . base_url() . 'index.php?doctor/manage_profile/', 'refresh');

		}

		if ($param1 == 'change_password') {

			$data['password']             = $this->input->post('password');

			$data['new_password']         = $this->input->post('new_password');

			$data['confirm_new_password'] = $this->input->post('confirm_new_password');

			

			$current_password = $this->db->get_where('doctor', array(

				'doctor_id' => $this->session->userdata('doctor_id')

			))->row()->password;

			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {

				$this->db->where('doctor_id', $this->session->userdata('doctor_id'));

				$this->db->update('doctor', array(

					'password' => $data['new_password']

				));

				$this->session->set_flashdata('flash_message', ('Password Updated'));

			} else {

				$this->session->set_flashdata('flash_message', ('Password Mismatch'));

			}

			redirect(base_url() . base_url() . 'index.php?doctor/manage_profile/', 'refresh');

		}

		$page_data['page_name']    = 'manage_profile';

		$page_data['page_title']   = ('Manage Profile');

		$page_data['edit_profile'] = $this->db->get_where('doctor', array(

			'doctor_id' => $this->session->userdata('doctor_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

	

	/***LOG ATTENDANCE***/
	function log_attendance() {
    // Ensure clean output
    if (ob_get_level()) ob_end_clean();
    
    // Set JSON header
    $this->output->set_content_type('application/json');
    
    if ($this->session->userdata('doctor_login') != 1) {
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]));
        return;
    }

    try {
        $doctor_id = $this->session->userdata('doctor_id');
        $today = date('Y-m-d');
        $location = $this->input->post('location');

        if (!$location) {
            throw new Exception('Location is required');
        }
        
        // Check for existing attendance today
        $existing = $this->db->where('doctor_id', $doctor_id)
                            ->where('DATE(FROM_UNIXTIME(timestamp))', $today)
                            ->get('attendance')
                            ->row();

        $data = array(
            'doctor_id' => $doctor_id,
            'timestamp' => time(),
            'location' => $location,
            'status' => 'present',
            'duration' => $existing ? $existing->duration : 0
        );

        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('attendance', $data);
        } else {
            $this->db->insert('attendance', $data);
            // Store attendance state in session
            $this->session->set_userdata('attendance_active', true);
            $this->session->set_userdata('attendance_start_time', time());
        }

        $this->output->set_output(json_encode([
            'status' => 'success',
            'message' => 'Attendance Logged'
        ]));
        
    } catch (Exception $e) {
        $this->output->set_status_header(500);
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]));
    }
}

	public function get_phc_coordinates() {
    if ($this->session->userdata('doctor_login') != 1) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    $phc_id = $this->session->userdata('phc_id'); // Assuming PHC ID is stored in session
    $phc = $this->db->get_where('phc', array('phc_id' => $phc_id))->row_array();

    if ($phc) {
        header('Content-Type: application/json'); // Ensure JSON response
        echo json_encode(['latitude' => $phc['latitude'], 'longitude' => $phc['longitude']]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'PHC not found']);
    }
    exit; // Ensure no additional output
}

public function stop_attendance() {
    if (ob_get_level()) ob_end_clean();
    $this->output->set_content_type('application/json');
    
    if ($this->session->userdata('doctor_login') != 1) {
        $this->output->set_status_header(401);
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]));
        return;
    }

    try {
        $doctor_id = $this->session->userdata('doctor_id');
        $new_duration = intval($this->input->post('duration'));
        $today = date('Y-m-d');

        // Begin transaction
        $this->db->trans_start();

        // First check if entry exists for today
        $existing = $this->db->where('doctor_id', $doctor_id)
                            ->where('attendance_date', $today)
                            ->get('doctor_attendance')
                            ->row();

        if ($existing) {
            // Add new duration to existing duration
            $total_duration = $existing->duration + $new_duration;
            
            // Update existing record
            $this->db->where('attendance_id', $existing->attendance_id)
                     ->update('doctor_attendance', [
                         'duration' => $total_duration,
                         'status' => 'present'
                     ]);
        } else {
            // Create new record
            $this->db->insert('doctor_attendance', [
                'doctor_id' => $doctor_id,
                'attendance_date' => $today,
                'status' => 'present',
                'duration' => $new_duration
            ]);
            $total_duration = $new_duration;
        }

        // Update attendance record
        $this->db->where('doctor_id', $doctor_id)
                 ->where('DATE(FROM_UNIXTIME(timestamp))', $today)
                 ->where('status', 'present')
                 ->update('attendance', [
                     'status' => 'completed',
                     'duration' => $total_duration,
                     'end_timestamp' => time()
                 ]);

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Failed to update attendance records');
        }

        // Clear session data
        $this->session->unset_userdata('attendance_active');
        $this->session->unset_userdata('attendance_start_time');

        $this->output->set_output(json_encode([
            'status' => 'success',
            'message' => 'Attendance stopped successfully',
            'duration' => $total_duration
        ]));

    } catch (Exception $e) {
        $this->db->trans_rollback();
        log_message('error', 'Stop attendance failed: ' . $e->getMessage());
        $this->output->set_status_header(500);
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]));
    }
}

    public function update_attendance() {
    if (ob_get_level()) ob_end_clean();
    $this->output->set_content_type('application/json');
    
    if ($this->session->userdata('doctor_login') != 1) {
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]));
        return;
    }

    try {
        $minutes = intval($this->input->post('minutes'));
        $doctor_id = $this->session->userdata('doctor_id');
        $today = date('Y-m-d');
        
        if (!$minutes) {
            throw new Exception('Invalid duration provided');
        }

        $result = $this->db->where('doctor_id', $doctor_id)
                          ->where('status', 'present')
                          ->where('DATE(FROM_UNIXTIME(timestamp))', $today)
                          ->update('attendance', ['duration' => $minutes]);

        if ($result) {
            $this->output->set_output(json_encode([
                'status' => 'success',
                'message' => 'Attendance updated',
                'minutes' => $minutes
            ]));
        } else {
            throw new Exception('No active attendance found');
        }
        
    } catch (Exception $e) {
        $this->output->set_status_header(500);
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]));
    }
}

public function update_attendance_duration() {
    if (ob_get_level()) ob_end_clean();
    $this->output->set_content_type('application/json');
    
    if ($this->session->userdata('doctor_login') != 1) {
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]));
        return;
    }

    try {
        $doctor_id = $this->session->userdata('doctor_id');
        $duration = intval($this->input->post('duration'));
        $today = date('Y-m-d');

        // Check if entry exists for today
        $existing = $this->db->where('doctor_id', $doctor_id)
                            ->where('attendance_date', $today)
                            ->get('doctor_attendance')
                            ->row();

        if ($existing) {
            // Update existing record by adding new duration
            $total_duration = $existing->duration + $duration;
            $this->db->where('attendance_id', $existing->attendance_id)
                     ->update('doctor_attendance', [
                         'duration' => $total_duration,
                         'status' => 'present'
                     ]);
            $final_duration = $total_duration;
        } else {
            // Create new record
            $this->db->insert('doctor_attendance', [
                'doctor_id' => $doctor_id,
                'attendance_date' => $today,
                'status' => 'present',
                'duration' => $duration
            ]);
            $final_duration = $duration;
        }

        // Update attendance status to completed
        $this->db->where('doctor_id', $doctor_id)
                 ->where('DATE(FROM_UNIXTIME(timestamp))', $today)
                 ->where('status', 'present')
                 ->update('attendance', [
                     'status' => 'completed',
                     'duration' => $final_duration,
                     'end_timestamp' => time()
                 ]);

        $this->output->set_output(json_encode([
            'status' => 'success',
            'message' => 'Duration updated successfully',
            'total_duration' => $final_duration
        ]));

    } catch (Exception $e) {
        $this->output->set_status_header(500);
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]));
    }
}

// Add new method to check attendance state
public function check_attendance_state() {
    if (ob_get_level()) ob_end_clean();
    $this->output->set_content_type('application/json');
    
    if ($this->session->userdata('doctor_login') != 1) {
        $this->output->set_output(json_encode([
            'status' => 'error',
            'message' => 'Unauthorized'
        ]));
        return;
    }

    $active = $this->session->userdata('attendance_active');
    $start_time = $this->session->userdata('attendance_start_time');
    
    $this->output->set_output(json_encode([
        'status' => 'success',
        'active' => $active ? true : false,
        'start_time' => $start_time
    ]));
}

}