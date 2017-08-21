<?php

class SearchDriverAction extends CAction
{
    public function run()
    {
        $lat = isset($_REQUEST['startLat']) ? $_REQUEST['startLat'] : '21.0462945';
        $long = isset($_REQUEST['startLong']) ? $_REQUEST['startLong'] : '105.802482';
        $distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : '3';
        $carType = isset($_REQUEST['carType']) ? $_REQUEST['carType'] : '';

        if (strlen($lat) == 0 || strlen($long) == 0 || strlen($distance) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing params',)));
            exit;
        }

        if (isset($carType) && $carType != null){
            $carType = ' AND ud.linkType="'.$carType.'"';
        }else{
            $carType = '';
        }

        //ud.status =1 : idle, ud.status = 0: busy
        $results = Yii::app()->db->createCommand()
            ->select('place.*, ud.rate, ud.rateCount, ud.linkType')
            ->from("
            (SELECT *, (((acos(sin((" . $lat . "*pi()/180)) *
                    sin((`lat`*pi()/180))+cos((" . $lat . "*pi()/180)) *
                    cos((`lat`*pi()/180)) * cos(((" . $long . "-
                            `long`)*pi()/180))))*180/pi())*60*1.1515*1.609344)
            as distance,
            (SELECT COUNT(*) FROM request WHERE
							UNIX_TIMESTAMP(NOW())
                            <= (requestTime +30) AND driverId = `user`.id)
			as noOfOrders
            FROM `user`) place INNER JOIN user_driver ud ON place.id = ud.userId")
            ->where("distance <= " . $distance . " AND ud.isOnline=1 AND isDriver=1 AND noOfOrders <=" . Settings::model()->getSettingValueByKey(Globals::MAX_REQUEST_ACCEPTABLE) . " AND ud.`status` = 1 AND ud.isActive = 1".$carType)
            ->limit(50)
            ->queryAll();
        $data = array();
        foreach ($results as $driver) {
            $data[] = array(
                'fullName' => $driver['fullName'],
                'image' => $driver['image'],
                'gender' => $driver['gender'],
                'description' => $driver['description'],
                'phone' => $driver['phone'],
                'distance' => $driver['distance'],
                'lat' => $driver['lat'],
                'long' => $driver['long'],
                'rate' => $driver['rate'],
                'carType' => $driver['linkType'],
                'rateCount' => $driver['rateCount'],
            );
        }

        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'count' => count($data),
            'data' => $data,
            'message' => 'OK',)));
    }
}