<?php

class CancelRequestAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '8ababea1276abffb8e305ff5ca45701f';
        $requestId = isset($_REQUEST['requestId']) ? $_REQUEST['requestId'] : '';
        $driver = isset($_REQUEST['driver']) ? $_REQUEST['driver'] : ''; //1,0


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

        if (strlen($driver) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing required params',)));
            exit;
        }

        $userId = $checkToken->userId;


        if ($driver == 0) {
            $requests = Request::model()->findAll('passengerId =' . $userId);
            if (count($requests) <= 0) {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => '',
                    'message' => 'OK',)));
            }
            $ids = array();

            foreach ($requests as $item) {
                $ids[] = $item->driverId;
            }


            Request::model()->deleteAll('passengerId =' . $userId);


            $string = implode(',', $ids);

            $devices = Device::model()->findAll('userId IN (' . $string . ')');

            $registrationIDs1 = array();
            $registrationIDs2 = array();

            if (count($devices) != 0) {
                foreach ($devices as $device) {
                    if ($device->type == Globals::DEVICE_TYPE_ANDROID) {
                        $registrationIDs1[] = $device->gcm_id;
                    }

                    if ($device->type == Globals::DEVICE_TYPE_IOS) {
                        $registrationIDs2[] = $device->gcm_id;
                    }
                }
            }

            $message = Yii::t('common', 'message.passenger.cancel');

            $msg = array
            (
                'data' => array(),
                'action' => 'cancelRequest',
                'body' => $message,

            );

            //var_dump($registrationIDs2);die;
            Globals::pushAndroid($registrationIDs1, $msg);
            Globals::pushIos($registrationIDs2, $msg);

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'OK',)));
        } elseif ($driver == 1) {

            if (strlen($requestId) == 0) {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Missing required params',)));
                exit;
            }


            $request = Request::model()->findByPk($requestId);
            if (!isset($request)) {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Request does not exist',)));
                exit;
            }

            $request->delete();
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'message' => 'OK',)));
        }
    }
}