<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_dashboard_model extends CI_Model
{

    
    public function totalcount()
    {
        $query = $this->db->select('id')
            ->get('tblusers');
        return  $query->num_rows();
    }

    public function countlastsevendays()
    {
        $query2 = $this->db->select('id')
            ->where('regDate >=  DATE(NOW()) - INTERVAL 10 DAY')
            ->get('tblusers');
        return  $query2->num_rows();
    }

    public function countthirtydays()
    {
        $query3 = $this->db->select('id')
            ->where('regDate >=  DATE(NOW()) - INTERVAL 30 DAY')
            ->get('tblusers');
        return  $query3->num_rows();
    }
}
