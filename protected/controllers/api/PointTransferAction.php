<?php
class PointTransferAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '9999';
        $receiverEmail = isset($_REQUEST['receiverEmail']) ? $_REQUEST['receiverEmail'] : 'driver@gmail.com';
        $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : 'driver@gmail.com';

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

        if (strlen($amount) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            exit;
        }


        $userId = $checkToken->userId;

        $user = User::model()->findByPk($userId);
        $balance = $user->balance;
        if($balance < $amount)
        {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Your balance is short',)));
            exit;
        }
        $receiver = User::model()->find('email = :email', array('email' => $receiverEmail));

        $newTrip = new PointTransfer();
        $newTrip->senderId = $userId;
        $newTrip->amount = $amount;
        $newTrip->receiverId = $receiver->id;
        $newTrip->status = Globals::STATUS_INACTIVE;
        $newTrip->note = $note;
        $newTrip->dateCreated = date('Y-m-d H:i:s', time());
        $newTrip->save();



        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}