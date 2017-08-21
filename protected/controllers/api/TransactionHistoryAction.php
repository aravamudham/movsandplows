<?php
class TransactionHistoryAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '8ababea1276abffb8e305ff5ca45701f';
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';

        $rows_per_page= Settings::model()->getSettingValueByKey(Globals::APP_POST_PER_PAGE);		
        $start_index= ($page-1)*$rows_per_page;
		
		
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


        $userId = $checkToken->userId;

        //$transactions = Transaction::model()->findAll('userId ='.$userId);
        //$totalPage = ceil(count($transactions)/$rows_per_page);

        $criteria = new CDbCriteria();
        $criteria->condition = 'userId ='.$userId;
        $criteria->order = 'dateCreated DESC';
        $criteria->limit = $rows_per_page;
        $criteria->offset = $start_index;
        $results = Transaction::model()->findAll($criteria);


        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => $results,
            'message' => 'OK',)));

    }
}