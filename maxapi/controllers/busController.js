import bcrypt from 'bcrypt'
import axios from 'axios';
class BusController{
    static getCityList = async (req, res) => {

         const cities = [
            {
                "id": 1059,
                "name": "Delhi (Delhi)"
            },
            {
                "id": 860,
                "name": "Patna (Bihar)"
            },
            {
                "id": 1268,
                "name": "Mumbai (Maharashtra)"
            },
            {
                "id": 6129,
                "name": "Kolkata (West Bengal)"
            }
        ];
        res.send({"status": "1", "message": "Success", "data": cities})


    }

    static destinationCity = async (req, res) => {
        const { skey, sourceId, destinationKey} = req.body;
        if(req.body.skey !='123456'){
            let error = {
               'status' : '0',
               'error' : 'AE1',
               'message' : 'Authentications Failed'
            };
            res.send(error)
       }
       const data = JSON.stringify({
        "sourceId": req.body.sourceId,
        "destinationKey":req.body.destinationKey,
        "key": "bgcdjmdtestkldh"   
    
    
    })
    var options = {
        'method': 'post',
        'url': 'http://stagingbusapi.easemytrip.com/api/search/GetDestinationCity/',
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
   static sourceCity = async (req, res) => {
    const { skey, sourceKey} = req.body;
    if(req.body.skey !='123456'){
        let error = {
           'status' : '0',
           'error' : 'AE1',
           'message' : 'Authentications Failed'
        };
        res.send(error)
   }
   const data = JSON.stringify({
    "sourceKey": req.body.sourceKey,
    "key": "bgcdjmdtestkldh"   


})
var options = {
    'method': 'post',
    'url': 'http://stagingbusapi.easemytrip.com/api/search/GetSourceCity/',
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


static availableTrips = async (req, res) => {
    const { skey, sourceId, destinationId, date} = req.body;
    if(req.body.skey !='123456'){
        let error = {
           'status' : '0',
           'error' : 'AE1',
           'message' : 'Authentications Failed'
        };
        res.send(error)
   }
   const data = JSON.stringify({
    "sourceId": req.body.sourceId,
    "destinationId": req.body.destinationId,
    "date": req.body.date 


})
var options = {
    'method': 'post',
    'url': 'http://stagingbusapi.easemytrip.com/api/detail/List/',
    'headers': {
        'Content-Type': 'application/json'
    },
    'data' : data
  };
  try {
    const result = await axios(options);
    if(result){
        res.send({"status": "1", "message": "Success", "data": result.data})
      } else {
        res.send({"status": 200, "message":"No Data Found"})
      }
  } catch(err) {
    console.log(err)
  }  
}

static seatDetail = async (req, res) => {
    const { skey,sourceId,sourceCity,destinationId,destinationCity,date,tripId,routeId,seater,sleeper,engineId} = req.body;
    if(req.body.skey !='123456'){
        let error = {
           'status' : '0',
           'error' : 'AE1',
           'message' : 'Authentications Failed'
        };
        res.send(error)
   }
   const data = JSON.stringify({
    "id": req.body.sourceId,
    "routeid":req.body.routeid,
    "searchReq":req.body.sourceCity|req.body.destinationId | req.body.tripId | req.body.routeId,
    "seater": req.body.seater,
    "sleeper": req.body.sleeper,
    "engineId": req.body.engineId,
    "JourneyDate": req.body.date,
    "agentCode":"EMTIN39483514",
    "version":"1.0"
})
var options = {
    'method': 'post',
    'url': 'http://stagingbusapi.easemytrip.com/api/detail/SeatBind/',
    'headers': {
        'Content-Type': 'application/json'
    },
    'data' : data
  };
  try {
    const result = await axios(options);
    if(result){
        res.send({"status": "1", "message": "Success", "data": result.data})
      } else {
        res.send({"status": 200, "message":"No Data Found"})
      }
  } catch(err) {
    console.log(err)
  }  
}
static blockSeat = async (req, res) => {
    const { skey,sourceId,destinationId,date,destinationCity,sourceCity,tripId,routeId,engineId,mobile,email,idProofType,idProofNo,address,busType,bId,travellers} = req.body;
    
    if(req.body.skey !='123456'){
        let error = {
           'status' : '0',
           'error' : 'AE1',
           'message' : 'Authentications Failed'
        };
        res.send(error)
   }
   const data = JSON.stringify({
    "sourceId": req.body.sourceId,
    "routeid":req.body.routeid,
    "destinationId":req.body.destinationId,
    "destinationCity":req.body.destinationCity,
    "sourceCity":req.body.sourceCity,
    "tripId":req.body.tripId,
    "seater": req.body.seater,
    "sleeper": req.body.sleeper,
    "engineId": req.body.engineId,
    "date": req.body.date,
    "mobile":req.body.mobile,
    "email":req.body.email,
    "idProofType":req.body.idProofType,
    "idProofNo":req.body.idProofNo,
    "address":req.body.address,
    "busType":req.body.busType,
    "boardprime": "false",
    "transactionId": "",
    "travellers": req.body.travellers,
    "GSTDetail": {
        "Phone": "",
        "Email": "",
        "CompanyName": "",
        "Address": "",
        "GSTNumber": ""
    },
    "WLCode": "",
    "IPAddress": "::1",
    "agentCode":"EMTIN39483514",
    "version": "1.0",
    "key": "sfjkbjkfskjsfjksEMT@2020"
})
var options = {
    'method': 'post',
    'url': 'http://stagingbusapi.easemytrip.com/api/detail/GetTentitiveId/',
    'headers': {
        'Content-Type': 'application/json'
    },
    'data' : data
  };
  try {
    const result = await axios(options);
    if(result){
        res.send({"status": "1", "message": "Success", "data": result.data})
      } else {
        res.send({"status": 200, "message":"No Data Found"})
      }
  } catch(err) {
    console.log(err)
  }  
}




    
}
export default BusController