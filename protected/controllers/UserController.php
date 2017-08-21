<?php

class UserController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('driverOnline','index', 'view', 'create', 'update', 'active', 'inactive', 'multipleDelete', 'requestDocument', 'download', 'delete', 'activeDriver', 'inactiveDriver'),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }


    public function actionView($id)
    {
        $this->pageTitle = "User Detail";
        //$vehicles = Vehicle::model()->searchByUserId($id);

        if (isset($_POST['User'])) {
            $userTemp = new User();
            $userTemp->attributes = $_POST['User'];
            $model = $this->loadModel($id);
            $model->isActive = $userTemp->isActive;
            $model->save();
        }

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'driverData' => $this->loadDriver($id),
            'passengerData' => $this->loadPassenger($id),
            //'vehicleData'=>  $vehicles
        ));
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->safeDelete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


    public function actionIndex()
    {
        $this->pageTitle = "Users";

        $user = new User('search');
        $user->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $model = new User();
        $status = array();
        $status[Globals::STATUS_ACTIVE] = Yii::t('common', 'label.active');
        $status[Globals::STATUS_INACTIVE] = Yii::t('common', 'label.inactive');

        $types = array();
        $types[Globals::USER_TYPE_DRIVER] = Yii::t('common', 'title.driver');
        $types[Globals::USER_TYPE_DRIVER_PENDING] = Yii::t('common', 'title.driver.pending');
        $types[Globals::NEW_DRIVER_REGISTER_CUSTOM_INDEX] = Yii::t('common', 'title.driver.newRegister');
        $types[Globals::INACTIVE_BY_ADMIN_CUSTOM_INDEX] = Yii::t('common', 'title.driver.inactiveByAdmin');

        $this->render('index', array(
            'model' => $model,
            'status' => $status,
            'user' => $user,
            'types' => $types
        ));
    }

    public function actionDriverOnline()
    {
        $this->pageTitle = "Online Drivers";

        $user = new User('search');
        $user->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }
        $user->driver_is_online = 1;
        $model = new User();
        $status = array();
        $status[Globals::STATUS_ACTIVE] = Yii::t('common', 'label.active');
        $status[Globals::STATUS_INACTIVE] = Yii::t('common', 'label.inactive');

        $types = array();
        $types[Globals::USER_TYPE_DRIVER] = Yii::t('common', 'title.driver');
        $types[Globals::USER_TYPE_DRIVER_PENDING] = Yii::t('common', 'title.driver.pending');

        $this->render('driverOnline', array(
            'model' => $model,
            'status' => $status,
            'user' => $user,
            'types' => $types
        ));
    }


    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadDriver($id)
    {
        $model = UserDriver::model()->find('userId =' . $id);
        return $model;
    }

    public function loadPassenger($id)
    {
        $model = UserPassenger::model()->find('userId =' . $id);
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    public function actionActive()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/user/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'active_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = User::model()->findByPk($id);
                            $model->isActive = Globals::STATUS_ACTIVE;
                            $model->save();
                        }
                    }
                    echo CJSON::encode(array(
                        'success' => true
                    ));
                    return;

                }
                echo CJSON::encode(array(
                    'success' => false,
                    'message' => Yii::t('common', 'errorMessage.invalidRequest'),
                ));
                return;
            }

        } else
            throw new CHttpException(400, Yii::t('conmon', 'errorMessage.invalidRequest'));
    }

    public function actionInactive()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/user/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'inactive_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = User::model()->findByPk($id);
                            $model->isActive = Globals::STATUS_INACTIVE;
                            $model->save();
                        }
                    }
                    echo CJSON::encode(array(
                        'success' => true
                    ));
                    return;

                }
                echo CJSON::encode(array(
                    'success' => false,
                    'message' => Yii::t('common', 'errorMessage.invalidRequest'),
                ));
                return;
            }

        } else
            throw new CHttpException(400, Yii::t('conmon', 'errorMessage.invalidRequest'));
    }

    public function actionMultipleDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/user/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'delete_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = User::model()->findByPk($id);
                            if ($model)
                                $model->safeDelete();
                        }
                    }
                    echo CJSON::encode(array(
                        'success' => true
                    ));
                    return;

                }
                echo CJSON::encode(array(
                    'success' => false,
                    'message' => Yii::t('common', 'errorMessage.invalidRequest'),
                ));
                return;
            }

        } else
            throw new CHttpException(400, Yii::t('conmon', 'errorMessage.invalidRequest'));
    }

    public function actionRequestDocument()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            header('Content-type: application/json');
            if (isset($_POST['userId'])) {
                //send request document email
            }
            echo CJSON::encode(array(
                'success' => true
            ));
            return;
        }

    }

    public function actionDownload()
    {
        if (isset($_GET['user_id'])) {
            $vehicle_id = $_GET['user_id'];
            $command = Yii::app()->db->createCommand();
            $file = $command->select('document')
                ->from('user')
                ->where('id=:id', array(':id' => $vehicle_id))
                ->queryRow();
            $path = Yii::getPathOfAlias('site') . DS . 'upload' . DS . 'user_document' . '\\' . $file['document'];
            // replace \ to /
            $path = str_replace('\\', '/', $path);
            if (file_exists($path)) {
                // IE <= 8: preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])
                // IE all versions: preg_match('/(?i)msie/',$_SERVER['HTTP_USER_AGENT'])
                $content = file_get_contents($path);

                Yii::app()->request->sendFile($file['document'], $content, null, true);
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            exit;
        }

    }

    public function actionInactiveDriver($id)
    {
        /** @var UserDriver $driverData */
        $driverData = UserDriver::model()->findByPk($id);
        $driverData->inactiveByAdmin = Globals::INACTIVE_BY_ADMIN;
        $driverData->isActive = Globals::STATUS_INACTIVE;
        $driverData->save();
        $this->redirect(Yii::app()->createUrl('user/view', array('id' => $driverData->userId)));
    }

    public function actionActiveDriver($id)
    {
        /** @var UserDriver $driverData */
        $driverData = UserDriver::model()->findByPk($id);
        $driverData->inactiveByAdmin = Globals::NEW_DRIVER_ADMIN_APPROVED;
        $driverData->isActive = Globals::STATUS_ACTIVE;
        $driverData->save();

        /** @var User $user */
        $user = User::model()->findByPk($driverData->userId);
        /** @var Device $device */
        $device = Device::model()->find('userId = ' . $user->id);
        if (isset($device)) {
            $iDevices = array();
            $iDevices[] = $device->gcm_id;
            $message = "Hello " . $user->fullName . "! Your account has become a driver. Please restart applicaion to apply the change.";
            $msg = array
            (
                'data' => array(),
                'action' => 'driverApproved',
                'body' => $message
            );

            if ($device->type == Globals::DEVICE_TYPE_IOS) {
                Globals::pushIos($iDevices, $msg);
            } else {
                Globals::pushAndroid($iDevices, $msg);
            }
        }

        $adminMail = Settings::model()->getSettingValueByKey(Globals::ADMIN_EMAIL);
        $message = new YiiMailMessage;
        $message->view = 'notifyDriverApproved';
        $params = array('name' => $user->fullName);
        $message->setBody($params, 'text/html');
        $message->subject = Yii::app()->name . ' - Your request has been approved.';
        $message->addTo($user->email);
        $message->from = $adminMail;
        Yii::app()->mail->send($message);

        $this->redirect(Yii::app()->createUrl('user/view', array('id' => $driverData->userId)));
    }
}
