<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require_once ('include/HotelApi.php');
require_once (APPPATH .'/controllers/prod/SmsinstaApi.php');
require_once (APPPATH .'/controllers/prod/WalletApi.php');

class Hotel extends REST_Controller {
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Calcutta");
        $this->load->helper(array('form', 'url','string','email','wallet')); 
        $this->load->library(array('form_validation'));
        $this->load->model(array('LoginModel','WalletModel'));
	}
		
	public function searchHotel_post()
	{
		$post = $this->security->xss_clean($this->input->post());
        if(!isset($post['skey']) || $post['skey'] != WSKEY)
        {
            $error = array(
                'status' => '0',
                'error' => 'AE1',
                'message' => 'Authentications Failed'
            );
            $this->response($error, 200);           
        }
		
		$param = [
			['field' => 'key','label' => 'City Name','rules' => 'trim|required'],
			['field' => 'checkInDate','label' => 'Check In Date','rules' => 'trim|required'],
			['field' => 'checkOutDate','label' => 'Check Out Date','rules' => 'trim|required'],
			['field' => 'noOfRooms','label' => 'Number Of Rooms','rules' => 'trim|required|integer'],
			['field' => 'adult','label' => 'Adult','rules' => 'trim|required|integer'],
			['field' => 'child','label' => 'Child','rules' => 'trim|integer'],
			['field' => 'childAge','label' => 'Child Age','rules' => 'trim']
		];
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

        $key = trim($post['key']);
        // $checkInDate = date_create(trim($post['checkInDate']));
        // $inDate = date_format($checkInDate,"Y/m/d");

        // $checkOutDate = date_create(trim($post['checkOutDate']));
        // $outDate = date_format($checkOutDate,"Y/m/d");


        $checkInDate = trim($post['checkInDate']);
        $checkOutDate = trim($post['checkOutDate']);
        $noOfRooms = trim($post['noOfRooms']);
        $adult = trim($post['adult']);

        (isset($post['child'])? $child = trim($post['child']) : $child = '');
        (isset($post['childAge'])? $childAge = trim($post['childAge']) : $childAge = '');
     
		if(!empty($child))
		{ 
			if(!(!empty($childAge) && count(explode(',', $childAge)) == $child))
			{
				$error = array(
	                'status' => '0',
	                'error' => 'AE2',
	                'message' => 'Invalid Request'
	            );
	            $this->response($error, 200);	
			}
		}


		$hotel = new HotelApi();
		$hotellist = $hotel->search($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$child,$childAge);

  		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $hotellist
        );
        $this->response($msg,200);
	}



	public function preCheck_post()
	{
		$post = $this->security->xss_clean($this->input->post());
        if(!isset($post['skey']) || $post['skey'] != WSKEY)
        {
            $error = array(
                'status' => '0',
                'error' => 'AE1',
                'message' => 'Authentications Failed'
            );
            $this->response($error, 200);           
        }
		
		$param = [
			['field' => 'key','label' => 'City Name','rules' => 'trim|required'],
			['field' => 'checkInDate','label' => 'Check In Date','rules' => 'trim|required'],
			['field' => 'checkOutDate','label' => 'Check Out Date','rules' => 'trim|required'],
			['field' => 'noOfRooms','label' => 'Number Of Rooms','rules' => 'trim|required|integer'],
			['field' => 'adult','label' => 'Adult','rules' => 'trim|required|integer'],
			['field' => 'child','label' => 'Child','rules' => 'trim'],
			['field' => 'childAge','label' => 'Child Age','rules' => 'trim'],
			
			['field' => 'eMTCommonId','label' => 'EMTCommonId','rules' => 'trim|required'],
			['field' => 'engine','label' => 'Engine Id','rules' => 'trim|required'],
			['field' => 'rateKey','label' => 'RateKey','rules' => 'trim|required'],
			['field' => 'hotelID','label' => 'Hotel ID','rules' => 'trim|required'],
			['field' => 'mealType','label' => 'Meal Type','rules' => 'trim|required'],
			['field' => 'roomType','label' => 'Room Type','rules' => 'trim|required'],
			['field' => 'roomTypeCode','label' => 'Room Type Code','rules' => 'trim|required'],
			['field' => 'rateCode','label' => 'Rate Code','rules' => 'trim|required']
		];
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
        
 
     	$key = trim($post['key']);
     	$checkInDate = trim($post['checkInDate']);
        $checkOutDate = trim($post['checkOutDate']);
        $noOfRooms = trim($post['noOfRooms']);
        $adult = trim($post['adult']);


        $eMTCommonId = trim($post['eMTCommonId']);
        $engine = trim($post['engine']);
        $rateKey = trim($post['rateKey']);
        $hotelID = trim($post['hotelID']);
        $mealType = trim($post['mealType']);
        $roomType = trim($post['roomType']);
        $roomTypeCode = trim($post['roomTypeCode']);
        $rateCode = trim($post['rateCode']);


        (isset($post['child'])? $child = trim($post['child']) : $child = '');
        (isset($post['childAge'])? $childAge = trim($post['childAge']) : $childAge = '');
     
		if(!empty($child))
		{ 
			if(!(!empty($childAge) && count(explode(',', $childAge)) == $child))
			{
				$error = array(
	                'status' => '0',
	                'error' => 'AE2',
	                'message' => 'Invalid Request'
	            );
	            $this->response($error, 200);	
			}
		}


		$hotel = new HotelApi();
		$product = $hotel->getProduct($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$child,$childAge,$eMTCommonId,$engine,$rateKey,$hotelID,$mealType,$roomType,$roomTypeCode,$rateCode);

  		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $product
        );
        $this->response($msg,200);
	}
	

	public function bookHotel_post()
	{
		$post = $this->security->xss_clean($this->input->post());
        if(!isset($post['skey']) || $post['skey'] != WSKEY)
        {
            $error = array(
                'status' => '0',
                'error' => 'AE1',
                'message' => 'Authentications Failed'
            );
            $this->response($error, 200);           
        }
		
		$param = [
			['field' => 'checkInDate','label' => 'Check In Date','rules' => 'trim|required'],
			['field' => 'checkOutDate','label' => 'Check Out Date','rules' => 'trim|required'],
			['field' => 'noOfRooms','label' => 'Number Of Rooms','rules' => 'trim|required|integer'],

			['field' => 'adult','label' => 'Adult','rules' => 'trim|required|integer'],
			['field' => 'adultAge','label' => 'adultAge','rules' => 'trim|required'],
			['field' => 'adultFName','label' => 'adultFName','rules' => 'trim|required'],
			['field' => 'adultLName','label' => 'adultLName','rules' => 'trim|required'],
			['field' => 'child','label' => 'Child','rules' => 'trim|required|integer'],
			['field' => 'childAge','label' => 'childAge','rules' => 'trim|required'],
			['field' => 'childFName','label' => 'childFName','rules' => 'trim|required'],
			['field' => 'childLName','label' => 'childLName','rules' => 'trim|required'],
			
			['field' => 'eMTCommonId','label' => 'EMTCommonId','rules' => 'trim|required'],
			['field' => 'engine','label' => 'Engine Id','rules' => 'trim|required'],
			['field' => 'rateKey','label' => 'RateKey','rules' => 'trim|required'],
			['field' => 'hotelID','label' => 'Hotel ID','rules' => 'trim|required'],
			['field' => 'mealType','label' => 'Meal Type','rules' => 'trim|required'],
			['field' => 'roomType','label' => 'Room Type','rules' => 'trim|required'],
			['field' => 'roomTypeCode','label' => 'Room Type Code','rules' => 'trim|required'],
			['field' => 'rateCode','label' => 'Rate Code','rules' => 'trim|required'],

			['field' => 'address','label' => 'address','rules' => 'trim|required'],
			['field' => 'city','label' => 'city','rules' => 'trim|required'],
			['field' => 'pinCode','label' => 'pinCode','rules' => 'trim|required'],
			['field' => 'stateCode','label' => 'stateCode','rules' => 'trim|required'],
			['field' => 'price','label' => 'price','rules' => 'trim|required']
		];
		$this->form_validation->set_data($post);
        $this->form_validation->set_rules($param);
        
        if($this->form_validation->run()==FALSE)
        {
    		$error = array(
                'status' => '0',
                'error' => 'AE1',
                'message' => 'Invalid Request'.validation_errors()
            );
            $this->response($error, 200);
        }

     	$checkInDate = trim($post['checkInDate']);
        $checkOutDate = trim($post['checkOutDate']);
        $noOfRooms = trim($post['noOfRooms']);
        $adult = trim($post['adult']);
        $adultAge = trim($post['adultAge']);
        $adultFName = trim($post['adultFName']);
        $adultLName = trim($post['adultLName']);

        $eMTCommonId = trim($post['eMTCommonId']);
        $engine = trim($post['engine']);
        $rateKey = trim($post['rateKey']);
        $hotelID = trim($post['hotelID']);
        $mealType = trim($post['mealType']);
        $roomType = trim($post['roomType']);
        $roomTypeCode = trim($post['roomTypeCode']);
        $rateCode = trim($post['rateCode']);

        $address = trim($post['address']);
        $city = trim($post['city']);
        $pinCode = trim($post['pinCode']);
        $stateCode = trim($post['stateCode']);
        $price = trim($post['price']);

        (isset($post['child'])? $child = trim($post['child']) : $child = '');
        (isset($post['childAge'])? $childAge = trim($post['childAge']) : $childAge = '');
        (isset($post['childFName'])? $childFName = trim($post['childFName']) : $childFName = '');
        (isset($post['childLName'])? $childLName = trim($post['childLName']) : $childLName = '');

        $transactionId = "666666666666";
     

		$hotel = new HotelApi();
		//$booked = $hotel->booking($checkInDate,$checkOutDate,$noOfRooms,$eMTCommonId,$engine,$rateKey,$hotelID,$mealType,$roomType,$roomTypeCode,$rateCode,$address,$city,$pinCode,$stateCode,$transactionId,$adult,$adultAge,$adultFName,$adultLName,$child,$childAge,$childFName,$childLName,$price);
        $booked = $hotel->getProduct($checkInDate,$checkOutDate,$noOfRooms,$eMTCommonId,$engine,$rateKey,$hotelID,$mealType,$roomType,$roomTypeCode,$rateCode,$address,$city,$pinCode,$stateCode,$transactionId,$adult,$adultAge,$adultFName,$adultLName,$child,$childAge,$childFName,$childLName,$price);

  		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $booked
        );
        $this->response($msg,200);
	}

    //Hotel Detail API

    public function hotelInfo_post(){
        $post = $this->security->xss_clean($this->input->post());
        if(!isset($post['skey']) || $post['skey'] != WSKEY)
        {
            $error = array(
                'status' => '0',
                'error' => 'AE1',
                'message' => 'Authentications Failed'
            );
            $this->response($error, 200);           
        }
        $param = [
            ['field' => 'hotelId','label' => 'Hotel Id','rules' => 'trim|required'],
            ['field' => 'emtComonId','label' => 'Emt Common Id','rules' => 'trim|required']            
	    ];
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
            $hotelID = trim($post['hotelId']);
            $EMTCOMMONID = trim($post['emtComonId']);
                      
            $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/HotelInfo/$hotelID/15/$EMTCOMMONID/HotelAPITest/HotelAPITest/15",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(
            "accept: text/json",
            "content-type: text/json"
        )
        
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response_new = "";
        if (!$err) {
       
            $response_new = json_decode($response,True);
            
        }

                   

  		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $response_new
        );
    
        $this->response($msg,200);     
    }

/*
	public function availableRooms_get()
	{
		$hotel_raw = new Hotel_Raw();
		$data='';
		$availablerooms = $hotel_raw->getAvailableRooms($data);
		print_r($availablerooms); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $oneway_result);
	    $this->response($res,200);
	}
	
	public function productdetails_get()
	{		
		$hotel_raw = new Hotel_Raw();
		$data='';
		$productdetails = $hotel_raw->getProductDetails($data);
		print_r($productdetails); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $productdetails);
	    $this->response($res,200);
	}
	
	
	
	public function bookingraw_get()
	{
		$hotel_raw = new Hotel_Raw();
		$data='';
		$booking = $hotel_raw->hotelBooking($data);
		print_r($booking); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $booking);
	    $this->response($res,200);
	}
	
	public function booking_post()
	{
		$post = $this->input->post();
		/*
		$post['ChildAge'][0] = 3;
		$post['ChildAge'][1] = 4;
        */		
        //print_r($post);		die;
		/*if(!isset($post['skey']) || $post['skey'] != '30b81092491d81c5e90990bb06d875498be3b83f8eb9d432d458324e5b4731225e6600cd27ae6e')
		{
			$error = array('status' =>'0','message' =>'Authentications Failed','Description' => 'Invalid Request');
            //$this->response($error, 200);			
		}
		
		$param = [
                   ['field' => 'City','label' => 'City Name','rules' => 'trim|required'],
                   ['field' => 'CheckInDate','label' => 'Check In Date','rules' => 'trim|required'],
                   ['field' => 'CheckOutDate','label' => 'Check Out Date','rules' => 'trim|required'],
                   ['field' => 'NoOfRooms','label' => 'Number Of Rooms','rules' => 'trim|required'],
                   ['field' => 'Adult','label' => 'Adult','rules' => 'trim|required'],
                   ['field' => 'Child','label' => 'Child','rules' => 'trim'],
                   ['field' => 'ChildAge','label' => 'Child Age','rules' => 'trim'],
                   ['field' => 'EMTCommonId','label' => 'EMTCommonId','rules' => 'trim|required'],
                   ['field' => 'Engine','label' => 'Child Age','rules' => 'trim|required'],
                   ['field' => 'RateKey','label' => 'Engine id','rules' => 'trim|required'],
                   ['field' => 'HotelID','label' => 'Hotel ID','rules' => 'trim|required'],
                   ['field' => 'MealType','label' => 'Meal Type','rules' => 'trim|required'],
                   ['field' => 'RoomType','label' => 'Room Type','rules' => 'trim|required'],
                   ['field' => 'RoomTypeCode','label' => 'Room Type Code','rules' => 'trim|required'],
                   ['field' => 'RateCode','label' => 'Rate Code','rules' => 'trim'],
                   ['field' => 'Address','label' => 'Address','rules' => 'trim|required'],
                   ['field' => 'MyCity','label' => 'Your City','rules' => 'trim|required'],
                   ['field' => 'PinCode','label' => 'PinCode','rules' => 'trim|required'],
                   ['field' => 'StateCode','label' => 'State Code','rules' => 'trim|required'],
                   ['field' => 'Price','label' => 'Price','rules' => 'trim|required'],
                   ['field' => 'Passenger','label' => 'Passenger Details','rules' => 'trim|required']
                   
                 ];
		$this->form_validation->set_data($post);
        $this->form_validation->set_rules($param);
        
        if($this->form_validation->run()==FALSE)
        {
            $error = array('message' => 'Invalid Form Data','Description' => validation_errors());
            $this->response($error,200);
        }
		$hotel_raw = new Hotel_Raw();
		$booking = $hotel_raw->hotelBooking($post);
		//print_r($booking); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $booking);
	    $this->response($res,200);
	}
	
	
	
	public function searchhotel_get()
	{
		        
        $post['City'] = 'New Delhi';
        $post['CheckInDate'] = '2020/10/15';
        $post['CheckOutDate'] = '2020/10/20';
        $post['NoOfRooms'] = '1';
        $post['Adult'] = '1';
        $post['Child'] = '1';
        $post['ChildAge'] = '4';
		
		$hotel_raw = new Hotel_Raw();
		$hotellist = $hotel_raw->hotel_search($post);
		//print_r($hotellist); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $hotellist);
		$this->response($res,200);
		
	}
	
	public function precheck_get()
	{		
		$post['City'] = 'New Delhi';
        $post['CheckInDate'] = '2020/05/19';
        $post['CheckOutDate'] = '2020/05/20';
        $post['NoOfRooms'] = '2';
        $post['Adult'] = '1';
        $post['Child'] = '2';
        
		$post['EMTCommonId'] = 'EMTHotel-1049691';
        $post['Engine'] = '23';
        $post['RateKey'] = '3509387|1|INR|494838|10c8e28b-8256-8862-bb48-fcfe16bab231|655231228893191002|B2B';
        $post['HotelID'] = '262889';
        $post['MealType'] = 'Room Only';
        $post['RoomType'] = 'Deluxe Room';
        $post['RoomTypeCode'] = '10c8e28b-8256-8862-bb48-fcfe16bab231';
        $post['RateCode'] = '';
		
		
		$hotel_raw = new Hotel_Raw();
		$data='';
		$productdetails = $hotel_raw->getProductDetails($post);
		//print_r($productdetails); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $productdetails);
	    $this->response($res,200);
		
	}
	
	
	public function testbooking_get()
	{
		
		$post['City'] = 'New Delhi';
        $post['CheckInDate'] = '2020/05/19';
        $post['CheckOutDate'] = '2020/05/20';
        $post['NoOfRooms'] = '2';
        $post['Adult'] = '1';
        $post['Child'] = '2';
        
		$post['EMTCommonId'] = 'EMTHotel-1049691';
        $post['Engine'] = '23';
        $post['RateKey'] = '3509387|1|INR|494838|10c8e28b-8256-8862-bb48-fcfe16bab231|655231228893191002|B2B';
        $post['HotelID'] = '262889';
        $post['MealType'] = 'Room Only';
        $post['RoomType'] = 'Deluxe Room';
        $post['RoomTypeCode'] = '10c8e28b-8256-8862-bb48-fcfe16bab231';
        $post['RateCode'] = '';
        
		$post['Address'] = 'Vasant Vihar';
        $post['MyCity'] = 'Patna';
        $post['PinCode'] = '805124';
        $post['StateCode'] = 'PAT';
        $post['Price'] = '1050';
		
		
		$hotel_raw = new Hotel_Raw();
		$booking = $hotel_raw->hotelBooking($post);
		//print_r($booking); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $booking);
	    $this->response($res,200);
	}
	*/
	
	
	
	
	
}