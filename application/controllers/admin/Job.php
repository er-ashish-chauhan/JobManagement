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
		$this->load->model('admin_job_model');
	}

	public function index()
	{
		$this->data_array['name'] = 'raghav';
		adminviews('job_listing', $this->data_array);
	}

	public function get_video_data()
	{
		try {
			$postData = $this->input->post();
			$data = $this->admin_job_model->jobsList($postData);
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
			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Bargain';
			$this->data_array['pageTitle'] = 'Manage Bargain\'s';
			$this->data_array["btn_name"] = "Add";

			$this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();
			$this->data_array["commodities"] = $this->admin_job_model->getCommodities();

			adminviews('add_job', $this->data_array);
		} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

			$request = $this->input->post();
			$request = $this->security->xss_clean($request);
			unset($request['id']);

			$checkIfJobExist = $this->admin_job_model->getLastBargain();

			$job_PO_number = str_pad(1, 5, "0", STR_PAD_LEFT);

			if ($checkIfJobExist) {
				// $this->session->set_flashdata("error", 'Job already exist in our record with the same firm and commodity.');
				// redirect('admin/addBargain');
				$job_PO_number = str_pad($checkIfJobExist->purchaseOrder + 1, 5, 0, STR_PAD_LEFT);
			}

			$form_data_arr = array(
				"purchaseOrder" => $job_PO_number,
				"firmId" => $request["firmId"],
				'total_quantity' => $request['total_quantity'],
				"price" => $request["qtyPrice"],
				"commodityId" => $request["commodityId"],
				"dealValidUpto" => $request["dealvalid"],
				"dealValidFrom" => $request["dealvalidFrom"],
				"deliveryType" => $request["deliveryType"],
				"quantityType" => $request["qtyTpe"],
				"brokerName" => $request["broker_name"],
				"remaining_quantity" => $request['total_quantity']
			);

			$response_data =  $this->db->insert("job", $form_data_arr);

			if ($response_data) {
				$this->session->set_flashdata("success", 'Bargain added successfully');
			} else {
				$this->session->set_flashdata("error", 'Error while adding Bargain');
			}
			redirect('admin/bargainsListing');
		}
	}

	public function getJobsList()
	{
		# code...
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
	public function view_job_detail($jobId)
	{
		$this->data_array['disabled'] = TRUE;
		$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Job Details';
		$this->data_array['pageTitle'] = 'Manage Job Entries';
		$this->data_array["jobId"] = $jobId;

		$this->data_array["jobDetails"] = $this->admin_job_model->checkIfJobExist(["id" => decode($jobId)]);

		adminviews('view_job', $this->data_array);
	}

	public function getJobEntries($jobId)
	{
		// $id = decode($jobId);
		try {
			$postData = $this->input->post();
			$data = $this->admin_job_model->getJobEntries($postData, $jobId);
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', 'Error while getting entries details: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	public function editBargain($jobId)
	{
		$this->data_array['disabled'] = TRUE;
		$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Edit Bargain';
		$this->data_array['pageTitle'] = 'Edit Bargain';
		$this->data_array["jobId"] = $jobId;
		$this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();
		$this->data_array["commodities"] = $this->admin_job_model->getCommodities();
		$this->data_array["bargain"] = $this->admin_job_model->checkIfJobExist(["id" => decode($jobId)]);


		if ($this->input->post()) {
			$dateTime = new DateTime();
			$form_data_arr = array(
				'total_quantity' => $this->input->post('total_quantity'),
				"remaining_quantity" => $this->input->post('total_quantity'),
				"dealValidUpto" => $this->input->post("dealvalid"),
				"dealValidFrom" => $this->input->post("dealvalidFrom"),
				"updated" => $dateTime->getTimestamp()
			);

			$response_data =  $this->admin_job_model->updateBargain($form_data_arr, ["id" => decode($jobId)]);

			if ($response_data) {
				$this->session->set_flashdata("success", 'Bargain updated successfully');
			} else {
				$this->session->set_flashdata("error", 'Error while update bargain');
			}
			redirect('admin/bargainsListing');
		}
		adminviews('editBargain', $this->data_array);
	}

	public function completeBargain($id)
	{
		$response_data =  $this->admin_job_model->updateBargain(["status" => "completed"], ["id" => decode($id)]);

		if ($response_data) {
			$this->session->set_flashdata("success", 'Bargain updated successfully');
		} else {
			$this->session->set_flashdata("error", 'Error while update bargain');
		}
		redirect('admin/bargainsListing');
	}
}
