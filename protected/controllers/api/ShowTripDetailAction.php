<?php

class ShowTripDetailAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $tripId = isset($_REQUEST['tripId']) ? $_REQUEST['tripId'] : '';

        if (strlen($token) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token missing',)));
            exit;
        }

        $checkToken = LoginToken::model()->find('token = :token', array('token' => $token));
        if (!isset($checkToken)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token mismatch')));
            exit;
        }
        if (strlen($tripId) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            exit;
        }

        $result = Trip::model()->findByPk($tripId);
        $driverId = $result->driverId;
        $userDriver = User::model()->findByPk($driverId);
        $link = Yii::app()->getBaseUrl(true);
        $vehicle = Vehicle::model()->find('userId = :userId', array('userId' => $driverId));
        $car_image = VehicleImg::model()->find('carId =' . $vehicle->id)->image;
        $driver = UserDriver::model()->find('userId =' . $driverId);
        $driverData = array(
            'driverName' => $userDriver->fullName,
            'rate' => isset($driver->rate) ? $driver->rate : '',
            'imageDriver' => (stripos($userDriver->image, 'user.') != false) ? Yii::app()->getBaseUrl(true).'/upload/user/'.$userDriver->image : $userDriver->image,
            'carPlate' => $vehicle->carPlate,
            'carImage' => $link . '/upload/car/' . $car_image,
            'phone' => isset($userDriver->phone) ? $userDriver->phone : '',
        );


        $passengerId = $result->passengerId;
        $passenger = UserPassenger::model()->find('userId =' . $passengerId);
        $userPassenger = User::model()->findByPk($passenger->userId);
        $passengerData = array(
            'id' => $userPassenger->id,
            'passengerName' => $userPassenger->fullName,
            'rate' => isset($passenger->rate) ? $passenger->rate : '',
            'imagePassenger' => (stripos($userPassenger->image, 'user.') != false) ? Yii::app()->getBaseUrl(true).'/upload/user/'.$userPassenger->image : $userPassenger->image,
            'phone' => isset($userPassenger->phone) ? $userPassenger->phone : '',
        );

        $totalTime = '';
        if (isset($result->endTime))
            $totalTime = ceil((strtotime($result->endTime) - strtotime($result->startTime)) / 60);

        $data = array(
            'id' => $result->id,
            'passengerId' => $result->passengerId,
            'link' => $result->link,
            'startTime' => isset($result->startTime) ? $result->startTime : '',
            'startLat' => $result->startLat,
            'startLong' => $result->startLong,
            'endLat' => $result->endLat,
            'endLong' => $result->endLong,
            'dateCreated' => $result->dateCreated,
            'driverId' => $result->driverId,
            'startLocation' => $result->startLocation,
            'endLocation' => $result->endLocation,
            'status' => $result->status,
            'endTime' => isset($result->endTime) ? $result->endTime : '',
            'distance' => isset($result->distance) ? $result->distance : '',
            'estimateFare' => isset($result->estimateFare) ? $result->estimateFare : '',
            'actualFare' => isset($result->actualFare) ? $result->actualFare : '',
            'driverRate' => isset($result->driverRate) ? $result->driverRate : '',
            'passengerRate' => isset($result->passengerRate) ? $result->passengerRate : '',
            'driver' => $driverData,
            'passenger' => $passengerData,
            'totalTime' => $totalTime
        );


        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => $data,
            'message' => 'OK',)));

    }
}