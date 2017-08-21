<?php
class ShareAppAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : ''; //driver/passenger
        $social = isset($_REQUEST['social']) ? $_REQUEST['social'] : ''; //f/g

        if (strlen($token) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token missing',)));
            Yii::app()->end();
        }

        $checkToken = LoginToken::model()->find('token = :token', array('token' => $token));
        if (!isset($checkToken)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token mismatch')));
            Yii::app()->end();
        }

        if (strlen($type) == 0 or strlen($social)==0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            Yii::app()->end();
        }
		if ($social != 'f' AND $social!='g') {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Social mismatch',)));
            Yii::app()->end();
        }
        $userId = $checkToken->userId;
        $amount = 0;
        if($type == 'driver')
        {
            $amount = Settings::model()->getSettingValueByKey(Globals::DRIVER_SHARE_BONUS_AMOUNT);
            $data = UserDriver::model()->find('userId = :userId', array('userId' => $userId));
            if( $social == 'f' && $data->sharedF == Globals::STATUS_ACTIVE)
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Shared',)));
                Yii::app()->end();
            }
            if( $social == 'g' && $data->sharedG == Globals::STATUS_ACTIVE)
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Shared',)));
                Yii::app()->end();
            }
        }
        elseif($type == 'passenger')
        {
            $amount = Settings::model()->getSettingValueByKey(Globals::PASSENGER_SHARE_BONUS_AMOUNT);
            $data = UserPassenger::model()->find('userId = :userId', array('userId' => $userId));
            if( $social == 'f' && $data->sharedF == Globals::STATUS_ACTIVE)
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Shared',)));
                Yii::app()->end();
            }
            if( $social == 'g' && $data->sharedG == Globals::STATUS_ACTIVE)
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Shared',)));
                Yii::app()->end();
            }
        }
        else
        {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Params mismatch',)));
            Yii::app()->end();
        }


        $user = User::model()->findByPk($userId);
        $balance = $user->balance;



        $newTransaction = new Transaction();
        $newTransaction->id = Globals::generateTransactionId($userId);
        $newTransaction->userId = $userId;
        $newTransaction->type = '+';
        $newTransaction->amount = $amount;
        $newTransaction->action = Globals::EXCHANGE_POINT;
        $newTransaction->dateCreated = date('Y-m-d H:i:s', time());
        $newTransaction->save();

        $user->balance = floatval($balance) + floatval($amount);
        $user->save();
        
        if($social == 'f')
        {
            $data->sharedF = Globals::STATUS_ACTIVE;

        }
        if($social == 'g')
        {
            $data->sharedG = Globals::STATUS_ACTIVE;
        }
        $data->save();

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));

    }
}