<?php

class ShowDistanceAction extends CAction
{
    public function run()
    {
        $tripId = isset($_REQUEST['tripId']) ? $_REQUEST['tripId'] : '1'; //1,0

        if (strlen($tripId) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            exit;
        }

        $result = Trip::model()->findByPk($tripId);

        $passenger = User::model()->findByPk($result->passengerId);
        $driver = User::model()->findByPk($result->driverId);


        $driverLat = $driver->lat;
        $driverLong = $driver->long;

        $passengerLat = $passenger->lat;
        $passengerLong = $passenger->long;

        $theta = $driverLong - $passengerLong;
        $dist = sin(deg2rad($driverLat)) * sin(deg2rad($passengerLat)) + cos(deg2rad($driverLat)) * cos(deg2rad($passengerLat)) * cos(deg2rad($theta));
        $dist = floatval($dist . '');
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $km = $miles * 1.609344;

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => $km,
            'message' => 'OK',)));
    }
}