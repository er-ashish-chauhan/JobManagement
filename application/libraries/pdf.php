
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class pdf
{
    function __construct()
    {
        $CI = &get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }

    function load($param = NULL)
    {
        include_once APPPATH . 'third_party/mpdf/mpdf/mpdf.php';
        if ($params == NULL) {
            $params = '"en-GB-x","A4","","",10,10,10,10,6,3';
        }
        // print_r(new mPDF($param));
        // die();
        return new mPDF($param);
    }
}