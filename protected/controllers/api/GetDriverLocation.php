<?php
class GetDriverLocation extends CAction
{
    public function run()
    {
        if(isset($_REQUEST['driverId'])){
            $driverId = $_REQUEST['driverId'];
            /** @var User $driver */
            $driver = User::model()->findByPk($driverId);

            if (isset($driver)) {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => array(
                        'driverId'=> $driver->id,
                        'driverLat'=> $driver->lat,
                        'driverLon'=> $driver->long,
                    ),
                    'message' => '',)));
            }else{
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Driver not found',)));
            }
        }else{
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing Parameter',)));
        }
    }
}