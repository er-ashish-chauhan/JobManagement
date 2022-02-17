<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Entries extends CI_Controller
{
    private $pageData = array();
    private $data_array = array();

    function __construct()
    {
        parent::__construct();
        adminAuth();
        $this->load->model('entries_model');
        $this->load->model('admin_job_model');
    }

    public function index()
    {
        $this->data_array['name'] = 'Entries';
        adminviews('entries_list', $this->data_array);
    }

    public function manage_bargain_detail()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $id = decode($this->input->get("id"));

            $this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Bargain';

            $this->data_array['disabled'] = TRUE;
            $this->data_array['pageTitle'] = 'Manage Bargain\'s';
            $this->data_array["btn_name"] = "Add";

            $this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();
            $this->data_array["commodities"] = $this->admin_job_model->getCommodities();

            $this->data_array['job_meta'] = $this->db->select("quantity, id")->from('jobMeta')->get()->row();
            $this->data_array['entries'] = $this->entries_model->getEntryById(["id" => $id]);

            adminviews('make_bargain', $this->data_array);
        } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

            $request = $this->input->post();
            $request = $this->security->xss_clean($request);
            unset($request['id']);

            $id = $request['job_meta_id'];

            $entryD = $this->entries_model->getEntryById(["id" => $id]);
            $checkIfJobExist = $this->admin_job_model->getLastBargain();

            $job_PO_number = str_pad(1, 5, "0", STR_PAD_LEFT);

            if ($checkIfJobExist) {
                $job_PO_number = str_pad($checkIfJobExist->purchaseOrder + 1, 5, 0, STR_PAD_LEFT);
            }

            $remainingQty = $request['total_quantity'];

            if ($request['qtyTpe'] == "qts") {
                $remainingQty = $request["total_quantity"] - $entryD->cNetWeight;
            } elseif ($request["qtyTpe"] == "bags") {
                $remainingQty = $request["total_quantity"] - $entryD->noOfBags;
            } else {
                $remainingQty = $request["total_quantity"] - 1;
            }

            $form_data_arr = array(
                "purchaseOrder" => $job_PO_number,
                'total_quantity' => $request['total_quantity'],
                "remaining_quantity" => $remainingQty,
                "price" => $request["qtyPrice"],
                "dealValidUpto" => $request["dealvalid"],
                "dealValidFrom" => $request["dealvalidFrom"],
                "quantityType" => $request["qtyTpe"],
                "brokerName" => $request["broker_name"],
                "deliveryType" => $entryD->deliveryType,
                "firmId" => $entryD->firmId,
                "commodityId" => $entryD->commodityId,
            );

            $response_data =  $this->entries_model->insertBargain($form_data_arr);

            if ($response_data) {

                $this->entries_model->updatedJobMeta($id, ["jobId" => $response_data, "status" => 2]);
                $this->session->set_flashdata("success", 'Bargain added successfully');
            } else {
                $this->session->set_flashdata("error", 'Error while adding Bargain');
            }
            redirect('admin/entries');
        }
    }

    public function get_entries_data()
    {
        try {
            $postData = $this->input->post();
            $data = $this->entries_model->entries_list($postData);
            echo json_encode($data);
        } catch (Exception $e) {
            log_message('error', 'Error while getting entries details: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
        }
    }

    public function approveEntry()
    {
        $id = $this->input->post("id");
        $jobId = $this->input->post("jobId");
        $quantity = $this->input->post("quantity");
        $noOfBags = $this->input->post("noOfBags");
        $netweight = $this->input->post("netweight");


        try {
            $dataToBeUpdate = [
                "jobId" => $jobId,
                "status" => 2
            ];

            $jobDetails = $this->entries_model->getjobById(["id" => $jobId]);
            if ($jobDetails) {
                $remaingQty = "";
                if ($jobDetails->quantityType == "qts") {
                    $remaingQty =  $jobDetails->remaining_quantity - $netweight;
                } else if ($jobDetails->quantityType == "bags") {
                    $remaingQty =  $jobDetails->remaining_quantity - $noOfBags;
                } else {
                    $remaingQty =  $jobDetails->remaining_quantity - 1;
                }

                $this->entries_model->updateJob($jobId, ["remaining_quantity" => $remaingQty]);
                $result = $this->entries_model->updatedJobMeta($id, $dataToBeUpdate);
                if ($result) {
                    $this->session->set_flashdata("success", 'Entry approved successfully');
                } else {
                    $this->session->set_flashdata("error", 'Error while approving entry');
                }
                echo json_decode($result);
            }
        } catch (Exception $e) {
            log_message('error', 'Error while deleting to commodity: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
        }
    }

    public function rejectEntry()
    {
        $id = $this->input->post("id");

        try {
            // $getJobId = $this->entries_model->getJobByEntryDetails($id);
            // if ($getJobId) {
            $dataToBeUpdate = [
                // "jobId" => $getJobId->jobId,
                "status" => 3
            ];
            $result = $this->entries_model->updatedJobMeta($id, $dataToBeUpdate);
            if ($result) {
                $this->session->set_flashdata("success", 'Entry rejected successfully');
            } else {
                $this->session->set_flashdata("error", 'Error while rejecting entry');
            }
            echo json_decode($result);
            // } else {
            //     $this->session->set_flashdata("error", 'Job does not exist in the record.');
            // }
        } catch (Exception $e) {
            log_message('error', 'Error while deleting to commodity: FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
        }
    }

    public function getjobdetails()
    {
        $id = $this->input->post('id');
        // $cid = $this->input->post('cid');

        $this->db->select('j.id as jobId, j.purchaseOrder, j.total_quantity, j.status, f.firm_name, c.commodity, jm.noOfBags, jm.cNetWeight');
        $this->db->from('jobMeta jm');
        $this->db->join("job j", "jm.commodityId=j.commodityId AND jm.firmId=j.firmId", "left");
        $this->db->join("firm f", "j.firmId=f.id", "left");
        $this->db->join("commodities c", "j.commodityId=c.id", "left");
        $this->db->where(["jm.id" => $id, "j.status" => "active"]);
        $query =  $this->db->get();
        $result = $query->result();

        // echo "<pre>";
        // print_r($result);

        $data = "";
        if ($query->num_rows() > 0) {
            foreach ($result as $row) {
                $radiobtn = '<div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input entriesRadio" id="defaultGroupExample' . $row->jobId . '" name="groupOfDefaultRadios" data-jobid="' . $row->jobId . '" data-entryid="' . $id . '" data-quantity="' . $row->total_quantity . '" data-quantitys="' . $row->noOfBags . '" data-netweight="' . $row->cNetWeight . '">
                <label class="custom-control-label" for="defaultGroupExample' . $row->jobId . '"></label>
              </div>
              ';
                $data .= "<tr>
              <td>$radiobtn</td>
              <td>$row->purchaseOrder</td>
              <td>$row->firm_name</td>
              <td>$row->commodity</td>
              <td>$row->total_quantity</td>
              <td>$row->status</td></tr>";
            }
        }


        echo $data;
    }

    public function view_entries()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $id = decode($this->input->get("id"));

            $this->data_array['title'] = lang("BRAND_NAME") . ' Admin | View Entry';

            $this->data_array['pageTitle'] = 'View Bargain\'s';
            $this->data_array["btn_name"] = "Add";

            $this->data_array['entry_details'] = $this->entries_model->viewEntriesDetail($id);
            adminviews('view_entries', $this->data_array);
        }
    }

    public function edit_entries_detail()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $id = decode($this->input->get("id"));

            $this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Bargain';

            $this->data_array['pageTitle'] = 'Manage Bargain\'s';
            $this->data_array["btn_name"] = "Add";

            $this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();
            $this->data_array["commodities"] = $this->admin_job_model->getCommodities();

            $this->data_array['entries'] = $this->entries_model->getEntryById(["id" => $id]);
            $this->data_array['entry_details'] = $this->entries_model->viewEntriesDetail($id);

            adminviews('edit_entries', $this->data_array);
        } else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

            $request = $this->input->post();
            $request = $this->security->xss_clean($request);

            $id = $request['job_meta_id'];

            $entry_details = $this->entries_model->viewEntriesDetail($id);

            unset($request['job_meta_id']);

            $form_data_arr = array(
                'firmId' => $request['firmId'],
                "commodityId" => $request["commodityId"],
                "entryType" => $request["entryType"],
                "deliveryType" => $request["deliveryType"],
                "cNetWeight" => $request["cNetWeight"],
                "cTareWeight" => $request["cTareWeight"],
                "noOfBags" => $request["noOfBags"],
                "truckNo" => $request["truckNo"],
                "kantaSlipNo" => $request["kantaSlipNo"],
                "previousSlipNo" => $request["previousSlipNo"],
                "currentSlipNo" => $request["currentSlipNo"],
                "billNo" => $request["billNo"],
                "cGrossWeight" => $request["cGrossWeight"],
            );

            if (!empty($_FILES['bill']['name'])) {
                $form_data_arr['bill'] = "jobMgmtApis/uploads/" . $_FILES['bill']['name'];
            }

            if (!empty($_FILES['previousSlip']['name'])) {
                $form_data_arr['previousSlip'] = "jobMgmtApis/uploads/" . $_FILES['previousSlip']['name'];
            }

            if (!empty($_FILES['currentSlip']['name'])) {
                $form_data_arr['currentSlip'] = "jobMgmtApis/uploads/" . $_FILES['currentSlip']['name'];
            }

            if (!empty($_FILES['kantaSlip']['name'])) {
                $form_data_arr['kantaSlip'] = "jobMgmtApis/uploads/" . $_FILES['kantaSlip']['name'];
            }

            $response_data =  $this->entries_model->updatedJobMeta($id, $form_data_arr);

            if ($response_data) {
                // $this->entries_model->updatedJobMeta($id, ["jobId" => $response_data, "status" => 2]);
                $this->session->set_flashdata("success", 'Entries updated successfully');
            } else {
                $this->session->set_flashdata("error", 'Error while updating Entries');
            }
            redirect('admin/entries');
        }
    }

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $fileName = 'Entries_' . time() . '.xls';
        $sheet->mergeCells("A1:J1");
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal('center');

        $fileName = 'entries_' . time() . '.xls';
        $sheet->setTitle("Entries");
        $sheet->setCellValue('A1', "Daily Entries");
        $sheet->setCellValue('A2', "SR. NO.");
        $sheet->setCellValue('B2', "ENTRY DATE");
        $sheet->setCellValue('C2', "INWARD NO.");
        $sheet->setCellValue('D2', "TRUCK NO.");
        $sheet->setCellValue('E2', "NET WEIGHT");
        $sheet->setCellValue('F2', "BAGS");
        $sheet->setCellValue('G2', "PARTY");
        $sheet->setCellValue('H2', "STATION");
        $sheet->setCellValue('I2', "COMMODITY");
        $sheet->setCellValue('J2', "IN");
        $rows = 3;

        $equery = "SELECT 
					`jobMeta`.`created` as EntryDate, `jobMeta`.`truckNo` as TruckNo,
					`jobMeta`.`currentSlipNo` as kantaSlipNo,
					`jobMeta`.`cNetWeight` as Quantity_in_qts,
					`jobMeta`.`noOfBags` as Quantity_in_bags,
					`users`.`coFirm` as userFirm,
					`commodities`.`commodity`,
					`firm`.`firm_name` as FirmName,
					`firm`.`address` as FirmAddress
					from `jobMeta` 
					LEFT JOIN users ON users.id = jobMeta.addedBy
					LEFT JOIN firm ON firm.id = jobMeta.firmId
					LEFT JOIN commodities ON commodities.id = jobMeta.commodityId WHERE `jobMeta`.`status` = 1";
        $eresult = $this->db->query($equery);

        $equery_result = $eresult->result();
        $eserial = 1;
        foreach ($equery_result as $val) {
            $sheet->setCellValue('A' . $rows, $eserial);
            $sheet->setCellValue('B' . $rows, date("d/m/Y", strtotime($val->EntryDate)));
            $sheet->setCellValue('C' . $rows, $val->kantaSlipNo);
            $sheet->setCellValue('D' . $rows, $val->TruckNo);
            $sheet->setCellValue('E' . $rows, $val->Quantity_in_qts);
            $sheet->setCellValue('F' . $rows, $val->Quantity_in_bags);
            $sheet->setCellValue('G' . $rows, $val->FirmName);
            $sheet->setCellValue('H' . $rows, $val->FirmAddress);
            $sheet->setCellValue('I' . $rows, $val->commodity);
            $sheet->setCellValue('J' . $rows, $val->userFirm);
            $rows++;
            $eserial++;
        }

        $sheet->getStyle("A1")->getFont()->setSize("14")->setBold(true);
        $sheet->getStyle("A2:J2")->getFont()->setSize("12")->setBold(true);

        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('I')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('J')->getAlignment()->setHorizontal('center');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $writer = new Xlsx($spreadsheet);
        $writer->save("upload/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        redirect(base_url() . "/upload/" . $fileName);
    }
}
