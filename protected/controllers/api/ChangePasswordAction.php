<?php
//http://localhost/matching/index.php/api/register?name=tony&email=tony@gmail.com&dob=1992-12-11&gender=2&password=123456
class ChangePasswordAction extends CAction
{
    public function run()
    {
        $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';


        $old_user = User::model()->findByPk($userId);
        if (isset($old_user))
        {

            $old_user->password = md5($password);
            if($old_user->save())
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
                'message' => 'User does not exist!',)));
            exit;
        }


    }
}