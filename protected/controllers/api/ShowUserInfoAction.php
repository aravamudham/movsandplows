<?php

class ShowUserInfoAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

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
                'message' => 'Token mismatch',)));
            exit;
        }

        $userId = $checkToken->userId;
        $driverRate = '';
        $driverRateCount = 0;
        $bankAccount = '';

        $user = User::model()->findByPk($userId);
        $passenger = UserPassenger::model()->find('userId = :userId', array('userId' => $userId));
        /** @var UserDriver $driverData */
        $driverData = UserDriver::model()->find('userId = :userId', array('userId' => $userId));

        $link = Yii::app()->getBaseUrl(true);
        $car = array();
        $driver = array();

        if (isset($user)) {
            if (isset($driverData)) {
                $check = UpdatePending::model()->find('userId = :userId', array('userId' => $userId));
                $update_pending = isset($check) ? Globals::STATUS_ACTIVE : Globals::STATUS_INACTIVE;

                $driver = array(
                    'driverRate' => strlen($driverData->rate) != 0 ? $driverData->rate : '',
                    'driverRateCount' => $driverData->rateCount,
                    'bankAccount' => $driverData->bankAccount,
                    'status' => $driverData->status != null ? $driverData->status : '',
                    'isOnline' => $driverData->isOnline != null ? $driverData->isOnline : '',
                    'document' => strlen($driverData->document) != 0 ? $link . '/upload/user_document/' . $driverData->document : '',
                    'isActive' => $driverData->isActive,
                    'updatePending' => $update_pending,
                    'linkType' => $driverData->linkType,
                );

                $carData = Vehicle::model()->find('userId = :userId', array('userId' => $userId));
                $imagesData = VehicleImg::model()->findAll('carId = :carId', array('carId' => $carData->id));
                $images = array();

                $i = 0;
                foreach ($imagesData as $image) {
                    $i++;
                    $images['image' . $i] = $link . '/upload/car/' . $image->image;
                }


                $car = array(
                    'id' => $carData->id,
                    'carPlate' => $carData->carPlate,
                    'brand' => $carData->brand,
                    'model' => $carData->model,
                    'year' => $carData->year,
                    'status' => $carData->status,
                    'document' => strlen($carData->document) != 0 ? $link . '/upload/car_document/' . $carData->document : '',
                    'dateCreated' => $carData->dateCreated,
                    'images' => $images
                );
            }

            $imageUrl = $user->image;
            if ($user->typeAccount == \Globals::TYPE_ACCOUNT_NORMAL) {
                $url = Yii::app()->getBaseUrl(true) . '/upload/user/';
                $imageUrl = $url . $user->image;
            }
            $data = array(
                'id' => $user->id,
                'fullName' => $user->fullName,
                'image' => $imageUrl,
                'email' => $user->email,
                'description' => strlen($user->description) != 0 ? $user->description : '',
                'isActive' => $user->isActive,
                'gender' => $user->gender,
                'phone' => strlen($user->phone) != 0 ? $user->phone : '',
                'dob' => strlen($user->dob) != 0 ? $user->dob : '',
                'address' => strlen($user->address) != 0 ? $user->address : '',
                'balance' => $user->balance,
                'isOnline' => $user->isOnline,
                'passengerRate' => strlen($passenger->rate) != 0 ? $passenger->rate : '',
                'passengerRateCount' => $passenger->rateCount,
                'stateId' => $user->stateId,
                'stateName' => isset($user->state) ? $user->state->name : '',
                'cityId' => $user->cityId,
                'cityName' => $user->cityId,
                'typeAccount' => $user->typeAccount,
                'account' => $user->payoutPaypalAddress,
                'driver' => $driver,
                'car' => $car,
            );

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => $data,
                'message' => 'OK',)));
        } else {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'User not found',)));
        }
    }
}