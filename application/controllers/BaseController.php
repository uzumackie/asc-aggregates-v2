<?php
defined('BASEPATH') or exit('No direct script access allowed');
class BaseController extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('session');

		$this->load->helper(array('form', 'url'));
		$this->load->model('App_model');
		$this->load->model('masterfileModel/User_model');
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->dbutil();
		$this->load->helper('download');
		date_default_timezone_set('Asia/Manila');

		//Disable Cache
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}

	#region login
	public function login()
	{
		$postContent = file_get_contents("php://input");
		$request = json_decode($postContent);

		$username = $request->username;
		$password = $request->password;

		$user = $this->App_model->getUsers($username);

		if (!empty($user)) {
			if ($user->status == '0') {
				$message = ['info' => 'Error', 'message' => 'Account Deactivated.'];
			} else {
				if ($username == 'admin') {
					// For 'admin', allow either plain text or hashed password match
					if ($password === $user->password || password_verify($password, $user->password)) {
						// Password matches either plain or hashed
						$this->_login_success($user);
						$message = ['info' => 'Granted', 'message' => 'Access Granted.'];
					} else {
						// Password doesn't match
						$message = ['info' => 'Error', 'message' => 'Access Denied! Wrong password for admin.'];
					}
				} else {
					// For all other users, only verify the hashed password
					if (password_verify($password, $user->password)) {
						$this->_login_success($user);
						$message = ['info' => 'Granted', 'message' => 'Access Granted.'];
					} else {
						$message = ['info' => 'Error', 'message' => 'Access Denied! Wrong password.'];
					}
				}
			}
		} else {
			$message = ['info' => 'Error', 'message' => 'Account Does Not Exist.'];
		}

		JSONResponse($message);
	}

	private function _login_success($user)
	{
		$user_ip = $this->input->ip_address();

		$this->session->set_userdata([
			'user_id' => $user->user_id,
			'emp_id' => $user->emp_id,
			'name' => $user->name,
			'emp_position' => $user->emp_position,
			'emp_bunit' => $user->emp_bunit,
			'emp_dept' => $user->emp_dept,
			'user_type' => $user->user_type,
			'username' => $user->username,
			'ip_address' => $user->ip_address,
			'is_logged_in' => TRUE,
			'ac_verify' => TRUE,
		]);


		$userdata = [
			'user_logged_in' => 1,
			'ip_address' => $user_ip,
			'login_timestamp' => date("Y-m-d H:i:s")
		];
		$this->db->where('user_id', $user->user_id)
			->update('users', $userdata);  // Update users table with the IP
	}

	#region index
	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {
			redirect('main');
			return;
		}

		$this->load->view('login/login');
	}
	#endregion

	#region main
	public function main()
	{
		if (!$this->session->userdata('is_logged_in')) {
			redirect(base_url());
			return;
		}

		$data['title'] = 'Dashboard';

		// Load the main layout only once.
		$this->load->view('template/main_view', $data);
	}
	#endregion

	#region dashboard
	public function dashboard()
	{
		if (!$this->session->userdata('is_logged_in')) {
			redirect(base_url());
			return;
		}

		$data['title'] = 'Dashboard';

		// This is the dashboard content, not the entire layout.
		$this->load->view('page/dashboard', $data);
	}
	#endregion

	#region masterfile
	public function masterfile($page)
	{
		if (!file_exists(APPPATH . 'views/page/masterfiles/' . $page . '.php')) {
			show_404();
		}

		$this->load->view('page/masterfiles/' . $page);
	}

	#region logout
	public function logout()
	{
		$userdata = ['user_logged_in' => 0];
		$this->db->where('user_id', $this->session->userdata('user_id'))
			->update('users', $userdata);

		$session_data = ['user_id', 'username', 'position', 'name', 'user_type', 'business_unit', 'ip_address', 'is_logged_in', 'loc_code', 'ac_verify'];
		$this->session->unset_userdata($session_data);
		$this->session->set_flashdata('logout_success', 'Successfully logged out');
		redirect(base_url());
	}

	#region session_expire
	public function session_expire()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');

		// If still logged in, redirect to dashboard
		if ($is_logged_in) {
			redirect(base_url() . 'main#!/dashboard');
			return; // Important to prevent further execution
		}

		// Otherwise, destroy session and show expire page
		$this->session->sess_destroy();

		if (!$this->input->is_ajax_request()) {
			$this->load->view('errors/cli/error_401'); // Your session expired view
		} else {
			$this->output->set_status_header(401);
		}
	}
}
