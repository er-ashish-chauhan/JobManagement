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

            $searchQuery = " (j.job_name like '%" . $searchValue . "%' or f.firm_name like '%" . $searchValue . "%' or j.status like '%" . $searchValue . "%' or j.dealValidFrom like '%" . $searchValue . "%' or j.dealValidUpto like '%" . $searchValue . "%') ";
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
        $this->db->select("j.id,  j.job_name, f.firm_name, j.assignToId, j.dealValidFrom, j.dealValidUpto, j.status");
        $this->db->from("job j");
        $this->db->join("firm f", "j.firmId = f.id", "left");

        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        if (!empty($columnName)) {
            $this->db->order_by($columnName, $columnSortOrder);
        } else {
            $this->db->order_by('j.job_name', 'DESC');
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
            $actionLinks_view = "<a  href='" . base_url('job/viewJobEntries') . "/" . encode($id) . "' class='btn btn-sm btn-flat  btn-primary' title='View job details' >View Details</a> ";

             $newvalidfrom= date('m-d-Y', strtotime($record->dealValidFrom));
             $newvalidto= date('m-d-Y', strtotime($record->dealValidUpto));

            $data[] = array(
                $i++,
                $actionLinks_view,
                $record->job_name,
                $record->firm_name,
                $newvalidfrom,
                $newvalidto,
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
        $searchQuery = "";
        if ($searchValue != '') {
            if ($searchValue == 'Active') {
                $searchValue = 'Enabled';
            }
            if ($searchValue == 'Inactive') {
                $searchValue = 'Disabled';
            }

            $searchQuery = " (j.job_name like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("jobMeta");
        $this->db->where("jobId", $jobId);
        $query = $this->db->get();
        $records = $query->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("jobMeta");
        $this->db->where("jobId", $jobId);
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("jobMeta.id, jobMeta.previousSlip, jobMeta.currentSlip,jobMeta.bill,
        jobMeta.firmId, jobMeta.commodityId, jobMeta.entryType, jobMeta.deliveryType, jobMeta.created");
        $this->db->from("jobMeta");
        $this->db->where('jobId', $jobId);
        $this->db->where('status', 2);

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

            $previousSlip = "<a data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->previousSlip . "'
             href='javascript:void(0)'><Image alt='Previous Slip' class='entryImage' src='" . str_replace("JobManagement/", "", base_url()) . $record->previousSlip . "' /></a>";

            $currentSlip = "<a data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->currentSlip . "'
             href='javascript:void(0)'><Image alt='Current Slip' class='entryImage' src='" . str_replace("JobManagement/", "", base_url()) . $record->currentSlip . "' /></a>";

            $bill = "<a data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->bill . "'
             href='javascript:void(0)'><Image alt='Bill Slip' class='entryImage' src='" . str_replace("JobManagement/", "", base_url()) . $record->bill . "' /></a>";

            $data[] = array(
                $i++,
                $previousSlip,
                $currentSlip,
                $bill,
                $record->entryType,
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
}
