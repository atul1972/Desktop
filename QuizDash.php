<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require_once (APPPATH .'/controllers/prod/SmsinstaApi.php');
require_once (APPPATH .'/controllers/prod/WalletApi.php');

class QuizDash extends REST_Controller {
	function __construct(){
        parent::__construct();
		date_default_timezone_set("Asia/Calcutta");
        $this->load->helper(array('form', 'url','string','email','employee')); 
        $this->load->library(array('form_validation','Xml2array'));
        $this->load->model(array('EmployeeModel','QuizModel','Api_models'));
	}
	

	function param($param){

        $post = $this->security->xss_clean($this->input->post());
        if(!isset($post['skey']) || $post['skey'] != ESKEY)
        {
            $error = array(
                'status' => '0',
                'error' => 'AE1',
                'message' => 'Authentications Failed'
            );
            $this->response($error, 200);           
        }
             
        $this->form_validation->set_data($post);
        $this->form_validation->set_rules($param);

        if($this->form_validation->run()==FALSE)
        {
            $error = array(
                'status' => '0',
                'error' => 'AE1',
                'message' => 'Invalid Request'
            );
            $this->response($error, 200);
        }

        return $post;
    }


	function get_post()
	{    
		$param = [
            ['field' => 'number','label' => 'Mobile Number','rules' => 'trim|required|integer|exact_length[10]'],
            ['field' => 'token','label' => 'Token','rules' => 'trim|required'],
            ['field' => 'page','label' => 'page','rules' => 'trim|required|integer'],
            ['field' => 'deviceid','label' => 'deviceid','rules' => 'trim|required']
	    ];    
		$post = $this->param($param);
        				
        $token = trim($post['token']); 
        $number = trim($post['number']);
        $page = trim($post['page']); 
        $deviceid = $post['deviceid'];

        $dateTime = date("Y-m-d H:i:s");

	    $customJWT = json_decode(_decryptIt($token),true);

	    if(!$customJWT){
	    	$error = array(
                'status'=> '0', 
                'error' => 'EX1',
                'message' => 'Invalid User'
            );
            $this->response($error,200);
	    }

		$quiz = $this->QuizModel->getQuizList($dateTime,30,$page,'');
		$msg = array(
			'status' => '1',
            'error' => 'SS1',
            'message' => 'Success', 
			'data' => $quiz
		);
		$this->response($msg,200);
	}


	function del_post()
	{	
		$param = [
            ['field' => 'number','label' => 'Mobile Number','rules' => 'trim|required|integer|exact_length[10]'],
	        ['field' => 'token','label' => 'Token','rules' => 'trim|required'],
	        ['field' => 'quizid','label' => 'quizid','rules' => 'trim|required'],
            ['field' => 'deviceid','label' => 'deviceid','rules' => 'trim|required']
	    ];   
		$post = $this->param($param);	
		
		$number = trim($post['number']);
		$token = trim($post['token']);
		$quizid = trim($post['quizid']);
        $deviceid = $post['deviceid'];

		$dateTime = date("Y-m-d H:i:s");

	    $customJWT = json_decode(_decryptIt($token),true);

	    if(!$customJWT){
	    	$error = array(
                'status'=> '0', 
                'error' => 'EX1',
                'message' => 'Invalid User'
            );
            $this->response($error,200);
	    }

		$quiz = $this->QuizModel->getQuizList($dateTime,'1','0',$quizid);
		if(!$quiz)
		{
			$error = array(
				'status' => '0',
				'message' => 'Error'
			);
			$this->response($error,200);
		}

		if($quiz[0]['status'] != 'active')
		{
			$error = array(
				'status' => '0',
				'message' => $quiz[0]['status'].' quiz can\'t be suspend'
			);
			$this->response($error,200);	
		}

		$this->QuizModel->delQuiz($quizid);
		
		$msg = array(
			'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => 'marked as suspend'
		);
		$this->response($msg,200);
	}

	function new_post()
	{		
		$param = [
            ['field' => 'number','label' => 'Mobile Number','rules' => 'trim|required|integer|exact_length[10]'],
	        ['field' => 'token','label' => 'Token','rules' => 'trim|required'],
	        ['field' => 'question','label' => 'question','rules' => 'trim|required|max_length[500]'],
	        ['field' => 'opt1','label' => 'opt1','rules' => 'trim|required|max_length[60]'],
	        ['field' => 'opt2','label' => 'opt2','rules' => 'trim|required|max_length[60]'],
	        ['field' => 'opt3','label' => 'opt3','rules' => 'trim|required|max_length[60]'],
	        ['field' => 'opt4','label' => 'opt4','rules' => 'trim|required|max_length[60]'],
	        ['field' => 'ans','label' => 'ans','rules' => 'trim|required|max_length[60]'],
            ['field' => 'deviceid','label' => 'deviceid','rules' => 'trim|required'],
			['field' => 'amount','label' => 'amount','rules' => 'trim|required']
	    ];
		$post = $this->param($param);

		
		$number = trim($post['number']);
		$token = trim($post['token']); 
		$question = trim($post['question']); 
		$opt1 = trim($post['opt1']); 
		$opt2 = trim($post['opt2']); 
		$opt3 = trim($post['opt3']); 
		$opt4 = trim($post['opt4']); 
		$ans = trim($post['ans']); 
		$dateTime = date("Y-m-d H:i:s");
        $deviceid = $post['deviceid'];
		$amount = $post['amount'];
	
	    $customJWT = json_decode(_decryptIt($token),true);

	    if(!$customJWT){
	    	$error = array(
                'status'=> '0', 
                'error' => 'EX1',
                'message' => 'Invalid User'
            );
            $this->response($error,200);
	    }

		$this->QuizModel->newQuiz($question,$opt1,$opt2,$opt3,$opt4,$ans,$amount,$dateTime,'');

		$quiz = $this->QuizModel->getQuizList($dateTime,30,0,'');
		$msg = array(
			'status' => '1',
            'error' => 'SS1',
            'message' => 'Success', 
			'data' => $quiz
		);
		$this->response($msg,200);
	}


//------------------------------------------------------End of File
}
