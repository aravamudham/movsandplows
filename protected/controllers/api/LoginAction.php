<?php



class LoginAction extends CAction

{

    public function run()

    {

        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';

        $gcm_id = isset($_REQUEST['gcm_id']) ? $_REQUEST['gcm_id'] : '';

        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';

        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

        $lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] : '';

        $long = isset($_REQUEST['long']) ? $_REQUEST['long'] : '';



        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';

        $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';

        $image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';



        //$auth = isset($_REQUEST['auth']) ? $_REQUEST['auth'] : '';

        if (strlen($email) == 0 || strlen($gcm_id) == 0 || strlen($ime) == 0 || strlen($lat) == 0 || strlen($long) == 0) {

            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'ERROR',

                'data' => '',

                'message' => 'Missing required params',)));

            exit;

        }

        $driverActive = 0;



        /** @var User $old_user */

        $old_user = User::model()->find('email ="' . $email . '"');

        if (isset($old_user)) {

            /*if ($old_user->isActive != Globals::STATUS_ACTIVE) {

                ApiController::sendResponse(200, CJSON::encode(array(

                    'status' => 'ERROR',

                    'data' => '',

                    'message' => 'Your account is deactivated. Please contact admin to activate it.',)));

                exit;

            }*/

            $user_id = $old_user->id;

            $token = md5($email . time());

            $old_user->isOnline = Globals::STATUS_ONLINE;

            $old_user->lat = $lat;

            $old_user->long = $long;



            if ($old_user->typeAccount == \Globals::TYPE_ACCOUNT_SOCIAL){

                $old_user->image = $image;

                $old_user->fullName = $name;

                $old_user->gender = $gender;

                $old_user->typeAccount = \Globals::TYPE_ACCOUNT_SOCIAL; //new

                $old_user->save();

            }else{

                $old_user->gender = $gender;

                $old_user->typeAccount = \Globals::TYPE_ACCOUNT_NORMAL; //new

                $old_user->save();

            }



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

                    'points' => $old_user->balance,

                    'token' => $token,

                    'isDriver' => $old_user->isDriver,

                    'driverActive' => $driverActive



                ),

                'message' => 'OK',)));



        } else {

            $old_user = new User();

            $token = md5($email . time());

            $start_point = Settings::model()->getSettingValueByKey(Globals::SIGN_UP_START_POINTS);

            $new = new User();

            $new->fullName = $name;

            $new->image = $image;

            $new->email = $email;

            $new->gender = $gender;

            $new->balance = $start_point;

            $new->lat = $lat;

            $new->long = $long;

            $new->isDriver = Globals::STATUS_INACTIVE;

            $new->isOnline = Globals::STATUS_ONLINE;

            $new->isActive = Globals::STATUS_ACTIVE;

            $new->dateCreated = date('Y-m-d H:i:s', time());

            $new->cityId = '';

            $old_user->typeAccount = \Globals::TYPE_ACCOUNT_SOCIAL; //new

            $new->save();





            $user_id = $new->id;



            $new_device = new Device();

            $new_device->userId = $user_id;

            $new_device->gcm_id = $gcm_id;

            $new_device->ime = $ime;

            $new_device->status = Globals::STATUS_ACTIVE;

            $new_device->type = $type;

            $new_device->dateCreated = date('Y-m-d H:i:s', strtotime('Now'));

            $new_device->save();



            $new_token = new LoginToken();

            $new_token->userId = $user_id;

            $new_token->token = $token;

            $new_token->time = date('Y-m-d H:i:s', time());

            $new_token->save();



            $passenger = new UserPassenger();

            $passenger->userId = $user_id;

            $passenger->rateCount = 0;

            $passenger->save();



            ApiController::sendResponse(200, CJSON::encode(array(

                'status' => 'SUCCESS',

                'data' => array(

                    'user_id' => $user_id,

                    'points' => $new->balance,

                    'token' => $token,

                    'isDriver' => 0,

                    'driverActive' => $driverActive

                ),

                'message' => 'OK',)));

        }



    }

}