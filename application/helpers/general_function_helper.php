<?php
if(!function_exists('pr'))
{
  function pr($data, $flag='')
  {
	  echo "<pre>";
	  print_r($data);
	  echo "</pre>";

	  if($flag == 1)
	  {
		  die;
	  }

  }
}


if (!function_exists('get_admin_session')) {
	function get_admin_session($index)
	{
		$instance_CI = &get_instance();

		$userdata = $instance_CI->session->userdata();
		if (isset($userdata['admin'][$index])) {
			return $userdata['admin'][$index];
		} else {
			return false;
		}
	}
}

if (!function_exists('encode')) {
	function encode($string)
	{
		return base64_encode($string);
	}
}

/**
 * @description function to return base64 decoded string
 * @param no params
 */
if (!function_exists('decode')) {
	function decode($string)
	{
		return base64_decode($string);
	}
}

/**
 * @description function to redirect back 
 * @param no params
 */
if (!function_exists('redirect_back')) {
    function redirect_back($get = null)
    {
        $param = ($get == null) ? "" : $get;
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . $param);
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME'] . $param);
        }
        exit;
    }
}

/**
 * @description function to authenticate admin 
 * @param no params
 */
if (!function_exists('adminAuth')) {
	function adminAuth()
	{
		$CI = &get_instance();
		// $iUserId = get_admin_session('iUserId');
		$id = $CI->session->userdata['is_admin']['id'];
		// pr($id,1);
		if (empty($id)) {
			$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' .  $_SERVER['HTTP_HOST'];
			$url = $base_url . $_SERVER["REQUEST_URI"];
			redirect('admin/login?redirect_url=' . urlencode($url));
		}
	}
}

// function to load admin view

if( !function_exists('adminviews'))
{
   function adminviews($viewName,$data= array())
   {
	   $CI = &get_instance();
	   $CI->load->view('admin/includes/header', $data);
	   $CI->load->view('admin/includes/sidebar', $data);
	   $CI->load->view('admin/'.$viewName, $data);
	//    die('raghav');
      $CI->load->view('admin/includes/footer', $data);
   }
}

// FUNCTIO TO CONVERT TIME ZONE
function convertTimeZone($dateTime, $TimeZoneFrom = "America/New_York", $TimeZoneTo = "UTC")
{
    $CI = &get_instance();
    $query = "SELECT CONVERT_TZ('$dateTime','$TimeZoneFrom','$TimeZoneTo') as ConvertedDateTime";
    return $CI->db->query($query)->row()->ConvertedDateTime;
}