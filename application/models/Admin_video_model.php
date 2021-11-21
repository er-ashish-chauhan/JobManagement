<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_video_model extends CI_Model
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
    public function list_video($postData = null)
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

            $searchQuery = " (j.job_name like '%" . $searchValue . "%' or f.firm_name like '%".$searchValue."%' or u.firstName like '%". $searchValue . "%' or u.lastName like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("job j");
        $this->db->join("firm f", "j.firmId = f.id", "left");
        $this->db->join("users u", "j.assignToId = u.id", "left");
        $query = $this->db->get();
        $records = $query->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("job j");
        $this->db->join("firm f", "j.firmId = f.id", "left");
        $this->db->join("users u", "j.assignToId = u.id", "left");

        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("CONCAT_WS(' ',u.firstName, u.lastName) fullname, j.id,  j.job_name, f.firm_name, j.assignToId");
        $this->db->from("job j");
        $this->db->join("firm f", "j.firmId = f.id", "left");
        $this->db->join("users u", "j.assignToId = u.id", "left");
               
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        
        if (!empty($columnName)) {
            $this->db->order_by($columnName, $columnSortOrder);
        } else {
            $this->db->order_by('usr.firstName', 'DESC');
        }

        if ($rowperpage != -1) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();
        $records = $query->result();
        $data = array();

        $i=$start+1;
        // loop to iterate and storing data into array accordingly that is going to display.
        foreach ($records as $record) {
            $id = $record->id;

            // $eStatus = $record->isActive;
            // $actionLinks = ""; // variable to store action link.
            //     if ($record->isDeleted != 0 || $record->isDeleted == "Disabled") {
            //         $class = "deleted-user-row";
            //     } else {
            //         $class = "";
            //     }

            // link to change status
            // if ($record->isActive == "0") {
            //     $actionLinks .= "<div style='width:140px;' class='$class'><a  href='javascript:void(0)' data-id='" .$id. "' data-status='1' class='btn btn-sm btn-flat btn-warning change-video-status' title='Click to activate' ><i class='fa fa-times'></i></a> ";
            // } else {
            //     $actionLinks .= "<div style='width:140px;' class='$class'><a  href='javascript:void(0)' data-id='" .$id. "' data-status='0' class='btn btn-sm btn-flat btn-success change-video-status' title='Click to deactivate'><i class='fa fa-check'></i></a> ";
            // }
            
            if (empty($record->assignToId)) {
                $actionLinks = "<a  data-id='" .$id. "' id='delete-video' href='javascript:void(0)'  class='btn btn-sm btn-flat  btn-info' title='Assign'data-toggle='modal' data-target='#job_modal'  >Assign</a> ";
            } 
            // // link to edit user
            // $actionLinks .= "<a  href='" . base_url('admin/firm_video/manage_video_detail?id=') . "" . encode($id) . " ' class='btn btn-sm btn-flat  btn-primary' title='Edit' ><i class=' fa fa-edit'></i></a> ";

           
 
            $data[] = array(
                $i++,
                // $actionLinks,
                $record->job_name,
                $record->firm_name,
                !empty($record->assignToId) ? $record->fullname : $actionLinks,
             );
        }

 

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
            "detail"=> [ $columnName ,$columnSortOrder],
            "detail"                => [ $columnName ,$columnSortOrder],
            "search_query"          =>$searchQuery,
            "last_query"            => $this->db->last_query()
        );
        // pr($response,1);
        return $response;
    }

    // get user data to edit
    function get_video_to_edit($id)
    {
        $this->db->select("vd.coachId, vd.categoryId,vd.videoBanner, vd.videoURL, vd.title, vd.shortDescription, vd.views, vd.isActive, vd.isDeleted, vd.id, vd.longDescription");
        $this->db->from("$this->table_video vd");
        $this->db->where("vd.isDeleted", 0);
        $this->db->where("id", $id);
        $result=$this->db->get()->row();
        return $result;

    }

    
}
