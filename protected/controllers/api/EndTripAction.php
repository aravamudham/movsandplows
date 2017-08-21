<?php

class EndTripAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $tripId = isset($_REQUEST['tripId']) ? $_REQUEST['tripId'] : '';
        $distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : '';
        
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

        if (strlen($tripId) == 0 || strlen($distance)== 0) {
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

        if ($check->status != Globals::TRIP_STATUS_IN_PROGRESS) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Can not end trip now',)));
            exit;
        }


        $link = $check->link;
        $startTime =  strtotime($check->startTime);
        $endTime =  time();
        $fare = Globals::calculateFare($link,$startTime,$endTime,$distance);

        $check->endTime = date('Y-m-d H:i:s',$endTime);
        $check->actualFare = $fare;
        $check->distance = $distance;
		$check->status = Globals::TRIP_STATUS_PENDING_PAYMENT;
        $check->save();

        $passengerId = $check->passengerId;

        $registrationIDs = array();
        $device = Device::model()->find('userId ='.$passengerId);

		
		
        $msg = array
        (
            'data' => array(                
            ),
            'action' => 'endTrip',
            'body' => Yii::t('common', 'message.end.trip')
        );

        if($device->type == Globals::DEVICE_TYPE_ANDROID )
        {
            array_push($registrationIDs, $device->gcm_id);
            Globals::pushAndroid($registrationIDs,$msg);
        }
        elseif($device->type == Globals::DEVICE_TYPE_IOS)
        {
            array_push($registrationIDs, $device->gcm_id);
            Globals::pushIos($registrationIDs,$msg);
        }
		
		$driverUser = UserDriver::model()->find('userId = :userId', array('userId' => $userId));
        $driverUser->status = Globals::STATUS_IDLE;
        $driverUser->save();

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}