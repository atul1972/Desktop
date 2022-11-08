import express from 'express';
const router = express.Router();
import UserController from '../controllers/userController.js';
import checkUserAuth from '../middlewares/auth-middleware.js';
import hotelListController from '../controllers/hotellistContrller.js';
import busaController from '../controllers/busController.js';
import flightaController from '../controllers/flightsController.js'

// ROute Level Middleware - To Protect Route
router.use('/changepassword', checkUserAuth)
router.use('/loggeduser', checkUserAuth)


// Public Routes
router.post('/register', UserController.userRegistration)
router.post('/registermobile', UserController.userRegistrationMobile)
router.post('/otpVeryfication', UserController.otpVeryfication)
router.post('/login', UserController.userLogin)
router.post('/send-reset-password-email', UserController.sendUserPasswordResetEmail)
router.post('/reset-password/:id/:token', UserController.userPasswordReset)

// Protected Routes
router.post('/changepassword', UserController.changeUserPassword)
router.get('/loggeduser', UserController.loggedUser)
router.post('/search', hotelListController.searchHotel)
router.post('/hotelInfo', hotelListController.hotelInfo)
router.post('/hotelbook', hotelListController.hotelbook)
router.post('/preCheck', hotelListController.preCheck)
router.post('/sourceCity', busaController.sourceCity)
router.post('/destinationCity', busaController.destinationCity)
router.post('/availableTrips', busaController.availableTrips)
router.post('/seatDetail', busaController.seatDetail)
router.post('/blockSeat', busaController.blockSeat)
router.post('/airportList', flightaController.airportList)
router.post('/flightsSearch', flightaController.flightsSearch)


export default router
