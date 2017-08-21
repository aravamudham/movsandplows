<?php
class PointRedeemAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';

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

        /*
        if ($amount != 3000 AND $amount != 5000) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Amount need to be 3000 or 5000',)));
            exit;
        }
		*/


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

        $newTrip = new PointRedeem();
        $newTrip->userId = $userId;
        $newTrip->amount = $amount;
        $newTrip->payoutPaypalAddress = $user->payoutPaypalAddress;
        $newTrip->status = Globals::STATUS_INACTIVE;
        $newTrip->dateCreated = date('Y-m-d H:i:s', time());
        $newTrip->save();

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'message' => 'OK',)));
    }
}