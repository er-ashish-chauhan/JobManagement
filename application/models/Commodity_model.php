<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commodity_model extends CI_Model
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
    public function commodity_list($postData = null)
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

            $searchQuery = " (c.commodity like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("commodities c");
        $query = $this->db->get();
        $records = $query->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from("commodities c");
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $query = $this->db->get();
        $records = $query->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("c.id,  c.commodity");
        $this->db->from("commodities c");

        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        if (!empty($columnName)) {
            $this->db->order_by($columnName, $columnSortOrder);
        } else {
            $this->db->order_by('c.commodity', 'ASC');
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

                // $actionLinks = "<a  data-id='" . $id . "' id='delete-commodity' href='javascript:void(0)'  class='btn btn-sm btn-flat  btn-danger' title='delete'  >Delete</a> ";
            
            // link to edit user
            $actionLinks = "<a  href='" . base_url('admin/commodity/edit_commodity_detail?id=') . "" . encode($id) . "&action=edit ' class='btn btn-sm btn-flat  btn-primary' title='View job details' >Edit</a> ";


            $data[] = array(
                $i++,
                $actionLinks,
                $record->commodity,
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
}
