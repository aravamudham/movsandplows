<?php

class PrepareLoginAction extends CAction
{
    public function run()
    {
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';

        if (strlen($email) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Params missing',)));
            exit;
        }
        $check = User::model()->find('email = :email', array('email' => $email));


        if (!isset($check) || $check->isOnline = Globals::STATUS_OFFLINE) {
            $isOnline = Globals::STATUS_OFFLINE;

        } else
            $isOnline = Globals::STATUS_ONLINE;

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => $isOnline,
            'message' => 'OK',)));

    }
}
