<?php
//http://localhost/matching/index.php/api/register?name=tony&email=tony@gmail.com&dob=1992-12-11&gender=2&password=123456
class ResetPasswordAction extends CAction
{
    public function run()
    {
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $code = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

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
            $name = $check->fullName  ;
            $string = $check->token;

            if ($code != $string)
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Invalid token!',)));
                exit;
            }


            $length = 8;
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $count = mb_strlen($chars);

            for ($i = 0, $result = ''; $i < $length; $i++) {
                $index = rand(0, $count - 1);
                $result .= mb_substr($chars, $index, 1);
            }
                $check->password = md5($result);
                $check->save();


            $adminMail = Settings::model()->getSettingValueByKey(Globals::ADMIN_EMAIL);

            $message = new YiiMailMessage;
            $message->view = 'newPassword';
            $params = array('password' => $result, 'name'=>$name);
            $message->setBody($params, 'text/html');
            $message->subject = Yii::app()->name.' - Your password has been reset';
            $message->addTo($email);
            $message->from = $adminMail;
            Yii::app()->mail->send($message);


            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'A message contain new password has been sent to your email',)));
        }
    }
}