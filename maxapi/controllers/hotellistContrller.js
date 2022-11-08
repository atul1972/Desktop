import bcrypt from 'bcrypt'
import axios from 'axios';
class hotelListController{
    
    //For Hotel Search API Logic
    static searchHotel = async (req, res) => {
             
        const { skey, city, checkInDate, checkOutDate, numberOfChild, numberOfAdults } = req.body
        if(req.body.skey !='123456'){
             let error = {
                'status' : '0',
                'error' : 'AE1',
                'message' : 'Authentications Failed'
             };
             res.send(error)
        }
        const data = JSON.stringify({
      "Chain": 1,
			"City": req.body.city,
      "CityName": req.body.city+", India",
			"CheckInDate": req.body.checkInDate,
			"CheckOutDate": req.body.checkOutDate,
			"IsCorpFare": false,
			"MaxRange": 2000,
			"MinRange": 1000,
			"Rating": "3",
			"TotalHotel": 5,
			"country": "India",
			"rooms": {
                "Count": 1,
                "Room": [
                  {
                    "NumberOfAdults": req.body.numberOfAdults,
                    "Child": {
                      "NumberOfChild": 1,
                      "childAge": [
                        "5"
                      ]
                    }
                  }
                ]
              },
			"currency": "INR",
			"Nights": 0,
			"Engine": 5,
			"EMTAuthentication": {
				"UserName": "HotelAPITest",
				"Password": "HotelAPITest",
				"AgentCode": 5,
				"IPAddress": "35.199.32.91",				
			},
			"isFareRecheck": false,
			"EMTCommonID": ""
		})
        var options = {
            'method': 'post',
            'url': 'http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/HotelList',
            'headers': {
                'Content-Type': 'application/json'
            },
            'data' : data
          };
          try {
            const result = await axios(options);
            if(result){
                res.send({"status": "success", "message": "Login Success", "data": result.data})
              } else {
                res.send({"status": 200, "message":"No Data Found"})
              }
          } catch(err) {
            console.log(err)
          }        
                    
        

    }
//For Hoter Detail API
    static hotelInfo = async (req, res) => {
             
      const { skey, hotelId, emtComonId} = req.body;
     
      if(req.body.skey !='123456'){
           let error = {
              'status' : '0',
              'error' : 'AE1',
              'message' : 'Authentications Failed'
           };
           res.send(error)
      }
      const user = 'HotelAPITest';
      const pass = 'HotelAPITest';
      const data = ''
      var options = {
          'method': 'post',
          'url': 'http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/HotelInfo/'+req.body.hotelID+'/15/'+req.body.emtComonId+'/'+user+'/'+pass+'/'+15,
          'headers': {
              'Content-Type': 'application/json'
          },
          'data' : data
        };
        //console.log(options) ;exit
        try {
          const result = await axios(options);
          if(result){
              res.send({"status": "success", "message": "Login Success", "data": result.data})
            } else {
              res.send({"status": 200, "message":"No Data Found"})
            }
        } catch(err) {
          console.log(err)
        }        
                  
      

  }
//For Hotel Pre Check
  static preCheck = async (req, res) => {
             
    const {skey, city, checkInDate, checkOutDate, numberOfChild, numberOfAdults,eMTCommonId,engine,hotelID, mealType,rateCode,rateKey,roomType,roomTypeCode } = req.body
    if(req.body.skey !='123456'){
         let error = {
            'status' : '0',
            'error' : 'AE1',
            'message' : 'Authentications Failed'
         };
         res.send(error)
    }
    const data = JSON.stringify({
      "CheckInDate": req.body.checkInDate,
			"CheckOutDate": req.body.checkOutDate,
			"City": req.body.city,
			"EMTAuthentication": {
				"UserName": "HotelAPITest",
				"Password": "HotelAPITest",
				"AgentCode": 15,
				"IPAddress": "35.199.32.91"
			},
			"EMTCommonID": req.body.eMTCommonId,
			"Engine": req.body.engine,
			"HotelChain": "",
			"HotelID": req.body.hotelID,
			"IncludeDetails": true, 
			"Options": [],
			"MealType": req.body.mealType,
			"Nationality": "India",
			"RateCode": req.body.rateCode,
			"RateKey": req.body.rateKey,
			"RoomType": req.body.roomType,
			"RoomTypeCode":req.body.roomTypeCode,
			"rooms": {
        "Count": 1,
        "Room": [
          {
            "NumberOfAdults": req.body.numberOfAdults,
            "Child": {
              "NumberOfChild": 1,
              "childAge": [
                "5"
              ]
            }
          }
        ]
      },

			"country": "India",
			"currency": "INR",
			"stringToken": ""
})
    var options = {
        'method': 'post',
        'url': 'http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/HotelList',
        'headers': {
            'Content-Type': 'application/json'
        },
        'data' : data
      };
      try {
        const result = await axios(options);
        if(result){
            res.send({"status": "success", "message": "Login Success", "data": result.data})
          } else {
            res.send({"status": 200, "message":"No Data Found"})
          }
      } catch(err) {
        console.log(err)
      }        
                
    

}

//Hotel Book
static hotelbook = async (req, res) => {
             
  const {skey, city, checkInDate, checkOutDate, numberOfChild, numberOfAdults,eMTCommonId,engine,hotelID, mealType,rateCode,rateKey,roomType,roomTypeCode,name,price, } = req.body
  if(req.body.skey !='123456'){
       let error = {
          'status' : '0',
          'error' : 'AE1',
          'message' : 'Authentications Failed'
       };
       res.send(error)
  }
  const data = JSON.stringify({
    "AddressDetail":{
      "address":req.body.address,
      "city":req.body.city,
      "countryCode":req.body.countryCode,
      "postalCode":req.body.postalCode,
      "stateCode":req.body.stateCode
    },
    
    "EMTAuthentication": {
      "UserName": "HotelAPITest",
      "Password": "HotelAPITest",
      "AgentCode": 15,
      "IPAddress": "35.199.32.91"
    },
    "AppVersion":"",
    "BookingID":"",
    "CancellationPolicy":"",
    "CashBack":"",
    "CashBackUsed":"",
    "City":"",
    "Commission":"",
    "Country":"",
    "CouponCode":"",
    "CouponValue":"",
    "CurrencyCode":"",
    "EMTCommonID":req.body.emtComonId,
    "EngineID":15,
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
    "IsAE":true,
    "IsWL":true,
    "MealType":req.body.mealType,
		"Name":req.body.name,
		"Nationality":"",
		"NationalityCode":"",
		"Nights":2147483647,
    "rooms": {
      "Count": 1,
      "Room": [
        {
          "NumberOfAdults": req.body.numberOfAdults,
          "Child": {
            "NumberOfChild": 1,
            "childAge": [
              "5"
            ]
          }
        }
      ]
    },
            "SearchKey":"",
						"Tax":"",
						"TransactionRefNo":"",
						"Transactionid":'.rand(1000,9999).',
						"UniqueID":"",
						"chargeableRate":req.body.price,
						"checkInDate":req.body.CheckInDate,
						"checkOutDate":req.body.checkOutDate,
						"hotelID":req.body.hotelID,
						"rateCode":req.body.rateCode,						
						"rateKey":req.body.rateKey,
						"roomTypeCode":req.body.roomTypeCode,
						"supplierType":""
    })
    
 
  var options = {
      'method': 'post',
      'url': 'http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/HotelBooking',
      'headers': {
          'Content-Type': 'application/json'
      },
      'data' : data
    };
    try {
      const result = await axios(options);
      if(result){
          res.send({"status": "success", "message": "Login Success", "data": result.data})
        } else {
          res.send({"status": 200, "message":"No Data Found"})
        }
    } catch(err) {
      console.log(err)
    }        
              
  

}
}
export default hotelListController