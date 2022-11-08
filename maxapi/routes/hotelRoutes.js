import express from 'express';
const router = express.Router();
import hotelListController from '../controllers/hotellistContrller.js';
//import checkUserAuth from '../middlewares/auth-middleware.js';

router.post('/search', hotelListController.searchHotel)

//export default router