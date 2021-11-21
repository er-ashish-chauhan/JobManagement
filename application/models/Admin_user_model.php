<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user_model extends CI_Model
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
    public function list_user($postData = null)
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

            $searchQuery = " (usr.firstName like '%" . $searchValue . "%' or usr.lastName like '%". $searchValue . "%' or usr.email like '%" . $searchValue . "%' or usr.contact like'%" . $searchValue . "%' or DATE(usr.created) like'%" . date('Y-m-d H:i:s', strtotime($searchValue)) . "%' or um.gender like'%" . $searchValue . "%' or DATE(usr.dob) like'%" . date('Y-m-d', strtotime($searchValue)) . "%' ) ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("$this->table_users usr");
        $this->db->join("userMeta um", "usr.id = um.userId", "left");
        
        $this->db->where("usr.role",3);
        $this->db->where("usr.isDeleted", 0);
        $query = $this->db->get();
        $records = $query->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("$this->table_users usr");
        $this->db->join("userMeta um", "usr.id = um.userId", "left");
        
        $this->db->where("role",3);
        $this->db->where("isDeleted", 0);
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("CONCAT_WS(' ',usr.firstName,usr.lastName) vFullName,usr.email,usr.contact,usr.profileImage,usr.dob,um.gender, usr.id, usr.isActive, usr.isDeleted, usr.created");
        $this->db->from("$this->table_users usr");
        $this->db->join("userMeta um", "usr.id = um.userId", "left");
               
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $this->db->where("role",3);
        $this->db->where("isDeleted", 0);
        
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
        // pr($records,1);
        // pr($this->db->last_query(),1);
        $data = array();

        $i=$start+1;
        // loop to iterate and storing data into array accordingly that is going to display.
        foreach ($records as $record) {
            $id = $record->id;

            $eStatus = $record->isActive;
            $actionLinks = ""; // variable to store action link.
                if ($record->isDeleted != 0 || $record->isDeleted == "Disabled") {
                    $class = "deleted-user-row";
                } else {
                    $class = "";
                }

            // link to change status
            if ($record->isActive == "0") {
                $actionLinks .= "<div style='width:140px;' class='$class'><a  href='javascript:void(0)' data-id='" .$id. "' data-status='1' class='btn btn-sm btn-flat btn-warning change-user-status' title='Click to activate' ><i class='fa fa-times'></i></a> ";
            } else {
                $actionLinks .= "<div style='width:140px;' class='$class'><a  href='javascript:void(0)' data-id='" .$id. "' data-status='0' class='btn btn-sm btn-flat btn-success change-user-status' title='Click to deactivate'><i class='fa fa-check'></i></a> ";
            }
            
            // if ($record->bDeleted == 0) {
                // link to soft deletion.
                $actionLinks .= "<a  data-id='" . encode($id) . "' id='delete-user' href='javascript:void(0)'  class='btn btn-sm btn-flat  btn-danger' title='Delete' ><i class=' fa fa-trash'></i></a> ";
            // } 
            // link to edit user
            $actionLinks .= "<a  href='" . base_url('admin/user/manage_user_detail?id=') . "" . encode($id) . " ' class='btn btn-sm btn-flat  btn-primary' title='Edit' ><i class=' fa fa-edit'></i></a> ";

            // $newcreatedDate = convertTimeZone($record->created, 'UTC', 'US/Eastern');
            $createdDate = !empty($record->created) ? date('m/d/Y h:i A', strtotime($record->created)) : "";

            $dob = date("m/d/Y", strtotime($record->dob));

            if(!empty($record->profileImage) && file_exists(USER_IMAGE_PATH.$record->profileImage) )
            {
                $path= USER_IMAGE_URL.$record->profileImage;
                $profile_img= "<a title='view profile image' class='btn btn-primary' href='$path' target='_blank' >view </a>";
            }
            else{
                $profile_img='-';
            }
 
            $data[] = array(
                $i++,
                // $actionLinks,
                $record->vFullName,
                $record->email,
                // $record->contact,
                // $profile_img,
                // $dob,
                // $record->gender,
                // $createdDate
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
    function get_user_to_edit($id)
    {
        $this->db->select("usr.firstName, usr.lastName, usr.email,usr.contact,usr.profileImage,usr.dob,um.gender, usr.id, usr.isActive, usr.isDeleted, usr.created");
        $this->db->from("$this->table_users usr");
        $this->db->join("userMeta um", "usr.id = um.userId", "left");
        $this->db->where("role",3);
        $this->db->where("isDeleted", 0);
        $this->db->where("usr.id", $id);
        $result=$this->db->get()->row();
        return $result;

    }

    
}
