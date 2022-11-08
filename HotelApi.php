<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class HotelApi{
	
	//--------------------------staging
    private $URL = "http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/";
    private $key = "bgcdjmdtestkldh";

	private function send($request,$method){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->URL.$method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $request,
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/json"
        )
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if($err)
        {
            //print_r($err);
            return false;
        }
        else
            return $response;
    }

    function search($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$child,$childAge)
    {
    	$childList = '';

		if(!empty($child))
		{
         	$childList =',"Child": {
				"NumberOfChild": '.$child.',
				"childAge": ['.$childAge.']
			}';
	    }

        $ApiResponse = $this->findHotel($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$childList);
        $ApiResponse = json_decode($ApiResponse,True);
        return $ApiResponse;
    }

	private function findHotel($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$childList)
	{
		$request = '{
			"PageNo": 1,
			"City": "'.$key.'",
			"CheckInDate": "'.$checkInDate.'",
			"CheckOutDate": "'.$checkOutDate.'",
			"IsCorpFare": false,
			"MaxRange": 3000,
			"MinRange": 1000,
			"Rating": "3",
			"Nationality": "India",
			"TotalHotel": 5,
			"country": "India",
			"rooms": {
				"Count": '.$noOfRooms.',
				"Room": [{
					"NumberOfAdults": '.$adult.'
					'.$childList.'
		    }]},
			"currency": "INR",
			"Nights": 0,
			"Engine": 15,
			"EMTAuthentication": {
				"UserName": "HotelAPITest",
				"Password": "HotelAPITest",
				"AgentCode": 15,
				"IPAddress": "35.199.32.91",
				"SessionId": "GOI"
			},
			"isFareRecheck": false,
			"EMTCommonID": ""
		}';
		
	   $ApiResponse = $this->send($request,'HotelList');
       return $ApiResponse;
	}	



	function getProduct($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$child,$childAge,$eMTCommonId,$engine,$rateKey,$hotelID,$mealType,$roomType,$roomTypeCode,$rateCode)

    {
    	$childList = '';

		if(!empty($child))
		{
         	$childList =',"Child": {
				"NumberOfChild": '.$child.',
				"childAge": ['.$childAge.']
			}';
	    }

        $ApiResponse = $this->productDetails($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$childList,$eMTCommonId,$engine,$rateKey,$hotelID,$mealType,$roomType,$roomTypeCode,$rateCode);
        $ApiResponse = json_decode($ApiResponse,True);
        return $ApiResponse;
    }

	public function productDetails($key,$checkInDate,$checkOutDate,$noOfRooms,$adult,$childList,$eMTCommonId,$engine,$rateKey,$hotelID,$mealType,$roomType,$roomTypeCode,$rateCode)
	{
		$request = '{
			"CheckInDate": "'.$checkInDate.'",
			"CheckOutDate": "'.$checkOutDate.'",
			"City": "'.$key.'",
			"EMTAuthentication": {
				"UserName": "HotelAPITest",
				"Password": "HotelAPITest",
				"AgentCode": 15,
				"IPAddress": "35.199.32.91"
			},
			"EMTCommonID": "'.$eMTCommonId.'",
			"Engine": '.$engine.',
			"HotelChain": "",
			"HotelID": "'.$hotelID.'",
			"IncludeDetails": true, 
			"Options": [],
			"MealType": "'.$mealType.'",
			"Nationality": "India",
			"RateCode": "'.$rateCode.'",
			"RateKey": "'.$rateKey.'",
			"RoomType": "'.$roomType.'",
			"RoomTypeCode": "'.$roomTypeCode.'",
			"Rooms": {
				"Count": '.$noOfRooms.',
				"Room": [{
					"NumberOfAdults": '.$adult.'
					'.$childList.'
		    }]},
			"country": "India",
			"currency": "INR",
			"stringToken": ""
		}';
	
		$ApiResponse = $this->send($request,'ProductDetails');
       	return $ApiResponse;	
	}

	
	/*public function getAvailableRooms($data)
	{
		$request = '{
						"CheckInDate": "2020-04-10",
						"CheckOutDate": "2020-04-11",
						"City": "New Delhi",
						"EMTAuthentication": {
							"UserName": "HotelAPITest",
							"Password": "HotelAPITest",
							"AgentCode": 15,
							"IPAddress": "35.199.32.91",
							"SessionId": "GOI"
						},
						"EMTCommonID": "EMTHotel-1068476",
						"Engine": 23,
						"HotelChain": "",
						"HotelID": "867693",
						"IncludeDetails": true,
						"MealType": "Parking,Welcome drink,Free WiFi",
						"Nationality": "India",
						"RateCode": "",
						"RateKey": "4609281|1|INR|1465431|15cef9ef-4cd6-4b00-863e-9eb4ee51bdc1|657130975914061139|B2B",
						"RoomType": "Deluxe Room",
						"RoomTypeCode": "15cef9ef-4cd6-4b00-863e-9eb4ee51bdc1",
						"Rooms": {
							"Count": 1,
							"Room": [
								{
									"Child": {
										"NumberOfChild": 2,
										"childAge": ["3,4"]
									},
									"NumberOfAdults": 1
								}
							]
						},
						"country": "India",
						"currency": "INR",
						"stringToken": ""
					}';
					
		//echo $request; die; 
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/AvailableRooms",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $request,
								CURLOPT_HTTPHEADER => array(
				//"accept: application/json",
				"content-type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		
		
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		 //echo $response;
		  $res = json_decode($response);
		  return $res;
		}
	}
	
	
	
	
	
	
	public function hotelBooking($post)
	{
		$passenger = '{
								"AdultDetails":[{
									"Age":28,
									"PaxType":0,
									"Prefix":"",
									"firstName":"Kumar",
									"lastName":"Deepak"
								}],
								"ChildDetails":[{
									"Age":5,
									"PaxType":0,
									"Prefix":"",
									"firstName":"Ankit",
									"lastName":"Singh"
								}],
								"MealType":"",
								"TotalAdult":1,
								"TotalChild":1,
								"rateCode":"'.$post['RateCode'].'",								
								"rateKey":"'.$post['RateKey'].'",
								"roomType":"'.$post['RoomType'].'",
								"roomTypeCode":"'.$post['RoomTypeCode'].'",
								"smokingPreference":""
							}';
		$request = '{
						"AddressDetail":{
							"address":"'.$post['Address'].'",
							"city":"'.$post['MyCity'].'",
							"countryCode":"IND",
							"postalCode":"'.$post['PinCode'].'",
							"stateCode":"'.$post['StateCode'].'"
						},
						"AppVersion":"",
						"Authentication": {
							"UserName": "HotelAPITest",
							"Password": "HotelAPITest",
							"AgentCode": 15,
							"IPAddress": "35.199.32.91",
							"SessionId": "GOI"
						},
						"BookingID":"",
						"CancellationPolicy":"",
						"City":"",
						"Country":"",
						"CouponCode":"",
						"CouponValue":12678967.543233,
						"CurrencyCode":"",
						"EMTCommonID":"'.$post['EMTCommonId'].'",
						"EngineID":'.$post['Engine'].',
						"GSTDetails":{
							"CustomerAddress":"",
							"CustomerName":"",
							"CustomerState":"",
							"GSTCity":"",
							"GSTCompanyAddres":"",
							"GSTCompanyEmailId":"",
							"GSTCompanyName":"",
							"GSTNumber":"",
							"GSTPhoneISD":"",
							"GSTPhoneNumber":"",
							"GSTPinCode":"",
							"GSTState":""
						},
						"HotelChain":"",
						"HotelReservationInfo":{
							"BankCountryCode":"IND",
							"BankName":"",
							"Email":"",
							"ExpirationYear":"",
							"FullCardType":"",
							"SecondryEmailId":"",
							"Title":"",
							"cardNumber":"",
							"cardType":"",
							"creditCardIdentifier":"",
							"expirationMonth":"",
							"firstName":"",
							"homePhone":"",
							"lastName":"",
							"workPhone":""
						},
						"MealType":"'.$post['MealType'].'",
						"Name":"'.$post['MealType'].'",
						"Nationality":"",
						"NationalityCode":"",
						"Nights":2147483647,
						"Rooms":{
							"Count":'.$post['NoOfRooms'].',
							"Room":['.$post['Passenger'].']
						},
						"SearchKey":"",
						"Tax":"",
						"TransactionRefNo":"",
						"Transactionid":'.rand(1000,9999).',
						"UniqueID":"",
						"chargeableRate":"'.$post['Price'].'",
						"checkInDate":"'.$post['CheckInDate'].'",
						"checkOutDate":"'.$post['CheckOutDate'].'",
						"hotelID":"'.$post['HotelID'].'",
						"rateCode":"'.$post['RateCode'].'",						
						"rateKey":"'.$post['RateKey'].'",
						"roomTypeCode":"'.$post['RoomTypeCode'].'",
						"supplierType":""
					}';
		//echo $request; //die;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/HotelBooking",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $request,
								CURLOPT_HTTPHEADER => array(
				//"accept: application/json",
				"content-type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		
		
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		 //echo $response;
		  $res = json_decode($response);
		  return $res;
		}
	}*/
	
	
	
	
		
		
}