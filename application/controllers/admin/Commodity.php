
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Commodity extends CI_Controller
{
	private $pageData = array();
	private $data_array = array();

	function __construct()
	{
		parent::__construct();
		adminAuth();
		$this->load->model('commodity_model');
	}

	public function index()
	{
		$this->data_array['name'] = 'Commodity';
		adminviews('commodity_listing', $this->data_array);
	}

	public function get_commodity_data()
	{
		try {
			$postData = $this->input->post();
			$data = $this->commodity_model->commodity_list($postData);
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
				"brokerName" => $request["broker_name"]
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



	// function to soft delete user 
	function delete_commodity()
	{
		$id = $this->input->post("id");

		try {
                         $this->db->where("id", $id);
               $result = $this->db->delete("commodities");
				if ($result) {
					$this->session->set_flashdata("success", 'commodity deleted successfully');
				} else {
					$this->session->set_flashdata("error", 'Error while deleting coach');
				}
			echo json_decode($result);
		} catch (Exception $e) {
			log_message('error', 'Error while deleting to commodity: FILE-'.__FILE__.'CLASS: '.__CLASS__.'FUNCTION: '.__FUNCTION__);
		}
	}
}
