<?php

class AddInfoAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $dob = isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '';
        $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';

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
                'message' => 'Token mismatch',)));
            exit;
        }

        $userId = $checkToken->userId;
        $old_user = User::model()->findByPk($userId);


        if (strlen($phone) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing input data',)));
            exit;
        }


        $old_user->description = $description;
        $old_user->phone = $phone;
        $old_user->dob = $dob;
        $old_user->address = $address;
        $old_user->isActive = Globals::STATUS_ACTIVE;
        $old_user->dateCreated = date('Y-m-d H:i:s', time());
        $old_user->save();

        $passenger = new UserPassenger();
        $passenger->userId = $userId;
        $passenger->rateCount = 0;
        $passenger->save();


        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));

    }
}
