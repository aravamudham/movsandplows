<?php



class DriverConfirmAction extends CAction

{

    public function run()

    {

        //test

        /*$msg = array

        (

            'data' => array(

                'tripId' => 123,

            ),

            'action' => 'driverConfirm',

            'body' => Yii::t('common', 'message.driver.accepted')



        );

        $registrationIDs = array();
        array_push($registrationIDs, 'd1JqOiaAV8w:APA91bG-dsKGI4hl0guhKWC70Ug0PAI9MYBHMjdIlOTHK7KnPvEiEG0esyMNEu9ur4wzQ3m6bexshM8j9vHJkaZtllz_zKlF4dpVh6_oCMkWL85vKyP3pzxNHwkRBIsBgId9HEDVl31Z');
        Globals::pushAndroid($registrationIDs, $msg);
                //dsKGI4hl0guhKWC70Ug0PAI9MYBHMjdIlOTHK7KnPvEiEG0esyMNEu9ur4wzQ3m6bexshM8j9vHJkaZtllz_zKlF4dpVh6_oCMkWL85vKyP3pzxNHwkRBIsBgId9HEDVl31Z
        // end test


die;*/


        
        //tai day


        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

        $requestId = isset($_REQUEST['requestId']) ? $_REQUEST['requestId'] : '1';



        if (strlen($token) == 0) {

            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'ERROR',

                'data' => '',

                'message' => 'Token missing',)));

            Yii::app()->end();

        }



        $checkToken = LoginToken::model()->find('token = :token', array('token' => $token));

        if (!isset($checkToken)) {

            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'ERROR',

                'data' => '',

                'message' => 'Token mismatch')));

            Yii::app()->end();

        }



        if (strlen($requestId) == 0) {

            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'ERROR',

                'data' => '',

                'message' => 'Missing required params',)));

            Yii::app()->end();

        }



        $userId = $checkToken->userId;





        $check = Trip::model()->findAll('driverId =' . $userId . ' AND status !=' . Globals::TRIP_STATUS_FINISH);

        if (count($check) != 0) {

            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'ERROR',

                'data' => '',

                'message' => 'You can not accept more request',)));

            Yii::app()->end();

        }



        /** @var Request $request */

        $request = Request::model()->findByPk($requestId);

        if (!isset($request)) {

            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'ERROR',

                'data' => '',

                'message' => 'This request is taken by other driver',)));

            Yii::app()->end();

        }



        if ($request->driverId != $userId) {

            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'ERROR',

                'data' => '',

                'message' => 'You are not driver of this request',)));

            Yii::app()->end();

        }



        $newTrip = new Trip();

        $newTrip->passengerId = $request->passengerId;

        $newTrip->link = $request->link;

        $newTrip->startTime = $request->startTime;

        $newTrip->startLat = $request->startLat;

        $newTrip->startLong = $request->startLong;

        $newTrip->endLat = $request->endLat;

        $newTrip->endLong = $request->endLong;

        $newTrip->dateCreated = date('Y-m-d H:i:s', time());

        $newTrip->driverId = $request->driverId;

        $newTrip->startLocation = $request->startLocation;

        $newTrip->endLocation = $request->endLocation;

        $newTrip->estimateFare = $request->estimateFare;

        $newTrip->status = Globals::TRIP_STATUS_APPROACHING;

        $newTrip->save();



        $driver = UserDriver::model()->find('userId =' . $userId);

        $driver->status = Globals::STATUS_BUSY;

        $driver->save();





        $registrationIDs = array();

        $device = Device::model()->find('userId =' . $request->passengerId);





        $msg = array

        (

            'data' => array(

                'tripId' => $newTrip->id,

            ),

            'action' => 'driverConfirm',

            'body' => Yii::t('common', 'message.driver.accepted')



        );

        if ($device->type == Globals::DEVICE_TYPE_ANDROID) {

            array_push($registrationIDs, $device->gcm_id);
            // var_dump($registrationIDs);exit;
            if(count($registrationIDs)>0){
                Globals::pushAndroid($registrationIDs, $msg);
                //dsKGI4hl0guhKWC70Ug0PAI9MYBHMjdIlOTHK7KnPvEiEG0esyMNEu9ur4wzQ3m6bexshM8j9vHJkaZtllz_zKlF4dpVh6_oCMkWL85vKyP3pzxNHwkRBIsBgId9HEDVl31Z
            }    
            /*else
                echo 123;exit;*/

        } elseif ($device->type == Globals::DEVICE_TYPE_IOS) {

            array_push($registrationIDs, $device->gcm_id);

            Globals::pushIos($registrationIDs, $msg);

        }



        $passengerId = $request->passengerId;

        //22/9 delete driver requests too -> fail

        //Request::model()->deleteAll('passengerId =' . $passengerId . ' OR driverId ='.$userId);

        Request::model()->deleteAll('passengerId =' . $passengerId);





        $userDriver = User::model()->findByPk($userId);

        $link = Yii::app()->getBaseUrl(true);

        $vehicle = Vehicle::model()->find('userId = :userId', array('userId' => $userId));

        $car_image = VehicleImg::model()->find('carId =' . $vehicle->id)->image;

        $driver = UserDriver::model()->find('userId =' . $userId);

        $driverData = array(

            'driverName' => $userDriver->fullName,

            'rate' => isset($driver->rate) ? $driver->rate : '',

            'imageDriver' => (stripos($userDriver->image, 'user.') != false) ? Yii::app()->getBaseUrl(true) . '/upload/user/' . $userDriver->image : $userDriver->image,

            'carPlate' => $vehicle->carPlate,

            'carImage' => $link . '/upload/car/' . $car_image,

            'phone' => isset($userDriver->phone) ? $userDriver->phone : '',

        );





        $passengerId = $newTrip->passengerId;

        $passenger = UserPassenger::model()->find('userId =' . $passengerId);

        $userPassenger = User::model()->findByPk($passenger->userId);

        $passengerData = array(

            'passengerName' => $userPassenger->fullName,

            'rate' => isset($passenger->rate) ? $passenger->rate : '',

            'imagePassenger' => (stripos($userPassenger->image, 'user.') != false) ? Yii::app()->getBaseUrl(true) . '/upload/user/' . $userPassenger->image : $userPassenger->image,

            'phone' => isset($userPassenger->phone) ? $userPassenger->phone : '',

        );



        $data = array(

            'id' => $newTrip->id,

            'passengerId' => $newTrip->passengerId,

            'link' => $newTrip->link,

            'startTime' => '',

            'startLat' => $newTrip->startLat,

            'startLong' => $newTrip->startLong,

            'endLat' => $newTrip->endLat,

            'endLong' => $newTrip->endLong,

            'dateCreated' => $newTrip->dateCreated,

            'driverId' => $newTrip->driverId,

            'startLocation' => $newTrip->startLocation,

            'endLocation' => $newTrip->endLocation,

            'status' => $newTrip->status,

            'endTime' => isset($newTrip->endTime) ? $newTrip->endTime : '',

            'distance' => isset($newTrip->distance) ? $newTrip->distance : '',

            'estimateFare' => isset($newTrip->estimateFare) ? $newTrip->estimateFare : '',

            'actualFare' => isset($newTrip->actualFare) ? $newTrip->actualFare : '',

            'driverRate' => isset($newTrip->driverRate) ? $newTrip->driverRate : '',

            'passengerRate' => isset($newTrip->passengerRate) ? $newTrip->passengerRate : '',

            'driver' => $driverData,

            'passenger' => $passengerData

        );





        ApiController::sendResponse(200, CJSON::encode(array(

            'status' => 'SUCCESS',

            'data' => $data,

            'message' => 'OK',)));

    }

}