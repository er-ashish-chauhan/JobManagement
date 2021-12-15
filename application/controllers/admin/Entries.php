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
    }

    public function index()
    {
        $this->data_array['name'] = 'Entries';
        adminviews('entries_list', $this->data_array);
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

        $this->db->select('j.id as jobId, j.purchaseOrder, jm.quantity as total_quantity, j.status, f.firm_name, c.commodity, jm.noOfBags, jm.cNetWeight');
        $this->db->from('jobMeta jm');
        $this->db->join("job j", "jm.commodityId=j.commodityId AND jm.firmId=j.firmId", "left");
        $this->db->join("firm f", "j.firmId=f.id", "left");
        $this->db->join("commodities c", "j.commodityId=c.id", "left");
        $this->db->where("jm.id", $id);
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
