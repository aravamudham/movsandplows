<?php

class ForgetPasswordAction extends CAction
{
    public function run()
    {
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';

        if (strlen($email) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Please input email',
            )));
            exit;
        }

        $check = User::model()->find('email ="'.$email.'"');

        if(!isset($check)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Email is not registered!',)));
            exit;
        }
        else
        {
            if ($check->typeAccount == \Globals::TYPE_ACCOUNT_SOCIAL){
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'You cannot reset password. Your email has been registed by social account!',)));
                exit;
            }
            $token = md5(strtotime('Now'));
            $check->token = $token;
            $check->save();


            $name = $check->fullName  ;
            $url = Yii::app()->getBaseUrl(true).'/api/resetPassword?token='.$token.'&email='.$email;


            $adminMail = Settings::model()->getSettingValueByKey(Globals::ADMIN_EMAIL);
            $message = new YiiMailMessage;
            $message->view = 'forgetPassword';
            $params = array('url' => $url, 'name'=>$name);
            $message->setBody($params, 'text/html');
            $message->subject = Yii::app()->name.' - Please confirm to reset your password';
            $message->addTo($email);
            $message->from = $adminMail;
            Yii::app()->mail->send($message);


            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'A message has been sent to your email, please check!',)));
        }


    }
}