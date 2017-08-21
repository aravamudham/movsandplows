<?php

class LoginNormalAction extends CAction
{
    public function run()
    {

        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        $gcm_id = isset($_REQUEST['gcm_id']) ? $_REQUEST['gcm_id'] : '';
        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';//type device
        $lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] : '';
        $long = isset($_REQUEST['long']) ? $_REQUEST['long'] : '';


        if (strlen($email) == 0 || strlen($gcm_id) == 0 || strlen($ime) == 0 || strlen($password) == 0 || strlen($lat) == 0 || strlen($long) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing required params ',)));
            exit;
        }

        $driverActive = 0;


        $userCheck = User::model()->find("email = '" . $email . "'");
        if (isset($userCheck)) {
            if ($userCheck->typeAccount == \Globals::TYPE_ACCOUNT_SOCIAL) {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'The email address you have entered is already registered by social account!',)));
                exit;
            }
        } else {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Email not exist!',)));
            exit;
        }

        $user = User::model()->find("email = '" . $email . "' AND  password = '" . md5($password) . "'");
        if (isset($user)) {
            $user_id = $user->id;
            $token = md5($email . time());
            $user->isOnline = Globals::STATUS_ONLINE;
            $user->lat = $lat;
            $user->long = $long;
            $user->save();

            $driver = UserDriver::model()->find('userId = :userId', array('userId' => $user_id));
            if (isset($driver) && $driver->isActive == Globals::STATUS_ACTIVE) {
                $driverActive = 1;
            }

            $old_device = Device::model()->find('userId =' . $user_id);

            if (!isset($old_device)) {
                $new_device = new Device();
                $new_device->userId = $user_id;
                $new_device->gcm_id = $gcm_id;
                $new_device->ime = $ime;
                $new_device->status = Globals::STATUS_ACTIVE;
                $new_device->type = $type;
                $new_device->dateCreated = date('Y-m-d H:i:s', strtotime('Now'));
                $new_device->save();
            } elseif (isset($old_device) && ($old_device->gcm_id != $gcm_id)) {
                $old_device->gcm_id = $gcm_id;
                $old_device->ime = $ime;
                $old_device->type = $type;
                $old_device->save();
            }

            $old_token = LoginToken::model()->find('userId =' . $user_id);
            if (isset($old_token)) {
                $old_token->token = $token;
                $old_token->time = date('Y-m-d H:i:s', time());
                $old_token->save();
            } else {
                $new_token = new LoginToken();
                $new_token->userId = $user_id;
                $new_token->token = $token;
                $new_token->time = date('Y-m-d H:i:s', time());
                $new_token->save();
            }


            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => array(
                    'user_id' => $user_id,
                    'points' => $user->balance,
                    'token' => $token,
                    'isDriver' => $user->isDriver,
                    'driverActive' => $driverActive,
                    'typeAccount' => \Globals::TYPE_ACCOUNT_NORMAL,

                ),
                'message' => 'OK',)));

        } else {

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Email or password not correct!',)));
        }

    }
}