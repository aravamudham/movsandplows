<?php

class RateDriverAction extends CAction
{
    public function run()
    {


        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $tripId = isset($_REQUEST['tripId']) ? $_REQUEST['tripId'] : '';
        $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : '';


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

        if (strlen($tripId) == 0 ||strlen($rate) == 0  ) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing required params',)));
            exit;
        }

        $userId = $checkToken->userId;

        $check = Trip::model()->findByPk($tripId);

        if ($check->passengerId != $userId) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'You are not passenger',)));
            exit;
        }

        //if ($check->status != Globals::TRIP_STATUS_IN_PROGRESS) {
        //    ApiController::sendResponse(200, CJSON::encode(array(
        //        'status' => 'ERROR',
        //        'data' => '',
        //        'message' => 'Can not rate now',)));
        //    exit;
        //}

        $driverId =$check->driverId;
        $check->driverRate = $rate;
        $check->save();

        $command=Yii::app()->db->createCommand();
        $command->select('SUM(driverRate) AS total');
        $command->from('trip');
        $command->where('driverId=:id', array(':id'=>$driverId));
        $total =  $command->queryScalar();

        $trips = Trip::model()->findAll('driverId ='.$driverId. ' AND driverRate IS NOT NULL');
        $new_count = count($trips);

        $passenger = UserDriver::model()->find('userId ='.$driverId);
        $passenger->rateCount = $new_count;
        $passenger->rate = $total/$new_count;
        $passenger->save();

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}