<?php
defined('BASEPATH') or exit('No direct script access allowed');
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



			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Jobs';

			$this->data_array['disabled'] = TRUE;
			$this->data_array['title'] = lang("BRAND_NAME") . ' Admin | Add Bargain';
			$this->data_array['pageTitle'] = 'Manage Bargain\'s';
			$this->data_array["btn_name"] = "Add";

			$this->data_array['firm_list'] = $this->db->select("firm_name, id")->from('firm')->get()->result();
			$this->data_array["commodities"] = $this->admin_job_model->getCommodities();

            $this->data_array['job_meta'] = $this->db->select("quantity, id")->from('jobMeta')->get()->row();

			adminviews('make_bargain', $this->data_array);
		} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {

			$request = $this->input->post();
			$request = $this->security->xss_clean($request);
			unset($request['id']);

            $id = $request['job_meta_id'];

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
				"brokerName" => $request["broker_name"]
			);

            // delete the record from Job meta
            $this->db->where("id", $id);
            $this->db->delete("jobMeta");

			$response_data =  $this->db->insert("job", $form_data_arr);

			if ($response_data) {
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
                    $remaingQty =  $jobDetails->total_quantity - $netweight;
                } else if ($jobDetails->quantityType == "bags") {
                    $remaingQty =  $jobDetails->total_quantity - $noOfBags;
                } else {
                    $remaingQty =  $jobDetails->total_quantity - 1;
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
}
