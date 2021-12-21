<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entries_model extends CI_Model
{
    private $table_users;
    private $table_states;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'admin';
        $this->table_users = 'users';
        $this->table_video = 'videos';
    }

    // get all user details for listing
    public function entries_list($postData = null)
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

            $searchQuery = " (j.job_name like '%" . $searchValue . "%' or f.firm_name like '%" . $searchValue . "%' or u.firstName like '%" . $searchValue . "%' or u.lastName like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("jobMeta");
        $this->db->where("status", 1);
        // echo $this->db->last_query();
        $query = $this->db->get();
        $records = $query->result();
        // echo '<pre>'; print_r($records); echo '</pre>';
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("jobMeta");
        $this->db->where("status", 1);
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("jobMeta.id, jobMeta.previousSlip, jobMeta.currentSlip,jobMeta.bill,
        jobMeta.firmId, jobMeta.commodityId, jobMeta.entryType, jobMeta.deliveryType, jobMeta.created,commodities.commodity, firm.firm_name, jobMeta.cNetWeight");
        $this->db->from("jobMeta");
        $this->db->where('status', 1);
        $this->db->join('firm', 'firm.id = jobMeta.firmId', 'left');
        $this->db->join('commodities', 'commodities.id = jobMeta.commodityId', 'left');

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

            $actionLinks = "<a data-id='" . $id . "' id='showentrymodel' href='javascript:void(0)'  class='btn btn-sm btn-flat  btn-primary' data-toggle='modal' data-target='#entriesModal
            ' title='Approve'>Approve</a> ";

            $actionLinks .= "<a data-id='" . $id . "' id='rejectEntry' href='javascript:void(0)' class='btn btn-sm btn-flat  btn-danger' title='Reject'>Reject</a> ";

            $actionLinks .= "<a  href='" . base_url('admin/entries/manage_bargain_detail?id=') . encode($id) . " ' class='btn btn-sm btn-flat  btn-primary' title='Edit Bargain' >Make Bargain</a>";

            $previousSlip = "<a id='previous_img' data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->previousSlip . "'
             href='javascript:void(0)'><Image alt='Previous Slip' class='entryImage' src='" . str_replace("JobManagement/", "", base_url()) . $record->previousSlip . "' /></a>";

            $currentSlip = "<a data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->currentSlip . "'
             href='javascript:void(0)'><Image alt='Current Slip' class='entryImage' id='previous_img' src='" . str_replace("JobManagement/", "", base_url()) . $record->currentSlip . "' /></a>";

            $bill = "<a data-imageurl='" . str_replace("JobManagement/", "", base_url()) . $record->bill . "'
             href='javascript:void(0)'><Image alt='Bill Slip' class='entryImage' id='previous_img' src='" . str_replace("JobManagement/", "", base_url()) . $record->bill . "' /></a>";

            $data[] = array(
                $i++,
                $actionLinks,
                $record->firm_name,
                $record->commodity,
                $previousSlip,
                $currentSlip,
                $bill,
                $record->entryType,
                $record->deliveryType,
                $record->cNetWeight,
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

    public function getJobByEntryDetails($entryId)
    {
        $this->db->select("job.id as jobId");
        $this->db->from("jobMeta");
        $this->db->where("jobMeta.id", $entryId);
        $this->db->join("job", "job.firmId = jobMeta.firmId AND job.commodityId = jobMeta.commodityId", "left");
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    public function getjobById($where)
    {
        $this->db->select("*");
        $this->db->from("job");
        $this->db->where($where);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    public function getEntryById($where)
    {
        $this->db->select("*");
        $this->db->from("jobMeta");
        $this->db->where($where);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    public function updateJob($jobId, $data)
    {
        $this->db->where('id', $jobId);
        $this->db->update('job', $data);
        $afftectedRow = $this->db->affected_rows();
        return  $afftectedRow;
    }

    public function updatedJobMeta($entryId, $data)
    {
        $this->db->where('id', $entryId);
        $this->db->update('jobMeta', $data);
        $afftectedRow = $this->db->affected_rows();
        return  $afftectedRow;
    }

    public function insertBargain($data)
    {
        $this->db->insert('job', $data);
        $afftectedRow = $this->db->affected_rows();
        return $this->db->insert_id();
    }
}
