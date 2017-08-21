<?php

class VehicleRegisterAction extends CAction
{
    public function run()
    {
        $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : NULL;
        $carPlate = isset($_REQUEST['carPlate']) ? $_REQUEST['carPlate'] : '';
        $brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : '';
        $model = isset($_REQUEST['model']) ? $_REQUEST['model'] : '';
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';


        if (strlen($carPlate) == 0 || strlen($brand) == 0 || strlen($model) == 0 || strlen($year) == 0 || strlen($status) == 0) {
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
                $documentName = time(). $_FILES['document']['name'];
                $document_path = $_SERVER['DOCUMENT_ROOT'] . '/linkapp/upload/car_document/';
                $document_path = $document_path . basename($documentName);
                move_uploaded_file($_FILES['document']['tmp_name'], $document_path);
            }

            if (isset($_FILES['image'])) {
                $imageName =time(). $_FILES['image']['name'];
                $file_path = $_SERVER['DOCUMENT_ROOT'] . '/linkapp/upload/car/';
                $file_path = $file_path . basename($imageName);
                move_uploaded_file($_FILES['image']['tmp_name'], $file_path);

            }
            if (isset($_FILES['image2'])) {
                $imageName2 =time(). $_FILES['image2']['name'];
                $file_path2 = $_SERVER['DOCUMENT_ROOT'] . '/linkapp/upload/car/';
                $file_path2 = $file_path2 . basename($imageName2);
                move_uploaded_file($_FILES['image2']['tmp_name'], $file_path2);

            }

                $new = new Vehicle();
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

            if(isset($_FILES['image']))
            {
                $new_car_image = new VehicleImg();
                $new_car_image->carId = $car_id;
                $new_car_image->image = $imageName;
                $new_car_image->save();
            }

            if(isset($_FILES['image2']))
            {
                $new_car_image = new VehicleImg();
                $new_car_image->carId = $car_id;
                $new_car_image->image = $imageName2;
                $new_car_image->save();
            }

                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => $car_id,
                    'message' => 'OK',)));
        }
    }
}