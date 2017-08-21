<?php

class UpdatePendingController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    public $layout='//layouts/main';

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
                'actions'=>array('index','view','create','update','approve','reject','download','delete'),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
	}

    public function actionView($id)
    {
        $this->pageTitle = "Pending Update Detail";
        $this->render('view',array(
            'model'=>$this->loadModel($id),
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
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->pageTitle = "Pending Driver Updates";

        $pending=new UpdatePending('search');
        $pending->unsetAttributes();  // clear any default values
        if(isset($_GET['UpdatePending']))
        {
            $pending->attributes=$_GET['UpdatePending'];
        }
        $model = new UpdatePending();

        $this->render('index',array(
            'model'=>$model,
            'pending'=>$pending,
        ));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UpdatePending the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UpdatePending::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UpdatePending $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='update-pending-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionApprove()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/updatePending/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'approve_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = UpdatePending::model()->findByPk($id);
                            if ($model)
                            {
                                $model->safeMove();
                                $model->pushNotification(Globals::STATUS_ACTIVE);
                            }

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

    public function actionReject()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/updatePending/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'reject_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = UpdatePending::model()->findByPk($id);
                            if ($model)
                            {
                                $model->safeDelete();
                                $model->pushNotification(Globals::STATUS_INACTIVE);
                            }
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

    public function actionDownload()
    {
        if (isset($_GET['item_id'])) {
            $vehicle_id = $_GET['item_id'];
            $command = Yii::app()->db->createCommand();
            $file = $command->select('document')
                ->from('update_pending')
                ->where('id=:id', array(':id' => $vehicle_id))
                ->queryRow();
            $path = Yii::getPathOfAlias('site') . DS . UPLOAD_DIR . DS . UPDATE_PENDING_DIR . '\\' . $file['document'];
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
}
