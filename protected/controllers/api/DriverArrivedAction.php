<?php

class DriverArrivedAction extends CAction
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
                'message' => 'Missing required params',)));
            exit;
        }

        $userId = $checkToken->userId;

        //$userId = 1;
        $check = Trip::model()->findByPk($tripId);

        if ($check->driverId != $userId) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'You are not driver',)));
            exit;
        }

        if ($check->status != Globals::TRIP_STATUS_APPROACHING) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Can not arrive now',)));
            exit;
        }

        $passengerId = $check->passengerId;

        $registrationIDs = array();
        $device = Device::model()->find('userId =' . $passengerId);

        $msg = array
        (
            'data' => array(),
            'action' => 'driverArrived',
            'body' => Yii::t('common', 'message.driver.arrived')
        );

        if ($device->type == Globals::DEVICE_TYPE_ANDROID) {
            array_push($registrationIDs, $device->gcm_id);
            Globals::pushAndroid($registrationIDs, $msg);
        } elseif ($device->type == Globals::DEVICE_TYPE_IOS) {
            array_push($registrationIDs, $device->gcm_id);
            Globals::pushIos($registrationIDs, $msg);
        }

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}