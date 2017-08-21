<?php
class UpdateDriverDataAndroidAction extends CAction
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
        $image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';
        $image2 = isset($_REQUEST['image2']) ? $_REQUEST['image2'] : '';
        $document = isset($_REQUEST['document']) ? $_REQUEST['document'] : '';
        $document_name = isset($_REQUEST['document_name']) ? $_REQUEST['document_name'] : '';
        $cityId = isset($_REQUEST['cityId']) ? $_REQUEST['cityId'] : 0;
        $stateId = isset($_REQUEST['stateId']) ? $_REQUEST['stateId'] : 0;
        $imageAvatar =  isset($_REQUEST['image_avatar']) ? $_REQUEST['image_avatar'] : '';
        $image_ext = '.png';
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


            /** @var UpdatePending $update_pending */
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


            if (strlen($document) != 0) {
                $document_ext = pathinfo($document_name, PATHINFO_EXTENSION);
                $documentName = time() . $userId . 'document.' . $document_ext;
                $document_path = $file_path . basename($documentName);
                file_put_contents($document_path, base64_decode($document));

                $update_pending->document = $documentName;

            }
            if (strlen($image) != 0) {
                $imageName = time() . $userId . 'image.' . $image_ext;
                $imagePath = $file_path . basename($imageName);
                file_put_contents($imagePath, base64_decode($image));

                $update_pending->image = $imageName;

            }
            if (strlen($image2) != 0) {
                $imageName2 = time() . $userId . 'image2.' . $image_ext;
                $imagePath2 = $file_path . basename($imageName2);
                file_put_contents($imagePath2, base64_decode($image2));

                $update_pending->image2 = $imageName2;
            }

            if (strlen($imageAvatar) != 0) {
                $imageAvatarName = time() . 'user' . $image_ext;
                $imageAvatarPath = $file_path . basename($imageAvatarName);
                file_put_contents($imageAvatarPath, base64_decode($imageAvatar));

                $update_pending->image_avt = $imageAvatarName;
            }


            $update_pending->dateCreated = date('Y-m-d H:i:s', time());

            $old_user->cityId = $cityId;
            $old_user->stateId = $stateId;
            $old_user->save();

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