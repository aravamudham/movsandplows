<?php
//https://maps.googleapis.com/maps/api/geocode/json?latlng=21.0462945,105.802482&sensor=false&key=AIzaSyCjYHZ7rgat5a79h09nKoMVPLq9bNF8EXw
//https://developers.google.com/maps/documentation/geocoding/
//https://developers.google.com/maps/articles/geocodestrat#client

class ShowMyRequestAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $driver = isset($_REQUEST['driver']) ? $_REQUEST['driver'] : '1'; //1,0

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
                'message' => 'Missing params',)));
            exit;
        }

        $userId = $checkToken->userId;

        $results = array();
        if ($driver == 1) {
            $results = Request::model()->findAll('driverId =' . $userId);

        } elseif ($driver == 0) {
            $results = Request::model()->findAll('passengerId =' . $userId);
        }
        $link = Yii::app()->getBaseUrl(true);

        $data = array();
        if (count($results) != 0) {
            /** @var Request $item */
            foreach ($results as $item) {
                $passengerRate = UserPassenger::model()->find('userId=' . $item->passengerId)->rate;

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

                $data[] = array(
                    'id' => $item->id,
                    'passengerId' => $item->passengerId,
                    'passenger' => $passenger,
                    'driverId' => $item->driverId,
                    'driver' => $driver,
                    'requestTime' => $item->requestTime,
                    'link' => $item->link,
                    'startTime' => isset($item->startTime) ? $item->startTime : '',
                    'startLat' => $item->startLat,
                    'startLong' => $item->startLong,
                    'startLocation' => $item->startLocation,
                    'endLat' => $item->endLat,
                    'endLong' => $item->endLong,
                    'endLocation' => $item->endLocation,
                    'passengerRate' => isset($passengerRate) ? $passengerRate : '',
                    'estimate_fare' => $item->estimateFare
                );
            }
        }


        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => $data,
            'message' => 'OK',)));

    }
}