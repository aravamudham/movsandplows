<?php

class RegisterAction extends CAction
{
    public function run()
    {
        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        $image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';
        $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
        $dob = isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '';
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        $card_number = isset($_REQUEST['card_number']) ? $_REQUEST['card_number'] : '';
        $cvv = isset($_REQUEST['cvv']) ? $_REQUEST['cvv'] : '';
        $exp = isset($_REQUEST['exp']) ? $_REQUEST['exp'] : '';
        $driver = isset($_REQUEST['driver']) ? $_REQUEST['driver'] : ''; //1,0
        $account = isset($_REQUEST['account']) ? $_REQUEST['account'] : '';
        $car_id = isset($_REQUEST['car_id']) ? $_REQUEST['car_id'] : '';

        if ($driver == Globals::STATUS_ACTIVE) {
            if (strlen($account) == 0 || strlen($car_id) == 0) {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Missing bank account or car information',)));
                exit;
            }
        }

        if (strlen($name) == 0 || strlen($email) == 0 || strlen($password) == 0 || strlen($phone) == 0 || strlen($card_number) == 0 || strlen($cvv) == 0 || strlen($exp) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing input data',)));
            exit;
        }
//        if(!isset($_FILES['image']))
//        {
//            ApiController::sendResponse(200, CJSON::encode(array(
//                'status' => 'ERROR',
//                'data' => '',
//                'message' => 'Missing photo',)));
//            exit;
//        }
//        $imageName = $_FILES['image']['name'];
//        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/linkapp/upload/user/';
//        $file_path = $file_path . basename($_FILES['image']['name']);
        $old_user = User::model()->find('email ="' . $email . '"');
        if (isset($old_user)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'User already exists!',)));
            exit;
        } else {
            //if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {

            $start_point = Settings::model()->getSettingValueByKey(Globals::SIGN_UP_START_POINTS);
            $new = new User();
            $new->fullName = $name;
            $new->image = $image;//Yii::app()->getBaseUrl(true) . '/upload/user/' . $imageName;
            $new->email = $email;
            $new->password = $password;//md5($password);
            $new->description = $description;
            $new->gender = $gender;
            $new->phone = $phone;
            $new->dob = $dob;
            $new->address = $address;
            $new->balance = $start_point;
            $new->cardNumber = $card_number;
            $new->cvv = $cvv;
            $new->exp = $exp;
            $new->isActive = Globals::STATUS_ACTIVE;
            $new->isDriver = $driver;
            $new->dateCreated = date('Y-m-d H:i:s', time());
            $new->save();

            $user_id = $new->id;

            $passenger = new UserPassenger();
            $passenger->userId = $user_id;
            $passenger->rateCount = 0;
            $passenger->save();

            if ($driver == Globals::STATUS_ACTIVE) {
                $driver = new UserDriver();
                $driver->userId = $new->id;
                $driver->rateCount = 0;
                $driver->bankAccount = $account;
                $driver->isActive = Globals::STATUS_INACTIVE;
                $driver->save();

                $car = Vehicle::model()->findByPk($car_id);
                $car->userId = $user_id;
                $car->save();

            }

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'OK',)));
            //} else {
            //    ApiController::sendResponse(200, CJSON::encode(array(
            //       'status' => 'ERROR',
            //       'data' => '',
            //       'message' => 'Upload fail!',)));
            //   exit;
            //}
        }
    }
}