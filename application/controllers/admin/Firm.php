<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Firm extends CI_Controller
{
	private $pageData = array();
	function __construct()
	{
		parent::__construct();
		adminAuth();
		$this->load->model('admin_firm_model');
	}

	public function index()
	{
		$this->pageData['name'] = 'Manage Firm';
		adminviews('firm_listing', $this->pageData);
	}

	public function get_coach_data()
	{
		try {
			$postData = $this->input->post();
			$data = $this->admin_firm_model->list_coach($postData);
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', 'Error while getting coach details: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
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
				$this->session->set_flashdata("success", lang("COACH_DELETE_SUCCESS"));
			} else {
				$this->session->set_flashdata("error", lang("COACH_DELETE_SUCCESS"));
			}
			echo json_decode($result);
		} catch (Exception $e) {
			log_message('error', 'Error while deleting to coach: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	/*
		* function to edit and update user     
		* @param       userid on edit and post values on update
		* @return      null
		*/
	public function manageParty()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$id = decode($this->input->get("id"));

			// echo $id;
			// die;

			// in case of view patient only
			if ($this->input->get('action') == 'add') {
				$this->data_array['disabled'] = TRUE;
				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Party';
				$this->data_array['pageTitle'] = 'Add Party';
				$this->data_array["btn_name"] = "Add";
				return adminviews('manage_firm', $this->data_array);
			} else if ($this->input->get('action') == 'edit') {

				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Update Party';
				$this->data_array['pageTitle'] = 'Update Party';
				$this->data_array["btn_name"] = "Update";
				$this->data_array['data'] = $this->admin_firm_model->get_firm_to_edit($id);
				return adminviews('manage_firm', $this->data_array);
			}

		} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
			$request = $this->input->post();
			$request = $this->security->xss_clean($request);

			if (!empty($request['id'])) {
				$form_data_arr = array(
					"firm_name" => trim($request["firm_name"], " "),
					"address" => $request["address"],
					"contactNumber" => $request["contact"]
				);

				$this->db->where('id', $request['id']);
				$response_data = $this->db->update("firm", $form_data_arr);

				if ($response_data) {
					$this->session->set_flashdata("success", 'Party updated successfully');
				} else {
					$this->session->set_flashdata("error", 'Error while updating party');
				}
				redirect('admin/firm');
			} else {
				$form_data_arr = array(
					"firm_name" => trim($request["firm_name"], " "),
					"address" => $request["address"],
					"contactNumber" => $request["contact"]
				);

				$response_data = $this->db->insert("firm", $form_data_arr);

				if ($response_data) {
					$this->session->set_flashdata("success", 'Party added successfully');
				} else {
					$this->session->set_flashdata("error", 'Error while Adding party');
				}
				redirect('admin/firm');
			}
		}
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
