<?php

class DriverRegisterAction extends CAction
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
        $link_type = isset($_REQUEST['link_type']) ? $_REQUEST['link_type'] : '';


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
        if (!isset($_FILES['image']) AND !isset($_FILES['image2'])) {
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

            
            if (isset($_FILES['document'])) {
                $original_document_name = $_FILES['document']['name'];
                $document_ext = pathinfo($original_document_name, PATHINFO_EXTENSION);
                $documentName = time() . $userId . 'document.' . $document_ext;
                $document_path = Yii::getPathOfAlias(SITE) . '/upload/car_document/';
                $document_path = $document_path . basename($documentName);
                //move_uploaded_file($_FILES['document']['tmp_name'], $document_path);
                if (move_uploaded_file($_FILES['document']['tmp_name'], $document_path) != true) {
                    ApiController::sendResponse(200, CJSON::encode(array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => 'Have some errors with network connection, try again later!',)));//
                    exit;
                }
            }
            if (isset($_FILES['image'])) {
                $original_image_name = $_FILES['image']['name'];
                $image_ext = pathinfo($original_image_name, PATHINFO_EXTENSION);
                $imageName = time() . $userId . 'image.' . $image_ext;
                $file_path = Yii::getPathOfAlias(SITE) . '/upload/car/';
                $file_path = $file_path . basename($imageName);
                //move_uploaded_file($_FILES['image']['tmp_name'], $file_path);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path) != true) {
                    ApiController::sendResponse(200, CJSON::encode(array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => 'Have some errors with network connection, try again later!',)));//
                    exit;
                }
            }
            if (isset($_FILES['image2'])) {
                $original_image_name2 = $_FILES['image2']['name'];
                $image_ext2 = pathinfo($original_image_name2, PATHINFO_EXTENSION);
                $imageName2 = time() . $userId . 'image2.' . $image_ext2;
                $file_path2 = Yii::getPathOfAlias(SITE) . '/upload/car/';
                $file_path2 = $file_path2 . basename($imageName2);
                //move_uploaded_file($_FILES['image2']['tmp_name'], $file_path2);
                if (move_uploaded_file($_FILES['image2']['tmp_name'], $file_path2) != true) {
                    ApiController::sendResponse(200, CJSON::encode(array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => 'Have some errors with network connection, try again later!',)));//
                    exit;
                }
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

            if (isset($_FILES['image'])) {
                $new_car_image = new VehicleImg();
                $new_car_image->carId = $car_id;
                $new_car_image->image = $imageName;
                $new_car_image->save();
            }
            if (isset($_FILES['image2'])) {
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
            if ($is_auto) {
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