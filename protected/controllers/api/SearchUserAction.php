<?php

class SearchUserAction extends CAction
{
    public function run()
    {


        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $email= isset($_REQUEST['email']) ? $_REQUEST['email'] : '';


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

        if (strlen($email)== 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing required params',)));
            exit;
        }

        $check = User::model()->find('email ="' . $email . '"');

        if (!isset($check)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Email has not been registered',)));
            exit;
        }
        else
        {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => array(
                    'fullName' => $check->fullName,
                    'image' => $check->image,
                    'email' => $check->email,
                    'gender' => $check->gender,
                ),
                'message' => 'OK',)));
        }

    }
}