<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*	
 *	Hospital Management system
 */

class Admin extends CI_Controller
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
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		if ($this->session->userdata('admin_login') == 1)
			redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
	}
	
	/***ADMIN DASHBOARD***/
	function dashboard()
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		$page_data['page_name']  = 'dashboard';
		$page_data['page_title'] = ('Admin Dashboard');
		$this->load->view('index', $page_data);
	}
	
	/***PHC MANAGEMENT***/
	function manage_phc($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') { // Handles adding a new PHC
			$data['phc_id']    = $this->input->post('phc_id');
			$data['phc_name']  = $this->input->post('phc_name');
			$data['latitude']  = $this->input->post('latitude');
			$data['longitude'] = $this->input->post('longitude');
			$data['email']     = $this->input->post('email');
			$data['password']  = $this->input->post('password');
			$this->db->insert('phc', $data);

			// Set a success message
			$this->session->set_flashdata('flash_message', 'PHC Created Successfully');

			// Redirect back to the same page to refresh the form and table
			redirect(base_url() . 'index.php?admin/manage_phc', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			// Only update allowed fields, exclude phc_id since it's primary key
			$data = array(
				'phc_name' => $this->input->post('phc_name'),
				'latitude' => $this->input->post('latitude'), 
				'longitude' => $this->input->post('longitude'),
				'password' => $this->input->post('password'),
				'email' => $this->input->post('email')
			);

			$this->db->where('phc_id', $param3);
			$success = $this->db->update('phc', $data);

			if ($success) {
				$this->session->set_flashdata('flash_message', 'PHC Updated Successfully');
			} else {
				$this->session->set_flashdata('error_message', 'Error updating PHC');
			}

			// Redirect back to edit page
			redirect(base_url() . 'index.php?admin/manage_phc/edit/' . $param3, 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('phc', array(
				'phc_id' => $param2
			))->result_array();
			$page_data['page_name'] = 'manage_phc';
			$page_data['page_title'] = ('Edit PHC');
			$page_data['phcs'] = $this->db->get('phc')->result_array();
			$this->load->view('index', $page_data);
			return;
		}
		if ($param1 == 'delete') {
			$this->db->where('phc_id', $param2);
			$this->db->delete('phc');
			$this->session->set_flashdata('flash_message', ('PHC Deleted Successfully'));
			redirect(base_url() . 'index.php?admin/manage_phc', 'refresh');
		}
		$page_data['page_name']  = 'manage_phc';
		$page_data['page_title'] = ('Manage PHC');
		$page_data['phcs']       = $this->db->get('phc')->result_array(); // Fetch all PHC data

		$this->load->view('admin/manage_phc', $page_data); // Pass data to the view
	}
	
	/***DEPARTMENTS OF DOCTORS********/
	function manage_department($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['phc_name']   = $this->input->post('phc_name');
			$data['latitude']   = $this->input->post('latitude');
			$data['longitude']  = $this->input->post('longitude');
			$data['password']   = $this->input->post('password');
			$data['email']      = $this->input->post('email');
			$this->db->insert('phc', $data);
			$this->session->set_flashdata('flash_message', ('PHC Created'));
			redirect(base_url() . 'index.php?admin/manage_department', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['phc_name']   = $this->input->post('phc_name');
			$data['latitude']   = $this->input->post('latitude');
			$data['longitude']  = $this->input->post('longitude');
			$data['password']   = $this->input->post('password');
			$data['email']      = $this->input->post('email');
			$this->db->where('phc_id', $param3);
			$this->db->update('phc', $data);
			$this->session->set_flashdata('flash_message', ('PHC Updated'));
			redirect(base_url() . 'index.php?admin/manage_department', 'refresh');
			
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('phc', array(
				'phc_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('phc_id', $param2);
			$this->db->delete('phc');
			$this->session->set_flashdata('flash_message', ('PHC Deleted'));
			redirect(base_url() . 'index.php?admin/manage_department', 'refresh');
		}

		// Fetch all PHC data
		$page_data['phcs'] = $this->db->get('phc')->result_array();

		$page_data['page_name']   = 'manage_department';
		$page_data['page_title']  = ('Manage Department');
		$this->load->view('index', $page_data);
		
	}
	/***Manage doctors**/
	function manage_doctor($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == 'create') {
			// Handle AJAX request for adding a doctor
			if ($this->input->is_ajax_request()) {
				$data['doctor_id']    = $this->input->post('doctor_id');
				$data['name']         = $this->input->post('name');
				$data['email']        = $this->input->post('email');
				$data['password']     = $this->input->post('password');
				$data['address']      = $this->input->post('address');
				$data['phone']        = $this->input->post('phone');
				$data['phc_id']       = $this->input->post('phc_id');

				$this->db->insert('doctor', $data);

				// Return success response
				echo json_encode(['status' => 'success', 'message' => 'Doctor added successfully']);
				return;
			}

			// Redirect if not an AJAX request
			redirect(base_url() . 'index.php?admin/manage_doctor', 'refresh');
		}

		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']          = $this->input->post('name');
			$data['email']         = $this->input->post('email');
			$data['password']      = $this->input->post('password');
			$data['address']       = $this->input->post('address');
			$data['phone']         = $this->input->post('phone');
			$data['department_id'] = $this->input->post('department_id');
			$data['profile']       = $this->input->post('profile');
			
			$this->db->where('doctor_id', $param3);
			$this->db->update('doctor', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_doctor', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('doctor', array(
				'doctor_id' => $param2
			))->result_array(); // Corrected the syntax here
		}
		if ($param1 == 'delete') {
			$this->db->where('doctor_id', $param2);
			$this->db->delete('doctor');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_doctor', 'refresh');
		}
		$page_data['page_name']  = 'manage_doctor';
		$page_data['page_title'] = ('Manage Doctor');
		$page_data['doctors']    = $this->db->get('doctor')->result_array();
		$this->load->view('index', $page_data);
		
	}
	
	/***Manage patients**/
	function manage_patient($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
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
			
			redirect(base_url() . 'index.php?admin/manage_patient', 'refresh');
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
			
			redirect(base_url() . 'index.php?admin/manage_patient', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('patient', array(
				'patient_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('patient_id', $param2);
			$this->db->delete('patient');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_patient', 'refresh');
		}
		$page_data['page_name']  = 'manage_patient';
		$page_data['page_title'] = ('Manage Patient');
		$page_data['patients']   = $this->db->get('patient')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	/***Manage nurses**/
	function manage_nurse($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['nurse_id'] = $this->input->post('nurse_id');
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address'); // Fixed syntax error here
			$data['phone']    = $this->input->post('phone');
			$data['phc_id']   = $this->input->post('phc_id'); // Ensure phc_id is included
			$this->db->insert('nurse', $data);
			$this->email_model->account_opening_email('nurse', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
			$this->session->set_flashdata('flash_message', ('Nurse Created Successfully'));
			
			redirect(base_url() . 'index.php?admin/manage_nurse', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$data['phc_id']   = $this->input->post('phc_id'); // Ensure phc_id is included
			$this->db->where('nurse_id', $param3);
			$this->db->update('nurse', $data);
			$this->session->set_flashdata('flash_message', ('Nurse Updated Successfully'));
			
			redirect(base_url() . 'index.php?admin/manage_nurse', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('nurse', array(
				'nurse_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('nurse_id', $param2);
			$this->db->delete('nurse');
			$this->session->set_flashdata('flash_message', ('Nurse Deleted Successfully'));
			
			redirect(base_url() . 'index.php?admin/manage_nurse', 'refresh');
		}
		$page_data['page_name']  = 'manage_nurse';
		$page_data['page_title'] = ('Manage Nurse');
		$page_data['nurses']     = $this->db->get('nurse')->result_array();
		$this->load->view('index', $page_data);
		
	}
	
	/***Manage pharmacists**/
	function manage_pharmacist($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['pharmacist_id'] = $this->input->post('pharmacist_id');
			$data['name']          = $this->input->post('name');
			$data['email']         = $this->input->post('email');
			$data['password']      = $this->input->post('password');
			$data['address']       = $this->input->post('address');
			$data['phone']         = $this->input->post('phone');
			$data['phc_id']        = $this->input->post('phc_id'); // Ensure phc_id is included
			$this->db->insert('pharmacist', $data);
			$this->session->set_flashdata('flash_message', ('Pharmacist Created Successfully'));
			redirect(base_url() . 'index.php?admin/manage_pharmacist', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$data['phc_id']   = $this->input->post('phc_id'); // Ensure phc_id is included
			$this->db->where('pharmacist_id', $param3);
			$this->db->update('pharmacist', $data);
			$this->session->set_flashdata('flash_message', ('Pharmacist Updated Successfully'));
			redirect(base_url() . 'index.php?admin/manage_pharmacist', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('pharmacist', array(
				'pharmacist_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('pharmacist_id', $param2);
			$this->db->delete('pharmacist');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_pharmacist', 'refresh');
		}
		$page_data['page_name']   = 'manage_pharmacist';
		$page_data['page_title']  = ('Manage Pharmacist');
		$page_data['pharmacists'] = $this->db->get('pharmacist')->result_array();
		$this->load->view('index', $page_data);
		
	}
	
	/***Manage laboratorists**/
	function manage_laboratorist($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['laboratorist_id'] = $this->input->post('laboratorist_id');
			$data['name']            = $this->input->post('name');
			$data['email']           = $this->input->post('email');
			$data['password']        = $this->input->post('password');
			$data['address']         = $this->input->post('address');
			$data['phone']           = $this->input->post('phone');
			$data['phc_id']          = $this->input->post('phc_id'); // Ensure phc_id is included
			$this->db->insert('laboratorist', $data);
			$this->session->set_flashdata('flash_message', ('Laboratorist Created Successfully'));
			redirect(base_url() . 'index.php?admin/manage_laboratorist', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$data['phc_id']   = $this->input->post('phc_id'); // Ensure phc_id is included
			$this->db->where('laboratorist_id', $param3);
			$this->db->update('laboratorist', $data);
			$this->session->set_flashdata('flash_message', ('Laboratorist Updated Successfully'));
			redirect(base_url() . 'index.php?admin/manage_laboratorist', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('laboratorist', array(
				'laboratorist_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('laboratorist_id', $param2);
			$this->db->delete('laboratorist');
			$this->session->set_flashdata('flash_message', ('Laboratorist Deleted Successfully'));
			redirect(base_url() . 'index.php?admin/manage_laboratorist', 'refresh');
		}
		$page_data['page_name']      = 'manage_laboratorist';
		$page_data['page_title']     = ('Manage Laboratorist');
		$page_data['laboratorists']  = $this->db->get('laboratorist')->result_array();
		$this->load->view('index', $page_data);
	}
	
	/***Manage accountants**/
	function manage_accountant($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->insert('accountant', $data);
			$this->email_model->account_opening_email('accountant', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
			$this->session->set_flashdata('flash_message', ('Account Opened'));
			
			redirect(base_url() . 'index.php?admin/manage_accountant', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->where('accountant_id', $param3);
			$this->db->update('accountant', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			redirect(base_url() . 'index.php?admin/manage_accountant', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('accountant', array(
				'accountant_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('accountant_id', $param2);
			$this->db->delete('accountant');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			redirect(base_url() . 'index.php?admin/manage_accountant', 'refresh');
		}
		$page_data['page_name']   = 'manage_accountant';
		$page_data['page_title']  = ('Manage Accountant');
		$page_data['accountants'] = $this->db->get('accountant')->result_array();
		$this->load->view('index', $page_data);
	}
	
	/*******VIEW APPOINTMENT REPORT	********/
	function view_appointment($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']    = 'view_appointment';
		$page_data['page_title']   = ('View Appointment');
		$page_data['appointments'] = $this->db->get('appointment')->result_array();
		$this->load->view('index', $page_data);
	}
	
	/*******VIEW PAYMENT REPORT	********/
	function view_payment($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']  = 'view_payment';
		$page_data['page_title'] = ('View Payment');
		$page_data['payments']   = $this->db->get('payment')->result_array();
		$this->load->view('index', $page_data);
	}
	
	/*******VIEW BED STATUS	********/
	function view_bed_status($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']      = 'view_bed_status';
		$page_data['bed_allotments'] = $this->db->get('bed_allotment')->result_array();
		$page_data['beds']           = $this->db->get('bed')->result_array();
		$this->load->view('index', $page_data);
	}
	
	/*******VIEW MEDICINE********/
	function view_medicine($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']  = 'view_medicine';
		$page_data['page_title'] = ('View Medicine');
		$page_data['medicines']  = $this->db->get('medicine')->result_array();
		$this->load->view('index', $page_data);
	}
	
	/*******VIEW MEDICINE********/
	function view_report($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']   = 'view_report';
		$page_data['page_title']  = ('View ' . $param1 . ' Report');
		$page_data['report_type'] = $param1;
		$page_data['reports']     = $this->db->get_where('report', array(
			'type' => $param1
		))->result_array();
		$this->load->view('index', $page_data);
	}
	
	/***MANAGE EMAIL TEMPLATE**/
	function manage_email_template($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param2 == 'do_update') {
			$this->db->where('task', $param1);
			$this->db->update('email_template', array(
				'body' => $this->input->post('body'),
				'subject' => $this->input->post('subject')
			));
			$this->session->set_flashdata('flash_message', ('Template Updated'));
			redirect(base_url() . 'index.php?admin/manage_email_template/' . $param1, 'refresh');
		}
		$page_data['page_name']     = 'manage_email_template';
		$page_data['page_title']    = ('Manage Email Template');
		$page_data['template']      = $this->db->get_where('email_template', array(
			'task' => $param1
		))->result_array();
		$page_data['template_task'] = $param1;
		$this->load->view('index', $page_data);
	}
	
	/***MANAGE NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
	function manage_noticeboard($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param1 == 'create') {
			$data['notice_title']     = $this->input->post('notice_title');
			$data['notice']           = $this->input->post('notice');
			$data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
			$this->db->insert('noticeboard', $data);
			$this->session->set_flashdata('flash_message', ('Report Created'));
			
			redirect(base_url() . 'index.php?admin/manage_noticeboard', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['notice_title']     = $this->input->post('notice_title');
			$data['notice']           = $this->input->post('notice');
			$data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
			$this->db->where('notice_id', $param3);
			$this->db->update('noticeboard', $data);
			$this->session->set_flashdata('flash_message', ('Notice Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_noticeboard', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('noticeboard', array(
				'notice_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('notice_id', $param2);
			$this->db->delete('noticeboard');
			$this->session->set_flashdata('flash_message', ('Notice Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_noticeboard', 'refresh');
		}
		$page_data['page_name']  = 'manage_noticeboard';
		$page_data['page_title'] = ('Manage Noticeboard');
		$page_data['notices']    = $this->db->get('noticeboard')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	/*****SITE/SYSTEM SETTINGS*********/
	function system_settings($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param2 == 'do_update') {
			$this->db->where('type', $param1);
			$this->db->update('settings', array(
				'description' => $this->input->post('description')
			));
			$this->session->set_flashdata('flash_message', ('Settings Updated'));
			redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
		}
		if ($param1 == 'upload_logo') {
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
			$this->session->set_flashdata('flash_message', ('Settings Updated'));
			redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
		}
		$page_data['page_name']  = 'system_settings';
		$page_data['page_title'] = ('System Settings');
		$page_data['settings']   = $this->db->get('settings')->result_array();
		$this->load->view('index', $page_data);
	}
	
	/*****BACKUP / RESTORE / DELETE DATA PAGE**********/
	function backup_restore($operation = '', $type = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect('login', 'refresh');
		
		if ($operation == 'create') {
			$this->crud_model->create_backup($type);
		}
		if ($operation == 'restore') {
			$this->crud_model->restore_backup();
			redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
		}
		if ($operation == 'delete') {
			$this->crud_model->truncate($type);
			redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
		}
		
		$page_data['page_name']  = 'backup_restore';
		$page_data['page_title'] = ('Backup Restore');
		$this->load->view('index', $page_data);
	}
	
	/******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
	function manage_profile($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'update_profile_info') {
			$data['name']    = $this->input->post('name');
			$data['email']   = $this->input->post('email');
			$data['address'] = $this->input->post('address');
			$data['phone']   = $this->input->post('phone');
			
			$this->db->where('admin_id', $this->session->userdata('admin_id'));
			$this->db->update('admin', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
		}
		if ($param1 == 'change_password') {
			$data['password']             = $this->input->post('password');
			$data['new_password']         = $this->input->post('new_password');
			$data['confirm_new_password'] = $this->input->post('confirm_new_password');
			
			$current_password = $this->db->get_where('admin', array(
				'admin_id' => $this->session->userdata('admin_id')
			))->row()->password;
			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
				$this->db->where('admin_id', $this->session->userdata('admin_id'));
				$this->db->update('admin', array(
					'password' => $data['new_password']
				));
				$this->session->set_flashdata('flash_message', ('Password Updated'));
			} else {
				$this->session->set_flashdata('flash_message', ('Password Mismatch'));
			}
			
			redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
		}
		$page_data['page_name']    = 'manage_profile';
		$page_data['page_title']   = ('Manage Profile');
		$page_data['edit_profile'] = $this->db->get_where('admin', array(
			'admin_id' => $this->session->userdata('admin_id')
		))->result_array();
		$this->load->view('index', $page_data);
	}
	
	/***MANAGE DOCTOR ATTENDANCE***/
	function manage_attendance($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('admin_login') != 1) {
			// Redirect to login if session is invalid
			echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again.']);
			return;
		}

		if ($param1 == 'view') {
			// Suppress PHP warnings for JSON responses
			error_reporting(0);

			$doctor_id = $param2;
			$attendances = $this->db->get_where('doctor_attendance', ['doctor_id' => $doctor_id])->result_array();

			// Return JSON response
			echo json_encode(['success' => true, 'attendances' => $attendances]);
			return;
		}

		if ($param1 == 'add') {
			$data['doctor_id'] = $this->input->post('doctor_id');
			$data['attendance_date'] = $this->input->post('attendance_date');
			$data['status'] = $this->input->post('status');
			$data['duration'] = $this->input->post('duration');
			$this->db->insert('doctor_attendance', $data);
			$this->session->set_flashdata('flash_message', ('Attendance Added Successfully'));
			redirect(base_url() . 'index.php?admin/manage_attendance', 'refresh');
		}

		if ($param1 == 'delete') {
			$this->db->where('attendance_id', $param2);
			$this->db->delete('doctor_attendance');
			$this->session->set_flashdata('flash_message', ('Attendance Deleted Successfully'));
			redirect(base_url() . 'index.php?admin/manage_attendance', 'refresh');
		}

		$page_data['page_name'] = 'manage_attendance';
		$page_data['page_title'] = ('Manage Attendance');
		$page_data['doctors'] = $this->db->get('doctor')->result_array();
		$this->load->view('index', $page_data);
	}
}
