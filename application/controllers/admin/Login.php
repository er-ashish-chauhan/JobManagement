<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{


	function index()
	{

		$pageData = array();
		$pageData['pageTitle'] = "Admin Login";

		if(isset($this->session->userdata()['is_admin']) && !empty($this->session->userdata()['is_admin']['id']) )
		{
			$session_id = $this->session->userdata()['is_admin']['id'];
		}
		$cookie_id = get_cookie('id');

		if (!empty($cookie_id)) {
			get_admin_session(decode($cookie_id));
			$get_redirect_url = $this->input->get('redirect_url');
			if (empty($get_redirect_url)) {
				redirect('admin/user');
			} else {
				redirect($get_redirect_url);
			}
		}

		if (!empty($session_id)) {
			redirect('admin/user');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->load->view('admin/login', $pageData);
		} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
			// pr($this->input->post()); die;
			// die('post');

			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() == FALSE) {
				// die("1");
				// $this->session->set_flashdata('error',validation_errors());
				// redirect('company/login');
				$this->load->view('admin/login', $pageData);
			} else {
				$postData = $this->security->xss_clean($this->input->post());
				extract($postData);
				$where = array();
				$where['email'] = $email;
				$where['role'] = 1;
				$admin_data = $this->db->get_where('users', $where)->row();

				if (!empty($admin_data)) {
					$verify = password_verify($password, $admin_data->password);
					if ($verify === true) {
						$session_array['is_admin'] = array(
							'id' => $admin_data->id,
							'email' => $admin_data->email,
							'name' => $admin_data->firstName . " " . $admin_data->lastName,
							'password' => $admin_data->password,
							'role' => 1
						);
						$this->session->set_userdata($session_array);
						if (isset($remember_me) && $remember_me == 1) {
							$this->input->set_cookie('id', encode($admin_data->id), 86500 * 90);
						} else {
							delete_cookie('id');
						}
						if (empty($redirect_url)) {
							redirect('admin/user');
						} else {
							redirect($redirect_url);
						}
					} else {
						$this->session->set_flashdata('error', lang('INVALID_PASSWORD'));
						redirect('admin/login');
					}
				} else {
					$this->session->set_flashdata('error', lang('INVALID_EMAIL'));
					redirect_back();
				}
			}
		}
	}

	// //function for logout
	// public function logout()
	// {
	// 	$this->session->unset_userdata('adid');
	// 	$this->session->sess_destroy();
	// 	return redirect('admin/login');
	// }
}
