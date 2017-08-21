<?php

class CancelTripAction extends CAction
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

        if (strlen($tripId) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing required params',)));
            exit;
        }

        $userId = $checkToken->userId;

        $check = Trip::model()->findByPk($tripId);

        if (!$check) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Trip not found',)));
        }

        if ($check->passengerId != $userId AND $check->driverId != $userId) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'You can not perform this action',)));
            exit;
        }

        $driverId = $check->driverId;

        $cancel_fee = Settings::model()->getSettingValueByKey(Globals::CANCELLATION_FEE);

        $user = User::model()->findByPk($userId);
        $user->balance = $user->balance - $cancel_fee;

        $newTransaction = new Transaction();
        $newTransaction->id = Globals::generateTransactionId($userId);
        $newTransaction->userId = $userId;
        $newTransaction->type = '-';
        $newTransaction->amount = $cancel_fee;
        $newTransaction->action = Globals::CANCELLATION_ORDER_FEE;
        $newTransaction->dateCreated = date('Y-m-d H:i:s', time());


        if ($user->save() && $newTransaction->save()) {
            $registrationIDs = array();

            if ($check->driverId == $userId) {
                $device = Device::model()->find('userId =' . $check->passengerId);
                $message = Yii::t('common', 'message.driver.cancel');
            } else {
                $device = Device::model()->find('userId =' . $check->driverId);
                $message = Yii::t('common', 'message.passenger.cancel');
            }


            $msg = array
            (
                'data' => array(),
                'action' => 'cancelTrip',
                'body' => $message,

            );


            if ($device->type == Globals::DEVICE_TYPE_ANDROID) {
                array_push($registrationIDs, $device->gcm_id);
                Globals::pushAndroid($registrationIDs, $msg);
            } elseif ($device->type == Globals::DEVICE_TYPE_IOS) {
                array_push($registrationIDs, $device->gcm_id);
                Globals::pushIos($registrationIDs, $msg);
            }
            $check->delete();
            $driver = UserDriver::model()->find('userId =' . $driverId);
            $driver->status = Globals::STATUS_IDLE;
            $driver->save();

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'OK',)));

        } else {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Can not decrease your balance, request fail',)));
            exit;
        }
    }
}