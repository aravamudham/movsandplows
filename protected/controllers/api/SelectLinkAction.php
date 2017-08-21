<?php

class SelectLinkAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $link = isset($_REQUEST['link']) ? $_REQUEST['link'] : '';

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

        if (strlen($link) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Link missing',)));
            exit;
        }


        $userId = $checkToken->userId;
        $driver = UserDriver::model()->find('userId = :userId', array('userId' => $userId));
        if(isset($driver))
        {
            $driver->link = $link;
            $driver->status = Globals::STATUS_IDLE;
            $driver->save();
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'OK',)));
        }
        else
        {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'You are not a driver')));
            exit;
        }


    }
}