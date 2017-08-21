<?php
class PointExchangeAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $transactionId = isset($_REQUEST['transactionId']) ? $_REQUEST['transactionId'] : '123456789';
        $amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
        $paymentMethod =  isset($_REQUEST['paymentMethod']) ? $_REQUEST['paymentMethod'] : '1';

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

        if (
            strlen($transactionId) == 0 ||
            strlen($amount) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            exit;
        }


        $userId = $checkToken->userId;

        $user = User::model()->findByPk($userId);
        $balance = $user->balance;

        $newTrip = new PointExchange();
        $newTrip->userId = $userId;
        $newTrip->amount = $amount;
        $newTrip->paymentMethod = $paymentMethod;
        $newTrip->status = Globals::STATUS_ACTIVE;
        $newTrip->dateCreated = date('Y-m-d H:i:s', time());
        $newTrip->save();


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

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}