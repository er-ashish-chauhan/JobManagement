<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
   private $pageData = array();
    function __construct()
    {
        parent::__construct();
        adminAuth();
       
    }

    public function index()
    {
        $this->pageData['name'] = 'raghav';
        // die('raghav');
        adminviews('dashboard', $this->pageData);
        // $this->load->model('admin_dashboard_model');
        // $totalcount = $this->admin_dashboard_model->totalcount();
        // $sevendayscount = $this->admin_dashboard_model->countlastsevendays();
        // $thirtydayscount = $this->admin_dashboard_model->countthirtydays();
        // $this->load->view('admin/user', ['tcount' => $totalcount, 'tsevencount' => $sevendayscount, 'tthirycount' => $thirtydayscount]);
    }
}
