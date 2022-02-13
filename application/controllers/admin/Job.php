<?php

use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');
// include_once('pathto/getid3.php');
include_once APPPATH . 'third_party/Getid3/getid3/getid3.php';
include_once APPPATH . 'third_party/mpdf/mpdf/mpdf.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// include_once FCPATH . 'vendor/autoload.php';

class Job extends CI_Controller
{
	private $pageData = array();
	private $data_array = array();

	function __construct()
	{
		parent::__construct();
		ob_clean();
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
			$this->data_array["brokers"] = $this->admin_job_model->getBrokersList();

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
			// echo "<pre>";
			// print_r($postData); die;
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
		$this->data_array["bargain"] = $this->admin_job_model->checkIfJobExist(["job.id" => decode($jobId)]);


		if ($this->input->post()) {

			$entriesSum =  $this->admin_job_model->getJobEntriesDetails(["jobId" => decode($jobId), "status" => 2]);
			// echo"<prev>";
			// print_r($entriesSum);
			// die();

			$dateTime = new DateTime();

			// stdClass Object ( [noOfBags] => 0 [cNetWeight] => 454.23 )
			$remaingQty = $this->input->post('total_quantity');
			if ($this->input->post('qtyTpe') == "qts") {
				$remaingQty = $this->input->post('total_quantity') - $entriesSum->cNetWeight;
			} elseif ($this->input->post('qtyTpe') == "bags") {
				$remaingQty = $this->input->post('total_quantity') - $entriesSum->noOfBags;
			} else {
				$remaingQty = $this->input->post('total_quantity') - $entriesSum->count;
			}

			$form_data_arr = array(
				'total_quantity' => $this->input->post('total_quantity'),
				'quantityType' => $this->input->post('qtyTpe'),
				"remaining_quantity" => $remaingQty,
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

	public function export_jobs()
	{
		// if ($this->input->post('csv')) {
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";

		/* file name */
		$filename = 'Bargains_' . date('Ymd') . '.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; ");

		$query = "SELECT `job`.`purchaseOrder` as PurchaseOrder, `firm`.`firm_name` as Firm,
		`commodities`.`commodity` as Commodity,`job`.`brokerName` as BrokerName, 
		`job`.`total_quantity` as TotalQuantity, `job`.`remaining_quantity` as RemainingQuantity,
		`job`.`quantityType` as QuantityType, `job`.`dealValidUpto` as DealValidUpto,
		`job`.`deliveryType` as DeliveryType, `job`.`status` as BargainStatus
		from `job` LEFT JOIN firm ON firm.id = job.firmId
		LEFT JOIN commodities ON commodities.id = job.commodityId";
		$result = $this->db->query($query);
		$query_result = $result->result();
		/* file creation */
		$file = fopen('php:/* output', 'w');
		$header = array("Purchase Order", "Party Name", "Commodity", "Broker", "Total Qty", "Remaining Qty", "Qty type", "Deal valid upto", "Delivery type", "Status");
		fputcsv($file, $header, $delimiter, $newline);
		foreach ($query_result as $key => $line) {
			fputcsv($file, $line);
		}
		fclose($file);
		force_download($filename);
		// $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
		// force_download($filename, $data);
		// }
	}

	public function exportApprovedEntries($jobId)
	{
		$job_id = decode($jobId);
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "Entries.csv";
		$query = "SELECT CONCAT(DATE_FORMAT(`job`.`created`, '%d-%m-%Y'),', ',`job`.`remaining_quantity`,' ', `job`.`quantityType`,', ', `job`.`price`,', ',`commodities`.`commodity`,', ',`brokers`.`brokerName`) as BargainDetaiils,
		`jobMeta`.`recordCreated` as EntryDate,
		`jobMeta`.`truckNo` as TruckNo,
		`jobMeta`.`cNetWeight` as Quantity_in_qts,
		`jobMeta`.`noOfBags` as Quantity_in_bags,
		IF(`job`.`quantityType` = 'trucks', '1', '-') as Quantity_in_trucks,
		`firm`.`firm_name` as FirmName
		from `jobMeta` LEFT JOIN firm ON firm.id = jobMeta.firmId
		LEFT JOIN commodities ON commodities.id = jobMeta.commodityId
		LEFT JOIN job ON job.id = jobMeta.jobId
		WHERE `jobMeta`.`jobId` = $job_id AND `jobMeta`.`status` = 2";
		$result = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
		force_download($filename, $data);
	}

	public function bargainListFilters($type)
	{
		$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Apply Filters';
		$this->data_array['pageTitle'] = 'Apply Filters';

		$this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();
		$this->data_array["commodities"] = $this->admin_job_model->getCommodities();
		$this->data_array["brokers"] = $this->admin_job_model->getBrokersList();
		$this->data_array["type"] = $type;
		// echo "<pre>";
		// print_r($this->data_array["brokers"]); die;
		$this->data_array["purchaseOrders"] = $this->admin_job_model->getPurchaseOrders();

		adminviews('pdfFilters', $this->data_array);
	}

	public function exportAllEntries()
	{
		if ($this->input->post()) {
			$firmId = $this->input->post("bFirm");
			$status = $this->input->post("bStatus");
			$fbrokerName = $this->input->post("broker_Name");
			$filterredBy = $this->input->post("filterby");
			$brokerName = $this->input->post("brokerName");
			$selectedDateFrom = $this->input->post("bSelectedDate") ? date("Y-m-d h:i:s", strtotime($this->input->post("bSelectedDate"))) : "";
			$selectedDateto = $this->input->post("bSelectedDateTo") != "" ?
				date("Y-m-d h:i:s", strtotime($this->input->post("bSelectedDateTo"))) : date("Y-m-d h:i:s");

			$where = "WHERE `job`.`created` <= '$selectedDateto'";

			$pdfTitle = "";
			if ($filterredBy == "status_f" && $status != "") {
				$pdfTitle = "Status: - " . strtoupper($status);
				$where .= " AND `job`.`status` = '$status'";
			}
			if ($selectedDateFrom != "") {
				$where .= " AND `job`.`created` >= '$selectedDateFrom'";
			}
			if ($filterredBy == "firm_f" && $firmId != "") {
				$where .= " AND `firm`.`id` = $firmId";
			}
			if ($filterredBy == "broker_f" && $brokerName != "") {
				$where .= " AND `job`.`brokerName` = '$brokerName'";
			}
			if ($filterredBy == "firm_f" && $fbrokerName != "") {
				$where .= " AND `job`.`brokerName` = '$fbrokerName'";
			}
			$mpdf = new \Mpdf();
			$mpdf->debug = true;

			$this->load->dbutil();
			$this->load->helper('file');
			$this->load->helper('download');
			$query = "SELECT CONCAT(DATE_FORMAT(`job`.`created`, '%d-%m-%Y'),', ',`job`.`total_quantity`,' ', `job`.`quantityType`,', Rs. ', `job`.`price`,', ',`commodities`.`commodity`,', ',`brokers`.`brokerName`) as BargainDetaiils,
		`job`.`id` as `bargainId`,
		IF(`job`.`quantityType` = 'trucks', '1', '-') as Quantity_in_trucks,
		`firm`.`firm_name` as FirmName,
		`firm`.`address` as FirmAddress
		from `job` LEFT JOIN firm ON firm.id = job.firmId
		LEFT JOIN commodities ON commodities.id = job.commodityId
		LEFT JOIN brokers ON brokers.id = job.brokerName
		$where";
			$result = $this->db->query($query);
			$query_result = $result->result();

			$entries = [];
			$bargainIds = '';

			foreach ($query_result as $list) {

				$subquery = "SELECT `jobMeta`.`jobId`, `jobMeta`.`recordCreated` as EntryDate, `jobMeta`.`truckNo` as TruckNo,
				`jobMeta`.`currentSlipNo` as kantaSlipNo,
				`jobMeta`.`cNetWeight` as Quantity_in_qts,
				`jobMeta`.`noOfBags` as Quantity_in_bags,
				`users`.`coFirm` as userFirm
				from `jobMeta`
				LEFT JOIN users ON users.id = jobMeta.addedBy WHERE `jobMeta`.`jobId` = $list->bargainId";

				$entriesResult = $this->db->query($subquery);

				if ($entriesResult->num_rows() > 0) {
					$entriesResult = $entriesResult->result();
					array_push($entries, ["bargain" => $list, "entries" => $entriesResult]);
				} else {
					array_push($entries, ["bargain" => $list, "entries" => null]);
				}
			}

			if ($filterredBy == "firm_f" && $firmId != "") {
				$pdfTitle = "Firm: - " . $entries[0]->FirmName;
			}

			$data["filterValues"] = [
				"startDate" => date("d-m-Y", strtotime($selectedDateFrom)) != "01-01-1970" ? date("d-m-Y", strtotime($selectedDateFrom)) : "",
				"endDate" => date("d-m-Y", strtotime($selectedDateto)),
				"title" => $pdfTitle
			];

			$data["entries"]  = $entries;
			// echo "<pre>";
			// print_r($entries);
			// echo "</pre>";
			// die();
			// $this->load->view('pdfViews/entriesList', $data);
			$html = $this->load->view('pdfViews/entriesList', $data, true);
			$mpdf->WriteHTML($html);
			ob_clean();
			$mpdf->Output("entriesReport.pdf", "D");
		}
	}

	// add broker
	public function addBroker()
	{
		$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Manage Broker';
		$this->data_array['pageTitle'] = 'Add Broker';

		if ($this->input->post()) {
			$form_data_arr = ["brokerName" => $this->input->post("broker_name")];

			$response_data =  $this->admin_job_model->addBroker($form_data_arr);;

			if ($response_data) {
				$this->session->set_flashdata("success", 'Broker added successfully');
			} else {
				$this->session->set_flashdata("error", 'Error while adding Broker');
			}
		}

		adminviews('addBroker', $this->data_array);
	}

	public function brokerslist()
	{
		$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Manage Broker';
		$this->data_array['pageTitle'] = 'Broker\'s List';
		adminviews('brokerslist', $this->data_array);
	}

	public function getBrokersList()
	{
		try {
			$postData = $this->input->post();
			$data = $this->admin_job_model->getBrokers($postData);
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', 'Error while getting brokers details: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
		}
	}

	public function updateBrokerDetails()
	{
		if ($this->input->post("brokerId")) {
			$form_data_arr = ["brokerName" => $this->input->post("brokerName"), "updated" => date("Y-m-d h:i:s")];
			$response_data =  $this->admin_job_model->updateBroker($form_data_arr, ["id" => decode($this->input->post("brokerId"))]);
			echo json_encode($response_data);
		}
	}

	public function exportExcel($type)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		if ($this->input->post()) {
			$firmId = $this->input->post("bFirm");
			$status = $this->input->post("bStatus");
			$fbrokerName = $this->input->post("broker_Name");
			$filterredBy = $this->input->post("filterby");
			$brokerName = $this->input->post("brokerName");
			$selectedDateFrom = $this->input->post("bSelectedDate") ? date("Y-m-d h:i:s", strtotime($this->input->post("bSelectedDate"))) : "";
			$selectedDateto = $this->input->post("bSelectedDateTo") != "" ?
				date("Y-m-d h:i:s", strtotime($this->input->post("bSelectedDateTo"))) : date("Y-m-d h:i:s");

			$where = "WHERE `job`.`created` <= '$selectedDateto'";

			$pdfTitle = "";
			if ($filterredBy == "status_f" && $status != "") {
				$pdfTitle = "Status: - " . strtoupper($status);
				$where .= " AND `job`.`status` = '$status'";
			}
			if ($selectedDateFrom != "") {
				$where .= " AND `job`.`created` >= '$selectedDateFrom'";
			}
			if ($filterredBy == "firm_f" && $firmId != "") {
				$where .= " AND `firm`.`id` = $firmId";
			}
			if ($filterredBy == "broker_f" && $brokerName != "") {
				$where .= " AND `job`.`brokerName` = '$brokerName'";
			}
			if ($filterredBy == "firm_f" && $fbrokerName != "") {
				$where .= " AND `job`.`brokerName` = '$fbrokerName'";
			}

			$fileName = 'Bargains_' . time() . '.xls';

			if ($type == "exportBargain") {
				$sheet->setTitle("Bargain's");
				$sheet->setCellValue('A1', "Sr. No.");
				$sheet->setCellValue('B1', "Purchase Order");
				$sheet->setCellValue('C1', "Party Name");
				$sheet->setCellValue('D1', "Commodity");
				$sheet->setCellValue('E1', "Broker");
				$sheet->setCellValue('F1', "Total Qty");
				$sheet->setCellValue('G1', "Remaining Qty");
				$sheet->setCellValue('H1', "Qty type");
				$sheet->setCellValue('I1', "Deal valid upto");
				$sheet->setCellValue('J1', "Delivery type");
				$rows = 2;

				$query = "SELECT `job`.`purchaseOrder` as PurchaseOrder, `firm`.`firm_name` as Firm,
					`commodities`.`commodity` as Commodity,`job`.`brokerName` as BrokerName, 
					`job`.`total_quantity` as TotalQuantity, `job`.`remaining_quantity` as RemainingQuantity,
					`job`.`quantityType` as QuantityType, `job`.`dealValidUpto` as DealValidUpto,
					`job`.`deliveryType` as DeliveryType, `job`.`status` as BargainStatus
					from `job` LEFT JOIN firm ON firm.id = job.firmId
					LEFT JOIN commodities ON commodities.id = job.commodityId $where";
				$result = $this->db->query($query);
				$query_result = $result->result();
				$serial = 1;
				foreach ($query_result as $val) {
					$sheet->setCellValue('A' . $rows, $serial);
					$sheet->setCellValue('B' . $rows, $val->PurchaseOrder);
					$sheet->setCellValue('C' . $rows, $val->Firm);
					$sheet->setCellValue('D' . $rows, $val->Commodity);
					$sheet->setCellValue('E' . $rows, $val->BrokerName);
					$sheet->setCellValue('F' . $rows, $val->TotalQuantity);
					$sheet->setCellValue('G' . $rows, $val->RemainingQuantity);
					$sheet->setCellValue('H' . $rows, $val->QuantityType);
					$sheet->setCellValue('I' . $rows, $val->DealValidUpto);
					$sheet->setCellValue('J' . $rows, $val->DeliveryType);
					$rows++;
					$serial++;
				}
			} else if ($type == "exportEntries") {
				$fileName = 'entries_' . time() . '.xls';
				$sheet->setTitle("Entries");
				$sheet->setCellValue('A1', "Sr. No.");
				$sheet->setCellValue('B1', "Bargain Details");
				$sheet->setCellValue('C1', "Entry Date");
				$sheet->setCellValue('D1', "Inward No.");
				$sheet->setCellValue('E1', "Truck No.");
				$sheet->setCellValue('F1', "Quantity (qts)");
				$sheet->setCellValue('G1', "Quantity (bags)");
				$sheet->setCellValue('H1', "Party");
				$sheet->setCellValue('I1', "Party Location");
				$sheet->setCellValue('J1', "Firm");
				$rows = 2;

				$equery = "SELECT CONCAT(DATE_FORMAT(`job`.`created`, '%d-%m-%Y'),', ',`job`.`total_quantity`,' ', `job`.`quantityType`,', Rs. ', `job`.`price`,', ',`commodities`.`commodity`,', ',`brokers`.`brokerName`) as BargainDetaiils,
					`jobMeta`.`jobId`, `jobMeta`.`recordCreated` as EntryDate, `jobMeta`.`truckNo` as TruckNo,
					`jobMeta`.`currentSlipNo` as kantaSlipNo,
					`jobMeta`.`cNetWeight` as Quantity_in_qts,
					`jobMeta`.`noOfBags` as Quantity_in_bags,
					`users`.`coFirm` as userFirm,
					IF(`job`.`quantityType` = 'trucks', '1', '-') as Quantity_in_trucks,
					`firm`.`firm_name` as FirmName,
					`firm`.`address` as FirmAddress
					from `jobMeta` 
					LEFT JOIN users ON users.id = jobMeta.addedBy
					LEFT JOIN firm ON firm.id = jobMeta.firmId
					LEFT JOIN commodities ON commodities.id = jobMeta.commodityId
					LEFT JOIN job ON job.id = jobMeta.jobId
					LEFT JOIN brokers ON brokers.id = job.brokerName $where";
				$eresult = $this->db->query($equery);

				$equery_result = $eresult->result();
				$eserial = 1;
				foreach ($equery_result as $val) {
					$sheet->setCellValue('A' . $rows, $eserial);
					$sheet->setCellValue('B' . $rows, $val->BargainDetaiils);
					$sheet->setCellValue('C' . $rows, $val->EntryDate);
					$sheet->setCellValue('D' . $rows, $val->kantaSlipNo);
					$sheet->setCellValue('E' . $rows, $val->TruckNo);
					$sheet->setCellValue('F' . $rows, $val->Quantity_in_qts);
					$sheet->setCellValue('G' . $rows, $val->Quantity_in_bags);
					$sheet->setCellValue('H' . $rows, $val->FirmName);
					$sheet->setCellValue('I' . $rows, $val->FirmAddress);
					$sheet->setCellValue('J' . $rows, $val->userFirm);
					$rows++;
					$eserial++;
				}
			} else if ($type == "exportBargainWithEntries") {


				$query = "SELECT CONCAT(DATE_FORMAT(`job`.`created`, '%d-%m-%Y'),', ',`job`.`total_quantity`,' ', `job`.`quantityType`,', Rs. ', `job`.`price`,', ',`commodities`.`commodity`,', ',`brokers`.`brokerName`) as BargainDetaiils,
			`job`.`id` as `bargainId`,
			IF(`job`.`quantityType` = 'trucks', '1', '-') as Quantity_in_trucks,
			`firm`.`firm_name` as FirmName,
			`firm`.`address` as FirmAddress
			from `job` LEFT JOIN firm ON firm.id = job.firmId
			LEFT JOIN commodities ON commodities.id = job.commodityId
			LEFT JOIN brokers ON brokers.id = job.brokerName
			$where";
				$result = $this->db->query($query);
				$query_result = $result->result();

				$entries = [];

				foreach ($query_result as $list) {

					$subquery = "SELECT `jobMeta`.`jobId`, `jobMeta`.`recordCreated` as EntryDate, `jobMeta`.`truckNo` as TruckNo,
				`jobMeta`.`currentSlipNo` as kantaSlipNo,
				`jobMeta`.`cNetWeight` as Quantity_in_qts,
				`jobMeta`.`noOfBags` as Quantity_in_bags,
				`users`.`coFirm` as userFirm
				from `jobMeta`
				LEFT JOIN users ON users.id = jobMeta.addedBy WHERE `jobMeta`.`jobId` = $list->bargainId";

					$entriesResult = $this->db->query($subquery);

					if ($entriesResult->num_rows() > 0) {
						$entriesResult = $entriesResult->result();
						array_push($entries, ["bargain" => $list, "entries" => $entriesResult]);
					} else {
						array_push($entries, ["bargain" => $list, "entries" => null]);
					}
				}

				$fileName = 'BargainsWEntries_' . time() . '.xls';
				$sheet->setTitle("Bargains wih Entries");
				$sheet->setCellValue('A1', "Sr. No.");
				$sheet->setCellValue('B1', "Bargain Details");
				$sheet->setCellValue('C1', "Entry Date");
				$sheet->setCellValue('D1', "Inward No.");
				$sheet->setCellValue('E1', "Truck No.");
				$sheet->setCellValue('F1', "Quantity (qts)");
				$sheet->setCellValue('G1', "Quantity (bags)");
				$sheet->setCellValue('H1', "Party");
				$sheet->setCellValue('I1', "Party Location");
				$sheet->setCellValue('J1', "Firm");
				$rows = 2;

				$eserial = 1;
				foreach ($entries as $val) {
					$sheet->setCellValue('A' . $rows, $eserial);
					$sheet->setCellValue('B' . $rows, $val["bargain"]->BargainDetaiils);
					if ($val["entries"]) {
						foreach ($val["entries"] as $eval) {
							$sheet->setCellValue('C' . $rows, $eval->EntryDate);
							$sheet->setCellValue('D' . $rows, $eval->kantaSlipNo);
							$sheet->setCellValue('E' . $rows, $eval->TruckNo);
							$sheet->setCellValue('F' . $rows, $eval->Quantity_in_qts);
							$sheet->setCellValue('G' . $rows, $eval->Quantity_in_bags);
							$sheet->setCellValue('H' . $rows, $val["bargain"]->FirmName);
							$sheet->setCellValue('I' . $rows, $val["bargain"]->FirmAddress);
							$sheet->setCellValue('J' . $rows, $eval->userFirm);
						}
					}
					$rows++;
					$eserial++;
				}
			}

			$writer = new Xlsx($spreadsheet);
			$writer->save("upload/" . $fileName);
			header("Content-Type: application/vnd.ms-excel");
			redirect(base_url() . "/upload/" . $fileName);
		}
	}
}
