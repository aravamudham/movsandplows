<?php

class DriverRegisterController extends Controller
{
    public $layout="//layouts/login";

    public function actionIndex()
    {
        $this->pageTitle = 'Validate Email';
        $model = new CheckEmailForm();
        if (isset($_POST['CheckEmailForm'])) {
            $model->attributes = $_POST['CheckEmailForm'];
            if ($model->validate()) {
                $email = $_POST['CheckEmailForm']['email'];
                $check = User::model()->find('email ="' . $email . '"');
                if (($check->isDriver == Globals::STATUS_INACTIVE)) {
                    $token = md5(strtotime('Now'));
                    $check->token = $token;
                    $check->save();

                    $adminMail = Settings::model()->getSettingValueByKey(Globals::ADMIN_EMAIL);
                    $message = new YiiMailMessage;
                    $message->view = 'driverRegister';
                    $params = array('token' => $token, 'name'=>$check->fullName);
                    $message->setBody($params, 'text/html');
                    $message->subject = Yii::app()->name.' - Your driver registration token';
                    $message->addTo($email);
                    $message->from = $adminMail;
                    Yii::app()->mail->send($message);

                    $this->redirect(array('register', 'email' => $email));
                } else {
                    $this->redirect(array('sorry', 'error_code' => 0));
                }
            }
        }


        $this->render('check-email', array('model' => $model));
    }

    public function actionRegister($email)
    {
        $this->pageTitle = 'Register';
        $check = User::model()->find('email ="' . $email . '"');

        $userId = $check->id;


        $driver = new DriverForm;

        if (isset($_POST['DriverForm'])) {
            $driver->attributes = $_POST['DriverForm'];

            $token = $_POST['DriverForm']['token'];
            if($check->token != $token) {
                $this->redirect(array('sorry', 'error_code' => 1));
            }

            if ($driver->validate()) {
                $carPlate = $_POST['DriverForm']['carPlate'];
                $brand = $_POST['DriverForm']['brand'];
                $car_model = $_POST['DriverForm']['model'];
                $year = $_POST['DriverForm']['year'];
                //$status = $_POST['DriverForm']['status'];
                //$account = $_POST['DriverForm']['account'];

                $uploadedFile1 = CUploadedFile::getInstance($driver, 'image1');
                $uploadedFile2 = CUploadedFile::getInstance($driver, 'image2');
                //$uploadedFile3 = CUploadedFile::getInstance($driver, 'document');
                $time_string = time();

                $ext1 = $uploadedFile1->getExtensionName();
                $imageName1 = $time_string . $userId . 'image.' . $ext1;
                $uploadedFile1->saveAs($this->uploadFolder.DS.CAR_DIR.DS.$imageName1);

                $ext2 = $uploadedFile2->getExtensionName();
                $imageName2 = $time_string . $userId . 'image2.' . $ext2;
                $uploadedFile2->saveAs($this->uploadFolder.DS.CAR_DIR.DS.$imageName2);

                //$ext3 = $uploadedFile3->getExtensionName();
                //$imageName3 = $time_string . $userId . 'document.' . $ext3;
                //$uploadedFile3->saveAs($this->uploadFolder.DS.CAR_DOCUMENT_DIR.DS.$imageName3);

                $new = new Vehicle();
                $new->userId = $userId;
                $new->carPlate = $carPlate;
                $new->brand = $brand;
                $new->model = $car_model;
                $new->year = $year;
                //$new->status = $status;
                $new->userId = $userId;
                //$new->document = $imageName3;
                $new->dateCreated = date('Y-m-d H:i:s', $time_string);
                $new->save();

                $car_id = $new->id;

                $new_car_image = new VehicleImg();
                $new_car_image->carId = $car_id;
                $new_car_image->image = $imageName1;
                $new_car_image->save();

                $new_car_image = new VehicleImg();
                $new_car_image->carId = $car_id;
                $new_car_image->image = $imageName2;
                $new_car_image->save();

                $driver = new UserDriver();
                $driver->userId = $userId;
                $driver->rateCount = 0;
                //$driver->bankAccount = $account;
                $driver->isActive = Globals::STATUS_INACTIVE;
                $driver->save();

                $check->isDriver = Globals::STATUS_ACTIVE;
                $check->token = NULL;
                $check->save();

                $this->redirect('success');

            }
        }

        $this->render('register', array('model' => $driver));
    }


    public function actionSuccess()
    {

        $this->pageTitle = 'Success';
        $message = 'Success';
        $this->render('result', array('message' => $message));
    }

    public function actionSorry($error_code)
    {
        $this->pageTitle = 'Sorry';

        if ($error_code == 0) {
            $message = 'You registered as a driver and you can not do this again';
        } elseif ($error_code == 1) {
            $message = 'Token mismatch, request fail';
        }
        else
        {
            $message = 'An error occurred, please try again';
        }
        $this->render('result', array('message' => $message, 'error_code'=>$error_code));
    }

}