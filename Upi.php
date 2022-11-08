<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Upi extends REST_Controller {
	function __construct(){
        parent::__construct();
		date_default_timezone_set("Asia/Calcutta");
        $this->load->helper(array('form', 'url','string','email')); 
        $this->load->library(array('form_validation'));
        $this->load->model(array('LoginModel','OtherModel','Api_models'));
	}

	function callback_post()
	{
		$ss = array(
			"data" => '[i]'.file_get_contents('php://input'),
			"method" => "upipost",
			"entry_date" => date('Y-m-d H:i:s')
		);
		$this->Api_models->insert_data('temp',$ss);
		$result = $this->db->insert_id();
		
		$msg = array(
			'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
			'id' => $result, 
			'data' => $ss
		);
		$this->response($msg,200);
		//echo'ok';
	}

	
	
	// function callback_post()
	// {
	// 	$ss = array(
	// 		"data" => '[i]'.file_get_contents('php://input'),
	// 		"method" => "upipost"
	// 	);
	// 	$this->Api_models->insert_data('temp',$ss);
	// 	echo'ok';
	// }

	function callback_get()
	{
		$ss = array(
			"data" => '[i]'.file_get_contents('php://input'),
			"method" => "upiget"
		);
		$this->Api_models->insert_data('temp',$ss);
		echo'ok';
	}
}
