<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include_once('pathto/getid3.php');
include_once APPPATH . 'third_party/Getid3/getid3/getid3.php';
class Job extends CI_Controller
{
	private $pageData = array();
	private $data_array = array();

	function __construct()
	{
		parent::__construct();
		adminAuth();
		$this->load->model('admin_video_model');
	}

	public function index()
	{
		$this->data_array['name'] = 'raghav';
		adminviews('video_listing', $this->data_array);
	}

	public function get_video_data()
	{
		try {
			$postData = $this->input->post();
			$data = $this->admin_video_model->list_video($postData);
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', 'Error while getting coach video details: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}



	// function to soft delete user 
	function delete_video()
	{
		$id = decode($this->input->post("id"));

		try {
			$update_data = array("isDeleted" => 1);

			$this->db->where("id", $id);
			$result = $this->db->update("videos", $update_data);
			if ($result) {
				$this->session->set_flashdata("success", lang("VIDEO_DELETE_SUCCESS"));
			} else {
				$this->session->set_flashdata("error", lang("VIDEO_DELETE_SUCCESS"));
			}
			echo json_decode($result);
		} catch (Exception $e) {
			log_message('error', 'Error while deleting to coach video: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	/*
		* function to edit and update user     
		* @param       userid on edit and post values on update
		* @return      null
		*/
	public function manage_job_detail()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$id = decode($this->input->get("id"));

			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Edit Patient';
			// $this->data_array['pageTitle'] = 'Edit Patient';
			// $this->data_array['disabled'] = FALSE;
			// $this->data_array["btn_name"] = "Update";

			// in case of view patient only
			if ($this->input->get('action') == 'add') {
				$this->data_array['disabled'] = TRUE;
				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Patient';
				$this->data_array['pageTitle'] = 'Manage Video';
				$this->data_array["btn_name"] = "Add";

				$this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();


				adminviews('manage_video', $this->data_array);
			} else {
				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Patient';
				$this->data_array['pageTitle'] = 'Update Caoch';
				$this->data_array["btn_name"] = "Update";
				$this->data_array['category_list'] = $this->db->select("category, id")->from('videoCategory')->where(array('isActive' => 1, 'isDeleted' => 0))->get()->result();

				$this->data_array['coach_list'] = $this->db->select("CONCAT_WS(' ',firstName, lastName) as coachName, id")->from('users')->where(array('role' => 2, 'isActive' => 1, 'isDeleted' => 0))->get()->result();
				$this->data_array['data'] = $this->admin_video_model->get_video_to_edit($id);
				adminviews('manage_video', $this->data_array);
			}



			// $this->adminviews('edit_user_profile', $this->data_array);
		} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

			// condition to Add new Coach...........................................
			$request = $this->input->post();
			$request = $this->security->xss_clean($request);
			unset($request['id']);

			$form_data_arr = array(
				"job_name" => $request["job_name"],
				"firmId" => $request["firmId"],
				'total_quantity' => $request['total_quantity'],
			);

			$response_data =  $this->db->insert("job", $form_data_arr);

			if ($response_data) {
				$this->session->set_flashdata("success", 'Job added successfully');
			} else {
				$this->session->set_flashdata("error", 'Error while adding job');
			}
			redirect('admin/job');
		}
	}

	// function to upload banner called from another function.
	private function upload_files()
	{
		$config['upload_path']          = BANNER_IMAGE_PATH;
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']        = 5000;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('videoBanner')) {
			$error['msg'] = $this->upload->display_errors();
			$error['er_no'] = 0;
			log_message('error', 'Error while uploading profile image of coach: Error-message' . $error['msg'] . ' FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
			$this->session->set_flashdata("error", $error['msg']);
			redirect_back();
		}
		return $this->upload->data();
	}

	// function to upload video called from another function.
	private function upload_video()
	{
		$config['upload_path']          = COACH_VIDEO_PATH;
		$config['allowed_types']        = '*';
		$config['max_size']        = 800000;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('videoURL')) {
			$error['msg'] = $this->upload->display_errors();
			$error['er_no'] = 0;
			log_message('error', 'Error while uploading profile image of coach: Error-message' . $error['msg'] . ' FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
			$this->session->set_flashdata("error", $error['msg']);
			redirect_back();
		}
		return $this->upload->data();
	}


	public function get_assignee()
	{
		$user_result = $this->db->select("*")->from("users")->where("role", 3)->get()->result();

		$user_option = " <option value=''>--firm--</option>";

		if (!empty($user_result)) {
			foreach ($user_result as $list) {
				$name = $list->firstName . " " . $list->lastName;
				$user_option .= "<option value='$list->id' >$name</option>";
			}
		}

		echo $user_option;
	}

	public function assign_job()
	{

		// 		Array
		// (
		//     [id] => 5
		//     [firmId] => 17
		// )
		// pr($this->input->post()); die;

		$request = $this->input->post();

		$data_arr = array(
			"assignToId" => $request['assignToId']
		);

		            $this->db->where("id", $request['id']);
		$res_is = 	$this->db->update("job", $data_arr);

		if ($res_is) {
			$this->session->set_flashdata("success", 'Job assigned successfully');
		} else {
			$this->session->set_flashdata("error", 'Error while assigning job');
		}
		redirect_back();
	}
}
