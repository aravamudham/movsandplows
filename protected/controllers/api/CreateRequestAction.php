<?php

class CreateRequestAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $link = isset($_REQUEST['link']) ? $_REQUEST['link'] : '';
        $startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : '';
        $startLat = isset($_REQUEST['startLat']) ? $_REQUEST['startLat'] : '';
        $startLong = isset($_REQUEST['startLong']) ? $_REQUEST['startLong'] : '';
        $startLocation = isset($_REQUEST['startLocation']) ? $_REQUEST['startLocation'] : '';
        $endLat = isset($_REQUEST['endLat']) ? $_REQUEST['endLat'] : '';
        $endLong = isset($_REQUEST['endLong']) ? $_REQUEST['endLong'] : '';
        $endLocation = isset($_REQUEST['endLocation']) ? $_REQUEST['endLocation'] : '';
        $estimateDistance = isset($_REQUEST['estimateDistance']) ? $_REQUEST['estimateDistance'] : '100';

        $distance = Settings::model()->getSettingValueByKey(Globals::DISTANCE_FOR_SEARCHING_DRIVER);

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

        if (strlen($link) == 0
            || strlen($startLat) == 0 || strlen($startLong) == 0 || strlen($endLat) == 0 || strlen($endLong) == 0
        ) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            exit;
        }


        $userId = $checkToken->userId;
        $balance = User::model()->findByPk($userId)->balance;
        $min_balance = Settings::model()->getSettingValueByKey(Globals::MIN_BALANCE_PLACE_REQUEST);


        $listDriverID = "SELECT driverId from request WHERE passengerId =" . $userId;


        $results = Yii::app()->db->createCommand()
            ->select('place.*, ud.rate, ud.rateCount')
            ->from("
            (SELECT *, (((acos(sin((" . $startLat . "*pi()/180)) *
                    sin((`lat`*pi()/180))+cos((" . $startLat . "*pi()/180)) *
                    cos((`lat`*pi()/180)) * cos(((" . $startLong . "-
                            `long`)*pi()/180))))*180/pi())*60*1.1515*1.609344)
            as distance,
            (SELECT COUNT(*) FROM request WHERE
							UNIX_TIMESTAMP(NOW())
                            <= (requestTime +30) AND driverId = `user`.id)
			as noOfOrders

            FROM `user`) place INNER JOIN user_driver ud ON place.id = ud.userId")
            ->where("distance <= " . $distance . " AND place.isOnline=1 AND isDriver=1 AND noOfOrders <=" . Settings::model()->getSettingValueByKey(Globals::MAX_REQUEST_ACCEPTABLE) . " AND ud.`status` = 1 AND ud.isActive =1
                AND ud.isOnline = 1      
                AND ud.linkType = '" . $link . "'
                AND ud.userId NOT IN (" . $listDriverID . ")
             ")
            ->limit(Settings::model()->getSettingValueByKey(Globals::MAX_DRIVER_RECEIVE_PUSH))
            ->queryAll();

        $adevices = array();
        $idevices = array();

        $estimate_fare = Globals::EstimateFare($link, $estimateDistance);

        foreach ($results as $item) {
            $newTrip = new Request();
            $newTrip->passengerId = $userId;
            $newTrip->link = $link;
            $newTrip->startLat = $startLat;
            $newTrip->startLong = $startLong;
            $newTrip->startLocation = $startLocation;
            $newTrip->endLocation = $endLocation;
            $newTrip->endLat = $endLat;
            $newTrip->endLong = $endLong;
            $newTrip->requestTime = time();
            $newTrip->driverId = $item['id'];
            $newTrip->estimateFare = $estimate_fare;
            $newTrip->save();

            $device = Device::model()->find('userId =' . $item['id']);
            if (isset($device)) {
                if ($device->type == Globals::DEVICE_TYPE_ANDROID) {
                    array_push($adevices, $device->gcm_id);
                } elseif ($device->type == Globals::DEVICE_TYPE_IOS) {
                    array_push($idevices, $device->gcm_id);
                }
            }
        }

        $msg = array
        (
            'data' => array(),
            'action' => 'createRequest',
            'body' => Yii::t('common', 'message.request.driver'),
        );

        if (count($adevices) != 0)
            Globals::pushAndroid($adevices, $msg);
        if (count($idevices) != 0)
            Globals::pushIos($idevices, $msg);

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => '',
            'estimate_fare' => $estimate_fare,
            'count' => count($results),
            'message' => 'OK',)));
    }
}