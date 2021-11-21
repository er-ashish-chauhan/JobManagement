<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Profile extends CI_Controller
{
    private $pageData = array();
    function __construct()
    {
        parent::__construct();
        adminAuth();
        $this->load->model('admin_profile_model');
    }

    public function index()
    {
        $id = $this->session->userdata()['is_admin']['id'];
        $this->data_array['data'] = $this->admin_profile_model->get_data_to_edit($id);

        adminviews('profile', $this->data_array);
    }



    /*
		* function to edit and update admin profile details    
		* @param       userid on edit and post values on update
		* @return      null
		*/
    public function manage_profile_detail()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $request = $this->input->post();
            $request = $this->security->xss_clean($request);
            $id = $this->session->userdata()['is_admin']['id'];

            $form_data_arr = array(
                "firstName" => trim($request["firstName"], " "),
                "lastName" => trim($request["lastName"], " "),
                "contact" => $request["contact"],
                "email" => $request["email"],
                "role" => 1,
                "updated" => date("Y-m-d H:i:s"),
            );

            if (!empty($request["password"])) {
                $form_data_arr['password'] = password_hash($request["password"], PASSWORD_DEFAULT);
            }

            if ($_FILES['profileImage']['name']) {
                $type = explode("/", $_FILES['profileImage']['type']);
                $_FILES['profileImage']['name'] = "profileImage" . time() . "." . $type[1];
                $image_uploded = $this->upload_files();

                if (isset($image_uploded['file_name']) && !empty($image_uploded['file_name'])) {
                    $form_data_arr['profileImage'] = $image_uploded['file_name'];
                }
            }

            $this->db->trans_start();

            $this->db->where("id", $id);
            $this->db->update("users", $form_data_arr);
            $this->db->trans_complete();

            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata("error", lang("PROFILE_UPDATE_FAILED"));
                redirect('admin/profile');
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata("success", lang("PROFILE_UPDATE_SUCCESS"));
                // echo "raghav2"; die;
                redirect('admin/profile');
            }

            // } 
        }
    }

    // function to upload image called from another function.
    private function upload_files()
    {
        $config['upload_path']          = ADMIN_IMAGE_PATH;
        $config['allowed_types']        = 'jpg|png|jpeg';
        $config['max_size']        = 5000;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('profileImage')) {
            $error['msg'] = $this->upload->display_errors();
            $error['er_no'] = 0;
            log_message('error', 'Error while uploading profile image of coach: Error-message' . $error['msg'] . ' FILE-' . __FILE__ . 'CLASS: ' . __CLASS__ . 'FUNCTION: ' . __FUNCTION__);
            $this->session->set_flashdata("error", $error['msg']);
            redirect_back();
        }
        return $this->upload->data();
    }


    /**
     * Function to check unique email (jquery validation)
     * @return      true or false
     * 
     */
    function unique_email()
    {
        $vEmail = $this->input->get("email");
        // $id = $this->input->get("id");
        $id = $this->session->userdata()['is_admin']['id'];
        $this->db->select("*");
        if (!empty($id)) {
            $this->db->where("id != ", $id);
        }
        $this->db->where("email", $vEmail);
        $this->db->where("isDeleted", 0);
        $query = $this->db->get("users");
        if ($query->num_rows() > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Function to Logout Admin
     * NOTE : Common model for common SQL queries is globally available to be used by the name "orm";
     *
     */
    public function logout()
    {
        delete_cookie('id');
        // pr($this->session->userdata(),1);
        $this->session->unset_userdata('is_admin');
        redirect('admin/login');
    }
}
