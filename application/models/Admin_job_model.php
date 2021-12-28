<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_job_model extends CI_Model
{
    private $table_users;
    private $table_states;

    public function __construct()
    {

        parent::__construct();
        $this->table = 'admin';
        $this->table_users = 'users';
    }

    // get all user details for listing
    public function jobsList($postData = null)
    {

        $response = array();
        ## Reading post values
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['name']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        ## variable to store data for searching.
        $searchQuery = "";
        if ($searchValue != '') {
            if ($searchValue == 'Active') {
                $searchValue = 'Enabled';
            }
            if ($searchValue == 'Inactive') {
                $searchValue = 'Disabled';
            }

            $searchQuery = " (j.purchaseOrder like '%" . $searchValue . "%' or f.firm_name like '%" . $searchValue . "%' or j.status like '%" . $searchValue . "%' or j.dealValidFrom like '%" . $searchValue . "%' or j.dealValidUpto like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("job j");
        $this->db->join("firm f", "j.firmId = f.id", "left");
        $query = $this->db->get();
        $records = $query->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("job j");
        $this->db->join("firm f", "j.firmId = f.id", "left");
        // $this->db->join("users u", "j.assignToId = u.id", "left");

        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("j.id,  j.purchaseOrder, f.firm_name, j.assignToId, j.dealValidFrom, j.dealValidUpto, 
        j.total_quantity, CONCAT(j.total_quantity,' ',j.quantityType) AS quanity, j.status, commodities.commodity, 
        CONCAT(j.remaining_quantity,' ',j.quantityType) as remaining_quantity");
        $this->db->from("job j");
        $this->db->join("firm f", "j.firmId = f.id", "left");
        $this->db->join("commodities", "j.commodityId = commodities.id", "left");

        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        if (!empty($columnName)) {
            $this->db->order_by($columnName, $columnSortOrder);
        } else {
            $this->db->order_by('j.purchaseOrder', 'DESC');
        }

        if ($rowperpage != -1) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();
        $records = $query->result();
        $data = array();

        $i = $start + 1;
        // loop to iterate and storing data into array accordingly that is going to display.
        foreach ($records as $record) {
            $id = $record->id;

            if (empty($record->assignToId)) {
                $actionLinks = "<a  data-id='" . $id . "' id='delete-video' href='javascript:void(0)'  class='btn btn-sm btn-flat  btn-info' title='Assign'data-toggle='modal' data-target='#job_modal'  >Assign</a> ";
            }
            // link to edit user
            $actionLinks_view = "<a  href='" . base_url('admin/viewJobEntries') . "/" . encode($id) . "' class='btn btn-sm btn-flat  btn-primary' title='View job details' ><i class=' fa fa-eye'></i></a> ";
            $actionLinks_edit = "<a  href='" . base_url('admin/editBargain') . "/" . encode($id) . " ' class='btn btn-sm btn-flat  btn-primary' title='Edit Bargain' ><i class=' fa fa-edit'></i></a>";
            $actionLinks_complete = "<a  href='" . base_url('admin/completeBargain') . "/" . encode($id) . " ' class='btn btn-sm btn-flat  btn-primary' title='Edit Bargain' ><i class=' fa fa-check'></i></a>";
            // $actionLinks_edit = "<a  href='" . base_url('admin/editBargain') . "/" . encode($id) . "' class='btn btn-sm btn-flat  btn-primary' title='View job details' >Edit Bargain</a> ";

            $newvalidfrom = date('m-d-Y', strtotime($record->dealValidFrom));
            $newvalidto = date('m-d-Y', strtotime($record->dealValidUpto));

            $data[] = array(
                $i++,
                $actionLinks_view . "  " . $actionLinks_edit . "  " . $actionLinks_complete,
                $record->purchaseOrder,
                $record->firm_name,
                $record->remaining_quantity,
                $newvalidto,
                $record->quanity,
                $record->commodity,
                $record->status
            );
        }



        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
            "detail" => [$columnName, $columnSortOrder],
            "detail"                => [$columnName, $columnSortOrder],
            "search_query"          => $searchQuery,
            "last_query"            => $this->db->last_query()
        );
        // pr($response,1);
        return $response;
    }

    function getCommodities()
    {
        $this->db->select("*");
        $this->db->from("commodities");
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return array();
        }
    }

    public function getJobEntries($postData = null, $jobId)
    {

        $response = array();
        ## Reading post values
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['name']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        ## variable to store data for searching.

        $searchByFromdate = $postData['searchByFromdate'];
        $searchByTodate = $postData['searchByTodate'];

        $searchQuery = "";
        if ($searchValue != '') {
            if ($searchValue == 'Active') {
                $searchValue = 'Enabled';
            }
            if ($searchValue == 'Inactive') {
                $searchValue = 'Disabled';
            }

            $searchQuery = " (j.purchaseOrder like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("jobMeta");
        $this->db->where("jobId", $jobId);
        // Date filter
        if ($searchByFromdate != '' && $searchByTodate != '') {
            $daterange_condition = "(jobMeta.created between '" . $searchByFromdate . "' and '" . $searchByTodate . "' ) ";
        $this->db->where($daterange_condition);
        }
        $query = $this->db->get();
        $records = $query->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("jobMeta");
        $this->db->where("jobId", $jobId);
        // Date filter
        if ($searchByFromdate != '' && $searchByTodate != '') {
            $daterange_condition = "(jobMeta.created between '" . $searchByFromdate . "' and '" . $searchByTodate . "' ) ";
        $this->db->where($daterange_condition);
        }
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("jobMeta.id, jobMeta.currentSlipNo, jobMeta.previousSlip, jobMeta.currentSlip,jobMeta.bill,
        jobMeta.firmId, jobMeta.commodityId, jobMeta.entryType, jobMeta.deliveryType, jobMeta.created, j.quantityType,
        jobMeta.noOfBags, jobMeta.cNetWeight");
        $this->db->from("jobMeta");
        $this->db->where('jobId', $jobId);
        $this->db->where('jobMeta.status', 2);
        // Date filter
        if ($searchByFromdate != '' && $searchByTodate != '') {
            $daterange_condition = "(jobMeta.created between '" . $searchByFromdate . "' and '" . $searchByTodate . "' ) ";
        $this->db->where($daterange_condition);
        }
        $this->db->join("job j", "jobMeta.jobId = j.id", "left");
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        // if (!empty($columnName)) {
        //     $this->db->order_by($columnName, $columnSortOrder);
        // } else {
        $this->db->order_by('jobMeta.id', 'DESC');
        // }

        if ($rowperpage != -1) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();
        $records = $query->result();
        $data = array();

        $i = $start + 1;
        // loop to iterate and storing data into array accordingly that is going to display.
        foreach ($records as $record) {
            $id = $record->id;
            $qty = "1 Truck";

            if ($record->quantityType == "qts") {
                $qty = $record->cNetWeight . ' qts';
            } else if ($record->quantityType == "bags") {
                $qty = $record->noOfBags . ' Bags';
            }

            $previousSlip = "<a class='previous_img' data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->previousSlip . "'
             href='javascript:void(0)'><Image alt='Previous Slip' class='entryImage' src='" . str_replace("JobManagement/", "", base_url()) . $record->previousSlip . "' /></a>";

            $currentSlip = "<a class='previous_img' data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->currentSlip . "'
             href='javascript:void(0)'><Image alt='Current Slip' class='entryImage' src='" . str_replace("JobManagement/", "", base_url()) . $record->currentSlip . "' /></a>";

            $bill = "<a class='previous_img' data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->bill . "'
             href='javascript:void(0)'><Image alt='Bill Slip' class='entryImage' src='" . str_replace("JobManagement/", "", base_url()) . $record->bill . "' /></a>";

            $data[] = array(
                $i++,
                $record->currentSlipNo,
                $previousSlip,
                $currentSlip,
                $bill,
                $qty,
                $record->deliveryType,
                $record->created,
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
            "detail" => [$columnName, $columnSortOrder],
            "detail"                => [$columnName, $columnSortOrder],
            "search_query"          => $searchQuery,
            "last_query"            => $this->db->last_query()
        );
        // pr($response,1);
        return $response;
    }

    public function checkIfJobExist($where)
    {
        $this->db->select('*');
        $this->db->from("job");
        $this->db->where($where);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function getLastBargain()
    {
        $this->db->select('*');
        $this->db->from("job");
        $this->db->limit(1);
        $this->db->order_by('id', "DESC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function updateBargain($data, $where)
    {
        $this->db->where($where);
        $this->db->update('job', $data);
        $afftectedRow = $this->db->affected_rows();
        return  $afftectedRow;
    }

    public function getJobEntriesDetails($where)
    {
        $this->db->select("COUNT(*) as count");
        $this->db->select_sum('noOfBags');
        $this->db->select_sum('cNetWeight');
        $this->db->from('jobMeta');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row();
    }
}
