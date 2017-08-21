<?php

class PointExchangeController extends Controller
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
                'actions' => array('index', 'view', 'multipleDelete', 'delete'),
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
        $this->pageTitle = "Exchange";

        $pe = new PointExchange('search');
        $pe->unsetAttributes();  // clear any default values
        if (isset($_GET['PointExchange'])) {
            $pe->attributes = $_GET['PointExchange'];
        }
        $model = new PointExchange();
        $types = array();
        $types[Globals::PAYMENT_METHOD_PAYPAL] = Yii::t('transaction', 'label.paypal');
        $types[Globals::PAYMENT_METHOD_CREDIT] = Yii::t('transaction', 'label.credit');

        $this->render('index', array(
            'model' => $model,
            'paymentMethod' => $types,
            'pe' => $pe,
        ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PointExchange the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PointExchange::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PointExchange $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='point-exchange-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionMultipleDelete()
	{
		if (Yii::app()->request->isPostRequest) {
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_POST['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/pointExchange/index'));
			} else {
				header('Content-type: application/json');
				if (isset($_POST['action']) AND $_POST['action'] == 'delete_many') {
					if (isset($_POST['checkedIds'])) {
						foreach ($_POST['checkedIds'] as $id) {
							$model = PointExchange::model()->findByPk($id);
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
