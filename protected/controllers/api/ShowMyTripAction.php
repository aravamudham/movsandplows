<?php

class ShowMyTripAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';

        $rows_per_page = Settings::model()->getSettingValueByKey(Globals::APP_POST_PER_PAGE);
        $start_index = ($page - 1) * $rows_per_page;


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

        $criteria = new CDbCriteria();
        $criteria->condition = 'driverId =' . $userId . ' OR passengerId =' . $userId;
        $criteria->order = 'status ASC, id DESC';
        $criteria->limit = $rows_per_page;
        $criteria->offset = $start_index;
        $results = Trip::model()->findAll($criteria);

        $link = Yii::app()->getBaseUrl(true);

        $data = array();
        if (count($results) != 0) {
            foreach ($results as $item) {
                $totalTime = '';
                if (isset($item->endTime))
                    $totalTime = ceil((strtotime($item->endTime) - strtotime($item->startTime)) / 60);

                $passenger = array(
                    'id' => $item->passenger->id,
                    'fullName' => $item->passenger->fullName,
                    'image' => (stripos($item->passenger->image, 'user.') != false) ? Yii::app()->getBaseUrl(true) . '/upload/user/' . $item->passenger->image : $item->passenger->image,
                    'email' => $item->passenger->email,
                    'description' => strlen($item->passenger->description) != 0 ? $item->passenger->description : '',
                    'gender' => $item->passenger->gender,
                    'phone' => strlen($item->passenger->phone) != 0 ? $item->passenger->phone : '',
                    'dob' => strlen($item->passenger->dob) != 0 ? $item->passenger->dob : '',
                    'address' => strlen($item->passenger->address) != 0 ? $item->passenger->address : '',
                    'balance' => $item->passenger->balance,
                    'isOnline' => $item->passenger->isOnline,
                    'rate' => isset($item->passenger->passenger->rate) ? $item->passenger->passenger->rate : '',
                    'rateCount' => isset($item->passenger->passenger->rateCount) ? $item->passenger->passenger->rateCount : '',
                );

                $imagesData = VehicleImg::model()->findAll('carId = :carId', array('carId' => $item->driver->vehicle->id));
                $images = array();

                $i = 0;
                foreach ($imagesData as $image) {
                    $i++;
                    $images['image' . $i] = $link . '/upload/car/' . $image->image;
                }

                $driver = array(
                    'id' => $item->driver->id,
                    'fullName' => $item->driver->fullName,
                    'image' => (stripos($item->driver->image, 'user.') != false) ? Yii::app()->getBaseUrl(true) . '/upload/user/' . $item->driver->image : $item->driver->image,
                    'email' => $item->driver->email,
                    'description' => strlen($item->driver->description) != 0 ? $item->driver->description : '',
                    'gender' => $item->driver->gender,
                    'phone' => strlen($item->driver->phone) != 0 ? $item->driver->phone : '',
                    'dob' => strlen($item->driver->dob) != 0 ? $item->driver->dob : '',
                    'address' => strlen($item->driver->address) != 0 ? $item->driver->address : '',
                    'balance' => $item->driver->balance,
                    'isOnline' => $item->driver->isOnline,
                    'rate' => isset($item->driver->driver->rate) ? $item->driver->driver->rate : '',
                    'rateCount' => isset($item->driver->driver->rateCount) ? $item->driver->driver->rateCount : '',
                    'carPlate' => $item->driver->vehicle->carPlate . '',
                    'carImages' => $images
                );

                /** @var Transaction $transaction */
                $transaction = Transaction::model()->find('tripId = ' . $item->id . ' 
                && userId = ' . $item->driver->id . ' 
                && type = "+"');
                $data[] = array(
                    'id' => $item->id,
                    'passengerId' => $item->passengerId,
                    'passenger' => $passenger,
                    'link' => $item->link,
                    'startTime' => isset($item->startTime) ? $item->startTime : '',
                    'startLat' => $item->startLat,
                    'startLong' => $item->startLong,
                    'endLat' => $item->endLat,
                    'endLong' => $item->endLong,
                    'dateCreated' => $item->dateCreated,
                    'driverId' => $item->driverId,
                    'driver' => $driver,
                    'startLocation' => $item->startLocation,
                    'endLocation' => $item->endLocation,
                    'status' => $item->status,
                    'endTime' => isset($item->endTime) ? $item->endTime : '',
                    'distance' => isset($item->distance) ? $item->distance : '',
                    'estimateFare' => isset($item->estimateFare) ? $item->estimateFare : '',
                    'actualFare' => isset($item->actualFare) ? $item->actualFare : '',
                    'actualReceive' => isset($transaction) ? $transaction->amount : '',
                    'driverRate' => isset($item->driverRate) ? $item->driverRate : '',
                    'passengerRate' => isset($item->passengerRate) ? $item->passengerRate : '',
                    'totalTime' => $totalTime
                );
            }
        }


        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => $data,
            'message' => 'OK',)));

    }
}