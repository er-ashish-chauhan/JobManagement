<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_coach_model extends CI_Model
{
    private $table_users;
    private $table_states;

    public function __construct()
    {

        parent::__construct();
        $this->table = 'admin';
        $this->table_users = 'users';
        $this->table_patient_details = 'patient_details';
        $this->table_documents = 'documents';
        $this->table_appointment = 'appointment';
        $this->table_doctor_details = 'doctor_details';
        $this->table_states = 'states';
        $this->table_pharmacy_details='pharmacy_details';
        $this->table_timezone = 'user_timezones';
        $this->table_insurance = 'insurance';
    }

    // get all user details for listing
    public function list_coach($postData = null)
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

            $searchQuery = " (firm_name like '%" . $searchValue . "%' or address like '%".$searchValue."%' ) ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("firm");
        $query = $this->db->get();
        $records = $query->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("firm");
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("id,firm_name,address");
        $this->db->from("firm");
      
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

      
        
        if (!empty($columnName)) {
            $this->db->order_by($columnName, $columnSortOrder);
        } else {
            $this->db->order_by('firm_name', 'ASC');
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
          
            $data[] = array(
                $i++,
                // $actionLinks,
                $record->firm_name,
                $record->address,
                // $record->contact,
                // $profile_img,
                // $dob,
                // $record->bio,
                // $result_follower,
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
    function get_coach_to_edit($id)
    {
        $this->db->select("usr.firstName, usr.lastName, usr.email,usr.contact,usr.profileImage,usr.dob,cd.bio, usr.id, usr.isActive, usr.isDeleted, usr.created");
        $this->db->from("$this->table_users usr");
        $this->db->join("coachDetails cd", "usr.id = cd.userId", "left");
        $this->db->where("role",2);
        $this->db->where("isDeleted", 0);
        $this->db->where("usr.id", $id);
        $result=$this->db->get()->row();
        return $result;

    }


}
