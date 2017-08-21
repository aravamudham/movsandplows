<?php

class RatePassengerAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $tripId = isset($_REQUEST['tripId']) ? $_REQUEST['tripId'] : '2';
        $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : '9';

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

        if (strlen($tripId) == 0 || strlen($rate) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing required params',)));
            exit;
        }
        $userId = $checkToken->userId;

        $check = Trip::model()->findByPk($tripId);

        if ($check->driverId != $userId) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'You are not driver',)));
            exit;
        }

        //if ($check->status != Globals::TRIP_STATUS_IN_PROGRESS) {
        //    ApiController::sendResponse(200, CJSON::encode(array(
        //        'status' => 'ERROR',
        //        'data' => '',
        //        'message' => 'Can not rate now',)));
        //    exit;
        //}

        $passengerId = $check->passengerId;
        $check->passengerRate = $rate;
        $check->save();

        $command = Yii::app()->db->createCommand();
        $command->select('SUM(passengerRate) AS total');
        $command->from('trip');
        $command->where('passengerId=:id', array(':id' => $passengerId));
        $total = $command->queryScalar();

        $trips = Trip::model()->findAll('passengerId =' . $passengerId . ' AND passengerRate IS NOT NULL');
        $new_count = count($trips);

        $passenger = UserPassenger::model()->find('userId =' . $passengerId);
        $passenger->rateCount = $new_count;
        $passenger->rate = $total / $new_count;
        $passenger->save();

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}