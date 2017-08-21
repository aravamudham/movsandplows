<?php

class UpdateCoordinateAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] : '';
        $long = isset($_REQUEST['long']) ? $_REQUEST['long'] : '';


        if (strlen($token) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token missing',)));
            exit;
        }

        /** @var LoginToken $checkToken */
        $checkToken = LoginToken::model()->find('token = :token', array('token' => $token));
        if (!isset($checkToken)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token mismatch')));
            exit;
        }


        $userId = $checkToken->userId;

        $user = User::model()->findByPk($userId);
        $user->lat = $lat;
        $user->long = $long;
        $user->save();

        //$logLatLong = new LogLatLong();
        //$logLatLong->user_id = $checkToken->userId;
        //$logLatLong->lat = $lat;
        //$logLatLong->long = $long;
        //$logLatLong->time = date('Y-m-d H:i:s');
        //$logLatLong->save();

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}