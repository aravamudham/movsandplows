<?php

class DriverRegisterAndroidAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $carPlate = isset($_REQUEST['carPlate']) ? $_REQUEST['carPlate'] : '';
        $brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : '';
        $model = isset($_REQUEST['model']) ? $_REQUEST['model'] : '';
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        $account = isset($_REQUEST['account']) ? $_REQUEST['account'] : '';
        $image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';
        $image2 = isset($_REQUEST['image2']) ? $_REQUEST['image2'] : '';
        $document = isset($_REQUEST['document']) ? $_REQUEST['document'] : '';
        $document_name = isset($_REQUEST['document_name']) ? $_REQUEST['document_name'] : '';
        $link_type = isset($_REQUEST['link_type']) ? $_REQUEST['link_type'] : '';
        $image_ext = '.png';
        
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
        $old_user = User::model()->findByPk($userId);

        if ($old_user->isDriver == 1) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Driver Exist!',)));
            exit;
        }
        if (strlen($carPlate) == 0 || strlen($brand) == 0 || strlen($model) == 0 || strlen($year) == 0 || strlen($status) == 0 || strlen($account) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing input data',)));
            exit;
        }
        if (strlen($image) == 0 AND strlen($image2) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing photo',)));
            exit;
        }
        $old_vehicle = Vehicle::model()->find('carPlate ="' . $carPlate . '"');
        if (isset($old_vehicle)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Vehicle already exists!',)));
            exit;
        } else {
            $documentName = null;

            if (strlen($document) != 0) {
                $document_ext = pathinfo($document_name, PATHINFO_EXTENSION);
                $documentName = time() . $userId . 'document.' . $document_ext;
                $document_path = Yii::getPathOfAlias(SITE)  . '/upload/car_document/';
                $document_path = $document_path . basename($documentName);
                file_put_contents($document_path, base64_decode($document));
            }
            if (strlen($image) != 0) {
                $imageName = time() . $userId . 'image.' . $image_ext;
                $file_path = Yii::getPathOfAlias(SITE) . '/upload/car/';
                $file_path = $file_path . basename($imageName);
                file_put_contents($file_path, base64_decode($image));
            }
            if (strlen($image2) != 0) {
                $imageName2 = time() . $userId . 'image2.' . $image_ext;
                $file_path2 = Yii::getPathOfAlias(SITE)  . '/upload/car/';
                $file_path2 = $file_path2 . basename($imageName2);
                file_put_contents($file_path2, base64_decode($image2));
            }
            
            
            $new = new Vehicle();
            $new->userId = $userId;
            $new->carPlate = $carPlate;
            $new->brand = $brand;
            $new->model = $model;
            $new->year = $year;
            $new->status = $status;
            $new->userId = $userId;
            $new->document = $documentName;
            $new->dateCreated = date('Y-m-d H:i:s', time());
            $new->save();

            $car_id = $new->id;

            if (strlen($image) != 0) {
                $new_car_image = new VehicleImg();
                $new_car_image->carId = $car_id;
                $new_car_image->image = $imageName;
                $new_car_image->save();
            }
            if (strlen($image2) != 0) {
                $new_car_image = new VehicleImg();
                $new_car_image->carId = $car_id;
                $new_car_image->image = $imageName2;
                $new_car_image->save();
            }
            $driver = new UserDriver();
            $driver->userId = $userId;
            $driver->rateCount = 0;
            $driver->bankAccount = $account;
            $driver->linkType = $link_type;

            $is_auto = Settings::model()->getSettingValueByKey(Globals::AUTO_APPROVE_FOR_REGISTER_DRIVER);
            if($is_auto){
                $driver->inactiveByAdmin = Globals::NEW_DRIVER_ADMIN_APPROVED;
                $driver->isActive = Globals::STATUS_ACTIVE;
            } else {
                $driver->inactiveByAdmin = Globals::NEW_DRIVER_REGISTER;
                $driver->isActive = Globals::STATUS_INACTIVE;
            }

            $driver->save();

            $old_user->isDriver = Globals::STATUS_ACTIVE;
            $old_user->save();

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => '',
                'is_active' => $driver->isActive,
                'message' => 'OK',)));
        }
    }
}