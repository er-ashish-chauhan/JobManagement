<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
	private $pageData = array();
	function __construct()
	{
		parent::__construct();
		adminAuth();
		$this->load->model('admin_user_model');
	}

	public function index()
	{
		$this->pageData['name'] = 'raghav';
		adminviews('user_listing', $this->pageData);
	}

	public function get_user_data()
	{
		try {
			$postData = $this->input->post();
			$data = $this->admin_user_model->list_user($postData);
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', 'Error while getting coach details: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	// function to change user status for listing
	function change_status()
	{
		$iUserId = $this->input->post("id");
		$eStatus = $this->input->post("status");
		$data1 = array("isActive" => $eStatus);
		try {
			$status_val = "";
			$this->db->where("id", $iUserId);
			$response_data = $this->db->update("users", $data1);
			if ($response_data) {
				if ($eStatus == "1") {
					$this->session->set_flashData("success", lang("USER_ACTIVATION_SUCCESS"));
				} elseif ($eStatus == "0") {
					$this->session->set_flashData("success", lang("USER_DEACTIVATION_SUCCESS"));
				}
			} else {
				$this->session->set_flashData("error", lang("USER_ACTIVATION_FAILED"));
			}
			echo json_encode($response_data);
		} catch (Exception $e) {
			log_message('error', 'Error while changing status of coach: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	// function to soft delete user 
	function delete_user()
	{
		$id = decode($this->input->post("id"));

		try {
			$update_data = array("isDeleted" => 1);

			$this->db->where("id", $id);
			$result = $this->db->update("users", $update_data);
			if ($result) {
				$this->session->set_flashdata("success", lang("USER_DELETE_SUCCESS"));
			} else {
				$this->session->set_flashdata("error", lang("USER_DELETE_SUCCESS"));
			}
			echo json_decode($result);
		} catch (Exception $e) {
			log_message('error', 'Error while deleting to user: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	/*
		* function to edit and update user     
		* @param       userid on edit and post values on update
		* @return      null
		*/
	public function manage_user_detail()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$id = decode($this->input->get("id"));

			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Edit Patient';
			$this->data_array['pageTitle'] = 'Edit Patient';
			$this->data_array['disabled'] = FALSE;
			$this->data_array["btn_name"] = "Update";

			// in case of view patient only
			if ($this->input->get('action') == 'add') {
				$this->data_array['disabled'] = TRUE;
				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Patient';
				$this->data_array['pageTitle'] = 'Add Coach';
				$this->data_array["btn_name"] = "View";
				adminviews('manage_coach', $this->pageData);
			}

			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Patient';
			$this->data_array['pageTitle'] = 'Update Caoch';
			$this->data_array["btn_name"] = "Update";
			$this->data_array['data'] = $this->admin_user_model->get_user_to_edit($id);
			adminviews('manage_user', $this->data_array);

			// $this->adminviews('edit_user_profile', $this->data_array);
		} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
			//code to update edited doctor data..........................................
			if (!empty($this->input->post('id'))) {
				$request = $this->input->post();
				$request = $this->security->xss_clean($request);
				$id = $request["id"];

				// pr($request,1);
				$form_data_arr = array(
					"firstName" => trim($request["firstName"], " "),
					"lastName" => trim($request["lastName"], " "),
					"email" => $request["email"],
					"coFirm" => $request["coParty"],
					"role" => 3,
					"updated" => date("Y-m-d H:i:s"),
				);

				if (!empty($request["password"])) {
					$form_data_arr['password'] = password_hash($request["password"], PASSWORD_DEFAULT);
				}

				if ($_FILES['profileImage']['name']) {
					$type = explode("/", $_FILES['profileImage']['type']);
					$_FILES['profileImage']['name'] = "profileImage" . time() . "." . $type[1];
					$image_uploded = $this->upload_files();

					if (isset($image_uploded['file_name']) && !empty($image_uploded['file_name'])) {
						$form_data_arr['profileImage'] = $image_uploded['file_name'];
					}
				}

				$this->db->trans_start();

				// pr($form_data_arr,1);

				$this->db->where("id", $id);
				$this->db->update("users", $form_data_arr);

				$form_data2 = array(
					"gender" => $request["gender"],
					"userId" => $id
				);

				$this->db->where("userId", $id);
				$this->db->update("userMeta", $form_data2);

				$this->db->trans_complete();

				if ($this->db->trans_status() == FALSE) {
					$this->db->trans_rollback();
					$this->session->set_flashdata("error", lang("USER_UPDATE_FAILED"));
					// echo "raghav"; die;
					redirect('admin/user');
				} else {
					$this->db->trans_commit();
					$this->session->set_flashdata("success", lang("USER_UPDATE_SUCCESS"));
					// echo "raghav2"; die;
					redirect('admin/user');
				}
			} else {
				// condition to Add new User...........................................
				$request = $this->input->post();
				$request = $this->security->xss_clean($request);
				unset($request['id']);

				$form_data_arr = array(
					"firstName" => trim($request["firstName"], " "),
					"lastName" => trim($request["lastName"], " "),
					"email" => $request["email"],
					"coFirm" => $request["coParty"],
					"role" => 3
				);

				if (!empty($request["password"])) {
					$form_data_arr['password'] = password_hash($request["password"], PASSWORD_DEFAULT);
				}

				// $this->doctor_model->create_doctor($form_data_arr);
				$response_data =  $this->db->insert("users", $form_data_arr);

				if ($response_data) {
					$this->session->set_flashdata("success", 'User added successfully');
				} else {
					$this->session->set_flashdata("error", 'Error in adding user');
				}
				redirect('admin/user');
			}
		}
	}

	// function to upload image called from another function.
	private function upload_files()
	{
		$config['upload_path']          = USER_IMAGE_PATH;
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']        = 10000;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('profileImage')) {
			$error['msg'] = $this->upload->display_errors();
			$error['er_no'] = 0;
			log_message('error', 'Error while uploading profile image of coach: Error-message' . $error['msg'] . ' FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
			$this->session->set_flashdata("error", $error['msg']);
			redirect_back();
		}
		return $this->upload->data();
	}


	/**
	 * Function to check unique email (jquery validation)
	 * @return      true or false
	 * 
	 */
	function unique_email()
	{
		$vEmail = $this->input->get("email");
		$id = $this->input->get("id");
		$user_session_id = $this->session->userdata("user_session_id");
		$this->db->select("*");
		if (!empty($id)) {
			$this->db->where("id != ", $id);
		}
		$this->db->where("email", $vEmail);
		// $this->db->where("bDeleted", 0);
		$query = $this->db->get("users");
		if ($query->num_rows() > 0) {
			echo "false";
		} else {
			echo "true";
		}
	}
}
