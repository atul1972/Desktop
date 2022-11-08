import axios from 'axios';
class HotelApiController{
    static HotelList = async (req, res) => {
        //console.log("MyTest", req)
        //const { name, email, password, password_confirmation, tc } = req.body
        var data = JSON.stringify({
            "Chain": 1,
            "City": "Delhi",
            "CityName": "Delhi, India",
            "CheckInDate": "2022-10-26",
            "CheckOutDate": "2022-10-27",
            "IsNotRoomAvail": true,
            "PageNo": 2,
            "Rating": "3",
            "MaxRange": 2000,
            "MinRange": 1000,
            "TotalHotel": 5,
            "country": "India",
            "rooms": {
              "Count": 1,
              "Room": [
                {
                  "NumberOfAdults": 2,
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
            "Nights": 1,
            "Engine": 5,
            "EMTAuthentication": {
              "UserName": "HotelAPITest",
              "Password": "HotelAPITest",
              "AgentCode": 15,
              "IPAddress": "35.199.32.91"
            },
            "isFareRecheck": false
          });
          
          var config = {
            method: 'post',
            url: 'http://cloud.easemytrip.in/EMTAPI/MiHotel.svc/HotelList',
            headers: { 
              'Content-Type': 'application/json'
            },
            data : data
          };
          
          axios(config)
          .then(function (response) {
            console.log(JSON.stringify(response.data));
            let ApiResponse = this.JSON.stringify(response.data);
            return ApiResponse
          })
          .catch(function (error) {
            //console.log(error);
            return error
          });
    }
}
export default HotelApiController;



