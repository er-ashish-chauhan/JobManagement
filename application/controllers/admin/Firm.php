<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Firm extends CI_Controller
{
   private $pageData = array();
    function __construct()
    {
        parent::__construct();
        adminAuth();
        $this->load->model('admin_coach_model');
        
    }

    public function index()
    {
        $this->pageData['name'] = 'raghav';
        adminviews('coach_listing', $this->pageData);
    }

    public function get_coach_data()
    {
        try {
			$postData = $this->input->post();
			$data = $this->admin_coach_model->list_coach($postData);
			echo json_encode($data);
		} catch (Exception $e) {
            log_message('error', 'Error while getting coach details: FILE-'.__FILE__.'CLASS: '.__CLASS__.'FUNCTION: '.__FUNCTION__);
		}
    }

	// function to soft delete user 
	function delete_user()
	{
		$id = decode($this->input->post("id"));

		try {
				$update_data= array("isDeleted" => 1);

                         $this->db->where("id", $id);
               $result = $this->db->update("users",$update_data);
				if ($result) {
					$this->session->set_flashdata("success", lang("COACH_DELETE_SUCCESS"));
				} else {
					$this->session->set_flashdata("error", lang("COACH_DELETE_SUCCESS"));
				}
			echo json_decode($result);
		} catch (Exception $e) {
			log_message('error', 'Error while deleting to coach: FILE-'.__FILE__.'CLASS: '.__CLASS__.'FUNCTION: '.__FUNCTION__);
		}
	}

	/*
		* function to edit and update user     
		* @param       userid on edit and post values on update
		* @return      null
		*/
	public function manage_coach_detail()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$id = decode($this->input->get("id"));

			// in case of view patient only
			if ($this->input->get('action') == 'add') {
				$this->data_array['disabled'] = TRUE;
				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Coach';
				$this->data_array['pageTitle'] = 'Add Coach';
				$this->data_array["btn_name"] = "Add";
               return adminviews('manage_coach', $this->data_array);
			}
			else{

				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Update Coach';
				$this->data_array['pageTitle'] = 'Update Caoch';
				$this->data_array["btn_name"] = "Update";
				$this->data_array['data'] = $this->admin_coach_model->get_coach_to_edit($id);
			  return adminviews('manage_coach', $this->data_array);
			}
		 
		

			// $this->adminviews('edit_user_profile', $this->data_array);
		}
	    else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
				// condition to Add new Coach...........................................
				$request = $this->input->post();
				$request = $this->security->xss_clean($request);
				unset($request['id']);

				$form_data_arr = array(
					"firm_name" => trim($request["firm_name"], " "),
					"address" => $request["address"],	
				);
				
				// $this->doctor_model->create_doctor($form_data_arr);
				$response_data = $this->db->insert("firm", $form_data_arr);
				
				if ($response_data) {
					$this->session->set_flashdata("success", 'Firm added successfully');
				} else {
					$this->session->set_flashdata("error", 'Error while Adding firm');
				}
				redirect('admin/firm');
			
		}
	}

	// function to upload image called from another function.
	private function upload_files()
	{
		$config['upload_path']          = COACH_IMAGE_PATH;
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']        = 10000;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('profileImage')) {
			$error['msg'] = $this->upload->display_errors();
			$error['er_no'] = 0;
			log_message('error', 'Error while uploading profile image of coach: Error-message'. $error['msg'].' FILE-'.__FILE__.'CLASS: '.__CLASS__.'FUNCTION: '.__FUNCTION__);
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
