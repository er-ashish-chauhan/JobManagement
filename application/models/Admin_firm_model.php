<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_firm_model extends CI_Model
{
    private $table_users;
    private $table_states;

    public function __construct()
    {

        parent::__construct();
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

            $actionLinks = "<a  href='" . base_url('admin/firm/manage_firm_detail?id=') . "" . encode($id) . "&action=edit ' class='btn btn-sm btn-flat  btn-primary' title='View job details' >Edit</a> ";
          
            $data[] = array(
                $i++,
                $actionLinks,
                $record->firm_name,
                $record->address,
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
    function get_firm_to_edit($id)
    {
        $this->db->select("id,firm_name,address")
                  ->from("firm")
                  ->where('id', $id);
        
        $result=$this->db->get()->row();
        return $result;

    }


}
