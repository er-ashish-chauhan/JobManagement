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
	    $this->data_array['btn_name'] = 'Add';
		adminviews('commodity_listing', $this->data_array);
	}

	public function get_commodity_data()
	{
		try {
			$postData = $this->input->post();
			$data = $this->commodity_model->commodity_list($postData);
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', 'Error while getting commodity data details: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	/*
		* function to edit and update user     
		* @param       userid on edit and post values on update
		* @return      null
		*/
	public function submitCommodity()
	{
		// condition to Add new Commodity...........................................
		$request = $this->input->post();
		$request = $this->security->xss_clean($request);
		// unset($request['id']);
	
		if(!empty($request['id']))
		{
			// echo "<pre>";
			// pr($request);
			// die;
			$id = $request['id'];

			$update_data = array(
				'commodity' => $request['commodity']
			);

			$this->db->where('id', $id);
			$update_result = $this->db->update('commodities', $update_data);

			if ($update_result) {
				$this->session->set_flashdata("success", 'Commodity updated successfully');
			} else {
				$this->session->set_flashdata("error", 'Error while updating commodity');
			}

			redirect('admin/Commodity');
		}
		else if ($request["add_commodity"]) {
			$form_data_arr = array(
				"commodity" => $request["commodity"]
			);

			$response_data =  $this->db->insert("commodities", $form_data_arr);

			if ($response_data) {
				$this->session->set_flashdata("success", 'Commodity added successfully');
			} else {
				$this->session->set_flashdata("error", 'Error while adding commodity');
			}
		} else {
			$this->session->set_flashdata("error", 'Error while adding commodity');
		}
		redirect('admin/Commodity');
	}

	public function edit_commodity_detail()
	{
		$id = decode($this->input->get('id'));

	  $comm_result	= $this->db->select("*")->from('commodities')->where("id", $id)->get()->row();

	    $this->data_array['name'] = 'Commodity';
	    $this->data_array['data'] = $comm_result;
	    $this->data_array['btn_name'] = 'Update';
		adminviews('commodity_listing', $this->data_array);
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
			log_message('error', 'Error while deleting to commodity: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}
}
