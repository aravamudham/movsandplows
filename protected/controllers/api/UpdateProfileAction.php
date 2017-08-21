<?php

//http://localhost/matching/index.php/api/register?name=tony&email=tony@gmail.com&dob=1992-12-11&gender=2&password=123456
class UpdateProfileAction extends CAction
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

        $dob = isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '';
        $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        $cityId = isset($_REQUEST['cityId']) ? $_REQUEST['cityId'] : 0;
        $stateId = isset($_REQUEST['stateId']) ? $_REQUEST['stateId'] : 0;
        $typeDevice = isset($_REQUEST['type_device']) ? $_REQUEST['type_device'] : '';
        $payoutPaypalAddress = isset($_REQUEST['account']) ? $_REQUEST['account'] : '';


        /** @var User $old_user */
        $old_user = User::model()->findByPk($userId);


        if (isset($old_user)) {
            $old_user->dob = $dob;
            $old_user->description = $description;
            $old_user->address = $address;
            $old_user->phone = $phone;
            $old_user->payoutPaypalAddress = $payoutPaypalAddress;
            $old_user->dateCreated = date('Y-m-d H:i:s', strtotime('Now'));
            $old_user->stateId = $stateId;
            $old_user->cityId = $cityId;
            if ($old_user->save()) {
                /** @var User $new_user */
                $new_user = User::model()->findByPk($userId);

                if ($typeDevice == \Globals::DEVICE_TYPE_ANDROID) {
                    //change image
                    if (isset($_REQUEST['image']) && strlen($_REQUEST['image']) > 0) {
                        $oldImage = Yii::getPathOfAlias(SITE) . '/upload/user/' . $old_user->image;
                        $image = $_REQUEST['image'];
                        $image_ext = '.png';
                        $imageName = time() . 'user' . $image_ext;
                        $file_path = Yii::getPathOfAlias(SITE) . '/upload/user/';
                        $file_path = $file_path . basename($imageName);
                        file_put_contents($file_path, base64_decode($image));
                        $old_user->image = $imageName;
                        $old_user->save();
                        if (is_file($oldImage)) {
                            unlink($oldImage);
                        }
                    }
                } elseif ($typeDevice == \Globals::DEVICE_TYPE_IOS) {
                    $oldImage = Yii::getPathOfAlias(SITE) . '/upload/user/' . $old_user->image;
                    if (isset($_FILES['image'])) {
                        $original_image_name = $_FILES['image']['name'];
                        $image_ext = pathinfo($original_image_name, PATHINFO_EXTENSION);
                        $imageName = time() . 'user.' . $image_ext;
                        $file_path = Yii::getPathOfAlias(SITE) . '/upload/user/';
                        $file_path = $file_path . basename($imageName);
                        move_uploaded_file($_FILES['image']['tmp_name'], $file_path);
                        $old_user->image = $imageName;
                        $old_user->save();
                        if (is_file($oldImage)) {
                            unlink($oldImage);
                        }
                    }
                }

                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => array(
                        'id' => $new_user->id,
                        'name' => $new_user->fullName,
                        'email' => $new_user->email,
                        'gender' => $new_user->gender,
                        'image' => $new_user->image,
                        'address' => $new_user->address,
                        'phone' => $new_user->phone,
                        'description' => $new_user->description,
                        'dob' => $new_user->dob,
                        'stateId' => $new_user->stateId,
                        'cityId' => $new_user->cityId,
                        'account' => $new_user->payoutPaypalAddress,
                    ),
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