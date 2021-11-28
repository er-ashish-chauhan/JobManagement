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

	/*
		* function to edit and update user     
		* @param       userid on edit and post values on update
		* @return      null
		*/
	public function manage_job_detail()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$id = decode($this->input->get("id"));

			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Jobs';

			$this->data_array['disabled'] = TRUE;
			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Job';
			$this->data_array['pageTitle'] = 'Manage Jobs';
			$this->data_array["btn_name"] = "Add";

			$this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();
			$this->data_array["commodities"] = $this->admin_video_model->getCommodities();

			adminviews('add_job', $this->data_array);
		} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

			// condition to Add new Coach...........................................
			$request = $this->input->post();
			$request = $this->security->xss_clean($request);
			unset($request['id']);

			// $this->form_validation->set_rules("job_name", "Job Name", "required|");
			$form_data_arr = array(
				"job_name" => $request["job_name"],
				"firmId" => $request["firmId"],
				'total_quantity' => $request['total_quantity'],
				"price" => $request["qtyPrice"],
				"commodityId" => $request["commodityId"],
				"dealValidUpto" => $request["dealvalid"],
				"deliveryType" => $request["deliveryType"],
				"quantityType" => $request["qtyTpe"],
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

	public function get_assignee()
	{
		$user_result = $this->db->select("*")->from("users")->where("role", 3)->get()->result();

		$user_option = " <option value=''>--user--</option>";

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



	/*
		* function to edit and update user     
		* @param       userid on edit and post values on update
		* @return      null
		*/
	public function view_job_detail()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$id = decode($this->input->get("id"));

			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Job';

			// in case of view patient only
			if ($this->input->get('action') == 'view') {
				$this->data_array['disabled'] = TRUE;
				$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Job';
				$this->data_array['pageTitle'] = 'Manage Video';
				$this->data_array["btn_name"] = "Add";

				$this->db->select("j.id,  j.job_name, j.total_quantity, jm.quantityConfirmed, jm.image, jm.created");
				// $this->db->select("CONCAT_WS(' ',u.firstName, u.lastName) fullname, j.id,  j.job_name, f.firm_name, j.assignToId, jm.quantityConfirmed, jm.image");
				$this->db->from("jobMeta jm");
				// $this->db->join("firm f", "j.firmId = f.id", "left");
				$this->db->join("job j", "j.id = jm.jobId", "left");
				$this->db->where("jm.jobId", $id);

				$query = $this->db->get();
				$this->data_array['data'] = $query->result();

				adminviews('view_job', $this->data_array);
			}
		}
	}
}
