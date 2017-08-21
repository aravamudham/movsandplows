<?php

class TransactionController extends Controller
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
                'actions' => array('index', 'view', 'multipleDelete', 'delete', 'adjustBalance'),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
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
        $this->pageTitle = "Transactions";

        $transaction = new Transaction('search');
        $transaction->unsetAttributes();  // clear any default values
        if (isset($_GET['Transaction'])) {
            $transaction->attributes = $_GET['Transaction'];
        }
        $model = new Transaction();
        $actions = array();
        $actions[Globals::CANCELLATION_ORDER_FEE] = Yii::t('transaction', 'title.cancel.fee');
        $actions[Globals::EXCHANGE_POINT] = Yii::t('transaction', 'title.exchange');
        $actions[Globals::REDEEM_POINT] = Yii::t('transaction', 'title.redeem');
        $actions[Globals::TRANSFER_POINT] = Yii::t('transaction', 'title.transfer');
        $actions[Globals::TRIP_PAYMENT] = Yii::t('transaction', 'title.trip,payment');
        $actions[Globals::PASSENGER_SHARE_BONUS] = Yii::t('transaction', 'title.passenger.share');
        $actions[Globals::DRIVER_SHARE_BONUS] = Yii::t('transaction', 'title.driver.share');
        $actions[Globals::COMMISSION_WHEN_PAYMENT_BY_CASH] = Yii::t('transaction', 'title.driver.commission');

        $this->render('index', array(
            'model' => $model,
            'actions' => $actions,
            'transaction' => $transaction,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Transaction the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Transaction::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Transaction $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'transaction-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAdjustBalance()
    {
        $this->pageTitle = "Adjust User Balance";

        $model = new Transaction();

        if (isset($_POST['Transaction'])) {
            $model->attributes = $_POST['Transaction'];
            if ($model->validate()) {
                $userId = $_POST['Transaction']['userId'];
                $amount = $_POST['Transaction']['amount'];
                $model->id = Globals::generateTransactionId($userId);
                $model->action = Globals::SYSTEM_ADJUST;
                $model->dateCreated = date('Y-m-d H:i:s', time());
                if ($model->save()) {
                    $type = $_POST['Transaction']['type'];
                    $user = User::model()->findByPk($userId);
                    $old_balance = $user->balance;
                    if ($type == '+') {
                        $user->balance = floatval($old_balance) + floatval($amount);
                        $user->save();
                    } elseif ($type == '-') {
                        $user->balance = floatval($old_balance) - floatval($amount);
                        $user->save();
                    }
                }
            }

        }

        $this->render('adjustBalance', array(
            'model' => $model,
        ));
    }

    public function actionMultipleDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/transaction/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'delete_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = Transaction::model()->findByPk($id);
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
