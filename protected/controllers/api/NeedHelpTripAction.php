<?php

class NeedHelpTripAction extends CAction
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

        $check = Trip::model()->findByPk($tripId);

        if (!$check) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Trip not found',)));
            exit;
        }

        if ($check->passengerId != $userId AND $check->driverId != $userId) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'You can not perform this action',)));
            exit;
        }

        $check->need_help = 1;
        if ($check->save()) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'OK',)));

        } else {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Can not decrease your balance, request fail',)));
            exit;
        }
    }
}