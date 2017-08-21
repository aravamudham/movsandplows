<?php

class TripPaymentAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $tripId = isset($_REQUEST['tripId']) ? $_REQUEST['tripId'] : '';


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

        if (strlen($tripId) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            Yii::app()->end();
        }


        $userId = $checkToken->userId;

        $trip = Trip::model()->findByPk($tripId);
        $amount = $trip->actualFare;

        $driver = User::model()->findByPk($trip->driverId);
        $passenger = User::model()->findByPk($userId);

        if ($passenger->balance < $amount) {
            $value = $amount - $passenger->balance;
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => $value,
                'message' => 'Your balance is short',)));
            Yii::app()->end();
        }

        //$fee = $amount * 80 / 100; Old code is hard code
        $fee = $amount * Settings::model()->getSettingValueByKey(Globals::DRIVER_EARN);


        $passenger->balance = $passenger->balance - $amount;
        $driver->balance = $driver->balance + $fee;
        $driver->save();
        $passenger->save();
        $now = date('Y-m-d H:i:s', time());

        $newTransaction = new Transaction();
        $newTransaction->id = Globals::generateTransactionId($userId);
        $newTransaction->userId = $userId;
        $newTransaction->type = '-';
        $newTransaction->amount = $amount;
        $newTransaction->tripId = $tripId;
        $newTransaction->action = Globals::TRIP_PAYMENT;
        $newTransaction->dateCreated = $now;
        $newTransaction->save();

        $newTransaction2 = new Transaction();
        $newTransaction2->id = Globals::generateTransactionId($trip->driverId);
        $newTransaction2->userId = $trip->driverId;
        $newTransaction2->type = '+';
        $newTransaction2->amount = $fee;
        $newTransaction2->tripId = $tripId;
        $newTransaction2->action = Globals::TRIP_PAYMENT;
        $newTransaction2->dateCreated = $now;
        $newTransaction2->save();

        $trip->status = Globals::TRIP_STATUS_FINISH;
        $trip->save();

        //$driverUser = UserDriver::model()->find('userId = :userId', array('userId' => $trip->driverId));
        //$driverUser->status = Globals::STATUS_IDLE;
        //$driverUser->save();

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}