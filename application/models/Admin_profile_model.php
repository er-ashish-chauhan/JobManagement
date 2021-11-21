<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_profile_model extends CI_Model
{
    private $table_users;
    private $table_states;

    public function __construct()
    {

        parent::__construct();
        $this->table = 'admin';
        $this->table_users = 'users';
    }

    // get user data to edit
    function get_data_to_edit($id)
    {
        $this->db->select("usr.firstName, usr.lastName, usr.email,usr.contact,usr.profileImage, usr.id, usr.isActive, usr.isDeleted, usr.created");
        $this->db->from("$this->table_users usr");
        $this->db->where("role",1);
        $this->db->where("isDeleted", 0);
        $this->db->where("usr.id", $id);
        $result=$this->db->get()->row();
        return $result;

    }

    
}
