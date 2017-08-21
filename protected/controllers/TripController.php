<?php

class TripController extends Controller
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'update', 'multipleDelete', 'delete'),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->pageTitle = "Trip Detail";

        $model = new SettingsForm();
        $model->loadModel();

        $oldPem = $model->pem;

        if (isset($_POST['SettingsForm'])) {
            $model->attributes = $_POST['SettingsForm'];
            if ($model->validate()) {
                if ($model->save()) {
                    //
                }
            }
        }

        $this->render('view', array(
            'model' => $this->loadModel($id),

            'model2' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $this->pageTitle = "Update Trip";

        if (isset($_POST['Trip'])) {
            $option_way = $_POST['option_way'];
            $formModel = new Trip();
            $formModel->attributes = $_POST['Trip'];
            $model = $this->loadModel($id);

            $driverUser = UserDriver::model()->find('userId = :userId', array('userId' => $model->driverId));
            $driverUser->status = Globals::STATUS_IDLE;
            $driverUser->save();

            switch ($option_way) {
                case 1:
                    //Option 1: Deduct fare from passenger’s balance and Add fare to driver’s balance
                    $amount = $formModel->actualFare;
                    $fee = $amount * Settings::model()->getSettingValueByKey(Globals::DRIVER_EARN);
                    $passenger = User::model()->findByPk($model->passengerId);
                    $driver = User::model()->findByPk($model->driverId);

                    $passenger->balance = $passenger->balance - $amount;
                    $driver->balance = $driver->balance + $fee;
                    $driver->save();
                    $passenger->save();
                    $now = date('Y-m-d H:i:s', time());

                    $newTransaction = new Transaction();
                    $newTransaction->id = Globals::generateTransactionId($model->passengerId);
                    $newTransaction->userId = $model->passengerId;
                    $newTransaction->type = '-';
                    $newTransaction->amount = $amount;
                    $newTransaction->tripId = $model->passengerId;
                    $newTransaction->action = Globals::TRIP_PAYMENT;
                    $newTransaction->dateCreated = $now;
                    $newTransaction->save();

                    $newTransaction2 = new Transaction();
                    $newTransaction2->id = Globals::generateTransactionId($model->driverId);
                    $newTransaction2->userId = $model->driverId;
                    $newTransaction2->type = '+';
                    $newTransaction2->amount = $fee;
                    $newTransaction2->tripId = $model;
                    $newTransaction2->action = Globals::TRIP_PAYMENT;
                    $newTransaction2->dateCreated = $now;
                    $newTransaction2->save();

                    $model->need_help = $formModel->need_help;
                    $model->actualFare = $formModel->actualFare;
                    $model->status = $formModel->status;
                    if ($model->save()) {
                        $this->redirect(array('index'));
                    }
                    break;
                case 2:
                    //Option 2: Deduct commission from driver’s balance
                    $fee = $formModel->actualFare * Settings::model()->getSettingValueByKey(Globals::DRIVER_EARN);

                    $driver = User::model()->findByPk($model->driverId);
                    $driver->balance = $driver->balance - $fee;
                    $driver->save();

                    $now = date('Y-m-d H:i:s', time());
                    $newTransaction = new Transaction();
                    $newTransaction->id = Globals::generateTransactionId($model->passengerId);
                    $newTransaction->userId = $model->passengerId;
                    $newTransaction->type = '-';
                    $newTransaction->amount = $formModel->actualFare;
                    $newTransaction->tripId = $model->passengerId;
                    $newTransaction->action = Globals::COMMISSION_WHEN_PAYMENT_BY_CASH;
                    $newTransaction->dateCreated = $now;
                    $newTransaction->save();

                    $model->need_help = $formModel->need_help;
                    $model->actualFare = $formModel->actualFare;
                    $model->status = $formModel->status;
                    if ($model->save()) {
                        $this->redirect(array('index'));
                    }
                    break;
                case 3:
                    //Option 3: Do nothing to driver and passenger’s balance
                    $model->need_help = $formModel->need_help;
                    $model->actualFare = $formModel->actualFare;
                    $model->status = $formModel->status;
                    if ($model->save()) {
                        $this->redirect(array('index'));
                    }
                    break;
            }
        }

        $this->render('update', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->pageTitle = "Trips";

        /** @var Trip $trip */
        $trip = new Trip('search');
        $trip->unsetAttributes();  // clear any default values

        $model = new Trip();
        if (isset($_GET['Trip'])) {
            $trip->attributes = $_GET['Trip'];
        }else{
            if(isset($_GET['status'])){
                if($_GET['status'] == Globals::TRIP_STATUS_IN_PROGRESS){
                    $trip->status = Globals::TRIP_STATUS_IN_PROGRESS;
                    $model->status = Globals::TRIP_STATUS_IN_PROGRESS;
                }
            }
            if(isset($_GET['today'])){
                if($_GET['today'] == 'true'){
                    $trip->start_date = date('Y-m-d');
                    $trip->end_date = date('Y-m-d');
                    $model->start_date = date('Y-m-d');
                    $model->end_date = date('Y-m-d');
                }
            }
        }
        $status = array();
        $status[Globals::TRIP_STATUS_APPROACHING] = Yii::t('trip', 'title.approaching');
        $status[Globals::TRIP_STATUS_IN_PROGRESS] = Yii::t('trip', 'title.in.process');
        $status[Globals::TRIP_STATUS_PENDING_PAYMENT] = Yii::t('trip', 'title.pending.payment');
        $status[Globals::TRIP_STATUS_FINISH] = Yii::t('trip', 'title.finish');
        $status[Globals::TRIP_STATUS_NEED_HELP] = Yii::t('trip', 'title.need.help');


        $this->render('index', array(
            'model' => $model,
            'status' => $status,
            'trip' => $trip,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Trip the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Trip::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Trip $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'trip-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionMultipleDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/trip/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'delete_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = Trip::model()->findByPk($id);
                            if ($model)
                                $model->delete();
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
}
