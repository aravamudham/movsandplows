<?php
class UpdateDriverDataAction extends CAction
{
    public function run()
    {

        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $carPlate = isset($_REQUEST['carPlate']) ? $_REQUEST['carPlate'] : '';
        $brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : '';
        $model = isset($_REQUEST['model']) ? $_REQUEST['model'] : '';
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        $fullName = isset($_REQUEST['full_name']) ? $_REQUEST['full_name'] : '';
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
        $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
        $link_type = isset($_REQUEST['link_type']) ? $_REQUEST['link_type'] : '';
        $payoutPaypalAddress = isset($_REQUEST['account']) ? $_REQUEST['account'] : '';


        if (strlen($token) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token missing',)));
            Yii::app()->end();
        }

        $checkToken = LoginToken::model()->find('token = :token', array('token' => $token));
        if (!isset($checkToken)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token mismatch',)));
            Yii::app()->end();
        }

        $userId = $checkToken->userId;


        $old_user = User::model()->findByPk($userId);

        if (isset($old_user)) {

            if($old_user->isDriver != Globals:: STATUS_ACTIVE)
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'User is not driver!',)));
                    Yii::app()->end();
            }

            $vehicle = Vehicle::model()->find('userId = :userId', array('userId' => $userId));

            if(!isset($vehicle))
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => 'Driver does not exist!',)));
                    Yii::app()->end();
            }


            $update_pending = new UpdatePending();
			$update_pending->userId = $userId;
            $update_pending->brand = $brand;
            $update_pending->carPlate = $carPlate;
            $update_pending->model = $model;
            $update_pending->year = $year;
            $update_pending->status = $status;
            $update_pending->phone = $phone;
            $update_pending->fullName = $fullName;
            $update_pending->address= $address;
            $update_pending->description= $description;
            $update_pending->linkType = $link_type;
            $update_pending->payoutPaypalAddress = $payoutPaypalAddress;
			
			$file_path = Yii::getPathOfAlias(SITE)  . '/upload/update_pending/';


            if (isset($_FILES['document'])) {
                $original_document_name = $_FILES['document']['name'];
                $document_ext = pathinfo($original_document_name, PATHINFO_EXTENSION);
                $documentName = time() . $userId . 'document.' . $document_ext;
                $document_path = $file_path . basename($documentName);
                //move_uploaded_file($_FILES['document']['tmp_name'], $document_path);

                if (move_uploaded_file($_FILES['document']['tmp_name'], $document_path) != true) {
                    ApiController::sendResponse(200, CJSON::encode(array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => 'Have some errors with network connection, try again later!',)));//
                    exit;
                }

                $update_pending->document = $documentName;

            }
            if (isset($_FILES['image'])) {
                $original_image_name = $_FILES['image']['name'];
                $image_ext = pathinfo($original_image_name, PATHINFO_EXTENSION);
                $imageName = time() . $userId . 'image.' . $image_ext;
                $imagePath = $file_path . basename($imageName);
                //move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath) != true) {
                    ApiController::sendResponse(200, CJSON::encode(array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => 'Have some errors with network connection, try again later!',)));//
                    exit;
                }

                $update_pending->image = $imageName;

            }
            if (isset($_FILES['image2'])) {
                $original_image_name2 = $_FILES['image2']['name'];
                $image_ext2 = pathinfo($original_image_name2, PATHINFO_EXTENSION);
                $imageName2 = time() . $userId . 'image2.' . $image_ext2;
                $imagePath2 = $file_path . basename($imageName2);
                //move_uploaded_file($_FILES['image2']['tmp_name'], $imagePath2);
                if (move_uploaded_file($_FILES['image2']['tmp_name'], $imagePath2) != true) {
                    ApiController::sendResponse(200, CJSON::encode(array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => 'Have some errors with network connection, try again later!',)));//
                    exit;
                }

                $update_pending->image2 = $imageName2;
            }
            if (isset($_FILES['image_avatar'])) {
                $original_image_avt_name = $_FILES['image_avatar']['name'];
                $image_avt_ext = pathinfo($original_image_avt_name, PATHINFO_EXTENSION);
                $imageAvtName = time() . 'user.' . $image_avt_ext;
                $imageAvtPath = $file_path . basename($imageAvtName);
                //move_uploaded_file($_FILES['image_avatar']['tmp_name'], $imageAvtPath);
                if (move_uploaded_file($_FILES['image_avatar']['tmp_name'], $imageAvtPath) != true) {
                    ApiController::sendResponse(200, CJSON::encode(array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => 'Have some errors with network connection, try again later!',)));//
                    exit;
                }

                $update_pending->image_avt = $imageAvtName;
            }

            $update_pending->dateCreated = date('Y-m-d H:i:s', time());

            if ($update_pending->save()) {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => '',
                    'message' => 'OK',)));
            }


        } else {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'User does not exist!',)));
            exit;
        }


    }
}