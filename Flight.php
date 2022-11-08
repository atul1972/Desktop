<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require_once ('include/FlightApi.php');
require_once (APPPATH .'/controllers/prod/SmsinstaApi.php');
require_once (APPPATH .'/controllers/prod/WalletApi.php');

class Flight extends REST_Controller {
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Calcutta");
        $this->load->helper(array('form', 'url','string','email','wallet')); 
        $this->load->library(array('form_validation'));
        $this->load->model(array('LoginModel','WalletModel','Api_models'));
	}

	
	public function airportlist_post()
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


		$list = array(    
		     array('City' => 'Patna', 'Citycode' => 'PAT','Country' => 'India',"AirportName"=>"Lok Nayak Jayaprakash Airport") ,
		     array('City' => 'New Delhi',"Citycode"=>"DEL","Country"=>"India","AirportName"=>"Indira Gandhi International Airport"),
		     array('City' => 'Mumbai',"Citycode"=>"BOM","Country"=>"India","AirportName"=>"Chhatrapati Shivaji International Airport"),
		     array('City' => 'Banglore',"Citycode"=>"BLR","Country"=>"India","AirportName"=>"Kempegowda International Airport"),		
		     array('City' => 'Lisbon',"Citycode"=>"LIS","Country"=>"","AirportName"=>"Lisbon International Airport"),		
		     array('City' => 'Paris',"Citycode"=>"CDG","Country"=>"","AirportName"=>"Paris International Airport")		
		 );

		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $list
        );
        $this->response($msg,200);
	}
	public function airports_post()
	{
		$post = $this->input->post();
		if(!isset($post['skey']) || $post['skey'] != WSKEY)
		{
			$error = array('status' =>'0','message' =>'Authentications Failed','Description' => 'Invalid Request');
            $this->response($error, 200);			
		}
		$rank = $this->Api_models->getAirportList();
		//print_r($rank);
		$msg = array('status' => '1','message' => 'success','data' => $rank	);
		$this->response($msg,200);
		
	}

	
	public function pricer_post()
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
   			['field' => 'segment','label' => 'Segment Details','rules' => 'trim|required'],
			['field' => 'travelDate','label' => 'Travel Date','rules' => 'trim|required'],
			['field' => 'origin','label' => 'Origin','rules' => 'trim|required'],
			['field' => 'destination','label' => 'Destination','rules' => 'trim|required'],
            ['field' => 'cabin','label' => 'cabin','rules' => 'trim|required'],
			['field' => 'tripType','label' => 'Trip Type','rules' => 'trim|required'],
			['field' => 'traceId','label' => 'traceId','rules' => 'trim|required'],
			['field' => 'adult','label' => 'Adult','rules' => 'trim|required'],
			['field' => 'child','label' => 'Child','rules' => 'trim|required'],
			['field' => 'infant','label' => 'Infant','rules' => 'trim|required'],
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

        $travelDate = trim($post['travelDate']);
		$origin = trim($post['origin']);
		$destination = trim($post['destination']);
		$adult = trim($post['adult']);
		$child = trim($post['child']);
		$infant = trim($post['infant']);
		$tripType = trim($post['tripType']);
		$traceId = trim($post['traceId']);
		$segment = trim($post['segment']);


		$flight  = new FlightApi();
		$check = $flight-> price($adult,$child,$travelDate,$origin,$destination,$infant,$tripType,$traceId,$segment);

		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $check
        );
        $this->response($msg,200);
	}
	
	public function booking_post()
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
		   ['field' => 'segment','label' => 'Segment Details','rules' => 'trim|required'],
           ['field' => 'travelDate','label' => 'Travel Date','rules' => 'trim|required'],
           ['field' => 'origin','label' => 'Origin','rules' => 'trim|required'],
           ['field' => 'destination','label' => 'Destination','rules' => 'trim|required'],
           ['field' => 'cabin','label' => 'cabin','rules' => 'trim|required'],
           ['field' => 'tripType','label' => 'Trip Type','rules' => 'trim|required'],
           ['field' => 'traceId','label' => 'Trace Id','rules' => 'trim|required'],
           ['field' => 'amount','label' => 'Amount','rules' => 'trim|required'],
           ['field' => 'adultT','label' => 'Traveller','rules' => 'trim|required'],
           ['field' => 'childT','label' => 'Traveller','rules' => 'trim'],
           ['field' => 'infantT','label' => 'Traveller','rules' => 'trim'],
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

        $travelDate = trim($post['travelDate']);
		$origin = trim($post['origin']);
		$destination = trim($post['destination']);
		$adultT = trim($post['adultT']);
		$tripType = trim($post['tripType']);		
		$traceId = trim($post['traceId']);		
		$cabin = trim($post['cabin']);		
		$segment = trim($post['segment']);
		$amount = trim($post['amount']);

		(isset($post['childT']) ? $childT = trim($post['childT']) : $childT = '');
		(isset($post['infantT']) ? $infantT = trim($post['infantT']) : $infantT = '');

        $transactionId = '44444444444444d99';

		$flight  = new FlightApi();
		$book = $flight->booking($segment,$amount,$travelDate,$origin,$destination,$cabin,$tripType,$traceId,$transactionId,$adultT,$childT,$infantT);

		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $book
        );
        $this->response($msg,200);
	}
	
	public function search_post()
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
           ['field' => 'travelDate','label' => 'Travel Date','rules' => 'trim|required'],
           ['field' => 'origin','label' => 'Origin','rules' => 'trim|required'],
           ['field' => 'destination','label' => 'Destination','rules' => 'trim|required'],
           ['field' => 'cabin','label' => 'cabin','rules' => 'trim|required'],
           ['field' => 'adult','label' => 'Adult','rules' => 'trim|required'],
           ['field' => 'child','label' => 'Child','rules' => 'trim|required'],
           ['field' => 'infant','label' => 'Infant','rules' => 'trim|required'],
           ['field' => 'tripType','label' => 'Trip Type','rules' => 'trim|required']
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

        $travelDate = trim($post['travelDate']);
		$origin = trim($post['origin']);
		$destination = trim($post['destination']);
		$adult = trim($post['adult']);
		$child = trim($post['child']);
		$infant = trim($post['infant']);
		$tripType = trim($post['tripType']);





		 //    $data['TravelDate'] = '2022-02-20';
			// $data['ReturnDate'] = '2022-02-22';
			// $data['Origin'] = 'DEL';
			// $data['Destination'] = 'BOM';
			// $data['Origin2'] = 'BOM';
			// $data['Destination2'] = 'DEL';
			// $data['Adult'] = 1;
			// $data['Child'] = 0;
			// $data['Infant'] = 0;
			// $data['TripType'] = 1;



		$flight  = new FlightApi();
		$flightlist = $flight->search($travelDate,$origin,$destination,$adult,$child,$infant,$tripType);

		$msg = array(
            'status' => '1',
            'error' => 'SS1',
            'message' => 'Success',
            'data' => $flightlist
        );
        $this->response($msg,200);
	}
	
	/*
	public function testpricerecheck_get()
	{
		    $data['TravelDate'] = '2022-02-20';
			$data['ReturnDate'] = '2022-02-22';
			$data['Origin'] = 'DEL';
			$data['Destination'] = 'BOM';
			$data['TripType'] = 1;
			$data['Adult'] = 1;
			$data['Child'] = 0;
			$data['Infant'] = 0;
		    $data['Traveller'] = '{
								"AdultTraveller": [{
									"CountryCode": "IN",
									"DOB": "",
									"EmailAddress": "mangal.pankaj5@gmail.com",
									"FirstName": "Pankaj",
									"FrequentFlierNumber": "",
									"Gender": "",
									"LastName": "Mangal",
									"MiddleName": "",
									"MobileNumber": "8920547478",
									"Nationality": "IN",
									"PassportExpiryDate": "",
									"PassportNo": "",
									"ResidentCountry": "",
									"Title": "",
									"expanded": false,
									"type": "Adult 1"
								}],
								"ChildTraveller": [],
								"InfantTraveller": []
							}';
		    $data['Segment'] = '
								{
				"BondType": "OutBound",
				"Bonds": [{
					"BoundType": "OutBound",
					"IsBaggageFare": false,
					"IsSSR": false,
					"ItineraryKey": null,
					"JourneyTime": "02h 10m",
					"Legs": [{
						"AircraftCode": "",
						"AircraftType": "320",
						"AirlineName": "UK",
						"AirlinePnr": null,
						"ArrivalDate": "Fri-12Jun2020",
						"ArrivalTerminal": "2",
						"ArrivalTime": "11:40",
						"AvailableSeat": "9",
						"BaggageUnit": "KG",
						"BaggageWeight": "15",
						"BookSeat": null,
						"BoundType": "OutBound",
						"Cabin": "Economy",
						"CabinClasses": null,
						"Capacity": 0,
						"CarrierCode": "UK",
						"CurrencyCode": "INR",
						"DepartureDate": "Fri-12Jun2020",
						"DepartureTerminal": "3",
						"DepartureTime": "09:30",
						"Destination": "BOM",
						"Duration": "02h 10m",
						"FareBasisCode": "QL1PYS",
						"FareClassOfService": "Q",
						"FareRulesKey": null,
						"FlightDesignator": null,
						"FlightDetailRefKey": "",
						"FlightName": "Vistara",
						"FlightNumber": "927",
						"GDSPnr": null,
						"Group": "0",
						"IsConnecting": false,
						"IsSeatOpen": false,
						"LayoverArrDT": null,
						"LayoverAt": null,
						"LayoverDepDT": null,
						"LayoverDuration": null,
						"NumberOfStops": "0",
						"OperatedBy": null,
						"Origin": "DEL",
						"ProviderCode": "1G!`320`2`9`KG`15`Economy``UK`3`0`False`0``````02h 10m`QL1PYS`Q",
						"Remarks": "",
						"SSRDetails": null,
						"Sold": 0,
						"Status": null
					}],
					"addOnDetail": null
				}],
				"CurrencyCode": "INR",
				"Deeplink": "",
				"EngineID": 7,
				"Fare": {
					"BasicFare": 5214,
					"BrandKeys": null,
					"ExchangeRate": 0,
					"OfferedFare": 0,
					"PaxFares": [{
						"AirlinePnr": null,
						"BaggageUnit": "KG",
						"BaggageWeight": "15",
						"BaseTransactionAmount": 0,
						"BasicFare": 5214,
						"Branded": null,
						"CancelPenalty": 3000,
						"Cashback": 0,
						"ChangePenalty": 2500,
						"Commission": 0,
						"DFValue": "0",
						"EquivCurrencyCode": "INR",
						"Fare": [{
							"Amount": 267,
							"ChargeCode": "K3.",
							"ChargeType": null
						}, {
							"Amount": 177,
							"ChargeCode": "P2.",
							"ChargeType": null
						}, {
							"Amount": 91,
							"ChargeCode": "PSF",
							"ChargeType": null
						}, {
							"Amount": 130,
							"ChargeCode": "YR",
							"ChargeType": null
						}],
						"FareBasisCode": "QL1PYS",
						"FareInfoKey": null,
						"FareInfoValue": null,
						"GDSPnr": null,
						"IsZeroCancellation": false,
						"MarkUP": 0,
						"PaxType": 0,
						"Refundable": true,
						"STF": 0,
						"ServiceFee": 0,
						"TDS": 0,
						"TotalFare": 5879,
						"TotalTax": 665,
						"TransactionAmount": 0,
						"TransactionFee": 0,
						"ZeroCancellationCharge": 100,
						"ZeroCancellationValidity": "28"
					}],
					"SeatCharge": 0,
					"TotalFareWithOutMarkUp": 5879,
					"TotalTaxWithOutMarkUp": 665
				},
				"FareIndicator": 0,
				"FareRule": "",
				"Fares": null,
				"IsBaggageFare": false,
				"IsBrandAvailable": false,
				"IsBranded": false,
				"IsCache": true,
				"IsHoldBooking": false,
				"IsInternational": false,
				"IsRoundTrip": false,
				"IsSegmentChanged": false,
				"IsSpecial": false,
				"IsSpecialId": false,
				"ItineraryKey": "0h4JDh8rNi7BZDG6L5Q78P9Fqnb27JiPE7m6A48QhDo=",
				"JourneyIndex": 0,
				"MemoryCreationTime": "\/Date(1591782431787+0530)\/",
				"NearByAirport": false,
				"PaxSSRs": null,
				"PromoCode": null,
				"Remark": null,
				"SSDetails": "Free Meals",
				"SSRDetails": null,
				"SearchId": "114,ADT:BF=5214 Tax=665,CHD:BF=0 Tax=0,INF:BF=0 Tax=0,DFValue=0,APFV=5879`665`5214`3000.0`2500.0`0`0`QL1PYS``KG`15`True`0`0`0`0`,CPFV=,IPFV=,ATB=`K3.:267`P2.:177`PSF:91`YR:130,CTB=,ITB=,TBA=5214,TTA=665,TFA=5879",
				"Sessionfilepath": null,
				"VisaInfo": null,
				"ZCStatus": false,
				"description": null
			}';
			
		$flight_raw = new Flight_Raw();
		$pricerecheck = $flight_raw->_pricerecheck($data);
		//print_r($pricerecheck); die;
		$res = array('status' => 1, 'message' => 'success', 'data' => $pricerecheck);
	    $this->response($res,200);
	}
	
	public function testbooking_get()
	{
		$flight_raw = new Flight_Raw();
		$post['Segment'] = '{
	"BondType": "OutBound",
	"Bonds": [{
		"BoundType": "OutBound",
		"IsBaggageFare": "false",
		"IsSSR": "false",
		"ItineraryKey": "IY8Y5uF2M9EKQh3DWSNY08U3T2Q0ldkhoZOFysq8vPE\u003d",
		"JourneyTime": "02h 05m",
		"Legs": [{
			"AircraftCode": "SG",
			"AircraftType": "737",
			"AirlineName": "SG",
			"ArrivalDate": "Mon-06Apr2020",
			"ArrivalTerminal": "2",
			"ArrivalTime": "10:35",
			"BaggageUnit": "KG",
			"BaggageWeight": "15",
			"BoundType": "OutBound",
			"Cabin": "Economy",
			"Capacity": "0",
			"CarrierCode": "SG",
			"CurrencyCode": "INR",
			"DepartureDate": "Mon-06Apr2020",
			"DepartureTerminal": "3",
			"DepartureTime": "08:30",
			"Destination": "BOM",
			"Duration": "02h 05m",
			"FareBasisCode": "ASAVER",
			"FareClassOfService": "A",
			"FlightDetailRefKey": "SG~8723~ ~~DEL~03/30/2020 08:30~BOM~03/30/2020 10:35~~",
			"FlightName": "SpiceJet",
			"FlightNumber": "8723",
			"Group": "0",
			"IsConnecting": "false",
			"IsSeatOpen": "false",
			"NumberOfStops": "0",
			"Origin": "DEL",
			"ProviderCode": "SG`8723` ``DEL`03/30/2020 08:30`BOM`03/30/2020 10:35``!0`A` `SG`ASAVER`1922``0`17``X!!SG`737`2``KG`15`Economy``SG`3`0`False`0``````02h 05m`ASAVER`A",
			"Sold": "0"
		}]
	}],
	"CurrencyCode": "INR",
	"Deeplink": "",
	"EngineID": "1",
	"Fare": {
		"BasicFare": "6800",
		"ExchangeRate": "0",
		"OfferedFare": "0",
		"PaxFares": [{
			"BaggageUnit": "KG",
			"BaggageWeight": "15",
			"BaseTransactionAmount": "0",
			"BasicFare": "3400",
			"CancelPenalty": "3500",
			"Cashback": "0",
			"ChangePenalty": "3000",
			"Commission": "0",
			"DFValue": "0",
			"EquivCurrencyCode": "INR",
			"Fare": [{
				"Amount": "100",
				"ChargeCode": "RCS",
				"ChargeType": "TravelFee"
			}, {
				"Amount": "65",
				"ChargeCode": "TRF",
				"ChargeType": "TravelFee"
			}, {
				"Amount": "177",
				"ChargeCode": "ASF",
				"ChargeType": "TravelFee"
			}, {
				"Amount": "91",
				"ChargeCode": "PSF",
				"ChargeType": "TravelFee"
			}, {
				"Amount": "4",
				"ChargeCode": "CGST07",
				"ChargeType": "Tax"
			}, {
				"Amount": "85",
				"ChargeCode": "CGST07",
				"ChargeType": "Tax"
			}, {
				"Amount": "4",
				"ChargeCode": "SGST07",
				"ChargeType": "Tax"
			}, {
				"Amount": "85",
				"ChargeCode": "SGST07",
				"ChargeType": "Tax"
			}],
			"FareBasisCode": "ASAVER",
			"FareInfoKey": "0~A~ ~SG~ASAVER~1922~~0~17~~X",
			"FareInfoValue": "A",
			"IsZeroCancellation": "false",
			"MarkUP": "0",
			"PaxType": "0",
			"Refundable": "true",
			"STF": "0",
			"ServiceFee": "0",
			"TDS": "0",
			"TotalFare": "4011",
			"TotalTax": "611",
			"TransactionAmount": "0",
			"TransactionFee": "0",
			"ZeroCancellationCharge": "100",
			"ZeroCancellationValidity": "28"
		}],
		"SeatCharge": "0",
		"TotalFareWithOutMarkUp": "8022",
		"TotalTaxWithOutMarkUp": "1222"
	},
	"FareIndicator": "0",
	"FareRule": "CAN-BEF 4_0:3500|CHG-BEF 4_0:3000|EMTFee-250|CANCEL-BEF 4_0:3500|CHANGE-BEF 4_0:3000",
	"IsBaggageFare": "false",
	"IsBrandAvailable": "false",
	"IsBranded": "false",
	"IsCache": "true",
	"IsHoldBooking": "false",
	"IsInternational": "false",
	"IsRoundTrip": "false",
	"IsSegmentChanged": "false",
	"IsSpecial": "false",
	"IsSpecialId": "false",
	"ItineraryKey": "IY8Y5uF2M9EKQh3DWSNY08U3T2Q0ldkhoZOFysq8vPE\u003d",
	"JourneyIndex": "0",
	"MemoryCreationTime": "/Date(1585156695861+0530)/",
	"NearByAirport": "false",
	"Remark": "",
	"SearchId": "107,ADT:BF\u003d3400.0000 Tax\u003d611.00000,CHD:BF\u003d0 Tax\u003d0,INF:BF\u003d0 Tax\u003d0,DFValue\u003d0,APFV\u003d4011.00000`611.00000`3400.0000`3500`3000`0`0`ASAVER``KG`15`True`0`0`0`0`,CPFV\u003d,IPFV\u003d,ATB\u003d`RCS:100.000`TRF:65.00000`ASF:177.00000`PSF:91.00000`CGST07:4.00000`CGST07:85.00000`SGST07:4.00000`SGST07:85.00000,CTB\u003d,ITB\u003d,TBA\u003d6800.0000,TTA\u003d1222.00000,TFA\u003d8022.00000",
	"ZCStatus": "false"
}';
		$post['TravelDate'] = '2020-03-30';
		$post['Origin'] = 'DEL';
		$post['Destination'] = 'BOM';
		$post['Traveller'] = '{"AdultTraveller":[{"CountryCode":"IN","DOB":"","EmailAddress":"mangal.pankaj5@gmail.com","FirstName":"Pankaj","FrequentFlierNumber":"","Gender":"","LastName":"Mangal","MiddleName":"","MobileNumber":"8920547478","Nationality":"IN","PassportExpiryDate":"","PassportNo":"","ResidentCountry":"","Title":"","expanded":false,"type":"Adult 1"},{"CountryCode":"IN","DOB":"","EmailAddress":"mangal.pankaj5@gmail.com","FirstName":"Singh","FrequentFlierNumber":"","Gender":"","LastName":"Ghotra","MiddleName":"","MobileNumber":"8920547478","Nationality":"IN","PassportExpiryDate":"","PassportNo":"","ResidentCountry":"","Title":"","expanded":false,"type":"Adult 2"}],"ChildTraveller":[],"InfantTraveller":[]}';
		$post['Amount'] = '8022';
		
		
		$booking = $flight_raw->booking1($post);
		print_r($booking);
	}*/
	
	
	
}