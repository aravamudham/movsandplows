<?php
/**
 * Created by PhpStorm.
 * User: KIEMDOAN
 * Date: 10/26/2016
 * Time: 3:47 PM
 */

class SignupAction extends CAction
{
    public function run()
    {
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        $fullName = isset($_REQUEST['full_name']) ? $_REQUEST['full_name'] : '';
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : '';
        $post_code = isset($_REQUEST['post_code']) ? $_REQUEST['post_code'] : '';
        $payoutPaypalAddress = isset($_REQUEST['account']) ? $_REQUEST['account'] : '';
        //$image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';
       // $image_ext = '.png';

        if (strlen($email) == 0 || strlen($phone) == 0 || strlen($password) == 0 || strlen($fullName) == 0)
        {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing required params',)));
            exit;
        }
        if (!isset($_FILES['image'])) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing photo',)));
            exit;
        }

        //check user exist
        $user = User::model()->find('email = :email', array(':email' => $email));
        if (isset($user)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Email is exist!',)));
            exit;
        } else {
            $start_point = Settings::model()->getSettingValueByKey(Globals::SIGN_UP_START_POINTS);
            $user = new User();
            $user->typeAccount = \Globals::TYPE_ACCOUNT_NORMAL;
            $user->fullName = $fullName;
            $user->payoutPaypalAddress = $payoutPaypalAddress;
            $user->phone = $phone;
            $user->email = $email;
            $user->password = md5($password);
            $user->dateCreated = date('Y-m-d H:i:s', time());
            $user->isOnline = \Globals::STATUS_OFFLINE;
            $user->isActive = \Globals::STATUS_ACTIVE;
            $user->balance = $start_point;
            $user->isDriver = Globals::STATUS_INACTIVE;
            if (strlen($city) != 0){
                $user->cityId = $city;
            }
            if (strlen($country) != 0){
                $user->stateId = $country;
            }
            if (strlen($address) != 0){
                $user->address = $address;
            }
            if (strlen($post_code) != 0){
                $user->description = $post_code;
            }



            if ($user->save()) {
                if (isset($_FILES['image'])) {
                    $original_image_name = $_FILES['image']['name'];
                    $image_ext = pathinfo($original_image_name, PATHINFO_EXTENSION);
                    $imageName = time()  . 'user.' . $image_ext;
                    $file_path = Yii::getPathOfAlias(SITE) . '/upload/user/';
                    $file_path = $file_path . basename($imageName);
                    move_uploaded_file($_FILES['image']['tmp_name'], $file_path);
                    $user->image = $imageName;
                    $user->save();
                }

                $passenger = new UserPassenger();
                $passenger->userId = $user->id;
                $passenger->rateCount = 0;
                $passenger->save();
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => '',
                    'message' => 'Register successfully!',)));


            } else {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Cannot save!',)));
            }

        }
    }
}