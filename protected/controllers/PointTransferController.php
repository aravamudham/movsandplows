<?php

class PointTransferController extends Controller
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
				'actions' => array('index', 'view', 'multipleDelete', 'delete' ,'approve','reject','approveOne','rejectOne'),
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
		$this->pageTitle = "Transfer";

		$pt = new PointTransfer('search');
		$pt->unsetAttributes();  // clear any default values
		if (isset($_GET['PointTransfer'])) {
			$pt->attributes = $_GET['PointTransfer'];
		}
		$model = new PointTransfer();

		$this->render('index', array(
			'model' => $model,
			'pt' => $pt,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PointTransfer the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PointTransfer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PointTransfer $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='point-transfer-form')
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
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/pointTransfer/index'));
			} else {
				header('Content-type: application/json');
				if (isset($_POST['action']) AND $_POST['action'] == 'delete_many') {
					if (isset($_POST['checkedIds'])) {
						foreach ($_POST['checkedIds'] as $id) {
							$model = PointTransfer::model()->findByPk($id);
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
	public function actionApprove()
	{
		if (Yii::app()->request->isPostRequest) {
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_POST['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/pointTransfer/index'));
			} else {
				header('Content-type: application/json');
				if (isset($_POST['action']) AND $_POST['action'] == 'approve_many') {
					if (isset($_POST['checkedIds'])) {
						foreach ($_POST['checkedIds'] as $id) {
							$model = PointTransfer::model()->findByPk($id);
							if ($model)
							{
								$userId = $model->userId;
								$user = User::model()->findByPk($userId);
								$balance = $user->balance;
								$amount = $model->amount;

								$receiverId = $model->receiverId;
								$receiver = User::model()->findByPk($receiverId);
								$receiverBalance = $user->balance;
								
								
								if($balance >= $amount)
								{
									$newTransaction = new Transaction();
									$newTransaction->id = Globals::generateTransactionId($userId);
									$newTransaction->userId = $userId;
									$newTransaction->destination = $receiverId;
									$newTransaction->type = '-';
									$newTransaction->amount = $amount;
									$newTransaction->action = Globals::TRANSFER_POINT;
									$newTransaction->dateCreated = date('Y-m-d H:i:s', time());
									$newTransaction->save();


									$newTransaction1 = new Transaction();
									$newTransaction1->id = Globals::generateTransactionId($userId);
									$newTransaction1->userId = $receiverId;
									$newTransaction1->type = '+';
									$newTransaction1->amount = $amount;
									$newTransaction1->destination = $userId;
									$newTransaction1->action = Globals::TRANSFER_POINT;
									$newTransaction1->dateCreated = date('Y-m-d H:i:s', time());
									$newTransaction1->save();

									$receiver->balance = floatval($receiverBalance) + floatval($amount);
									$receiver->save();
									
									$user->balance = floatval($balance) - floatval($amount);
									$user->save();

									$model->pushNotification(Globals::STATUS_ACTIVE);
									$model->delete();

								}
								else
								{
									$model->pushNotification(Globals::STATUS_INACTIVE);
									$model->delete();
								}
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
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/pointTransfer/index'));
			} else {
				header('Content-type: application/json');
				if (isset($_POST['action']) AND $_POST['action'] == 'reject_many') {
					if (isset($_POST['checkedIds'])) {
						foreach ($_POST['checkedIds'] as $id) {
							$model = PointTransfer::model()->findByPk($id);
							if ($model)
							{
								$model->pushNotification(Globals::STATUS_INACTIVE);
								$model->delete();
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

	public function actionApproveOne()
	{
		if (Yii::app()->request->isPostRequest) {
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			header('Content-type: application/json');
			$message = 'OK';
			$success = false;
			if (isset($_POST['requestId'])) {
				//send request document email
				$model = PointTransfer::model()->findByPk($_POST['requestId']);
				if ($model) {
					$senderId = $model->senderId;
					$sender = User::model()->findByPk($senderId);
					$senderBalance = $sender->balance;

					$receiverId = $model->receiverId;
					$receiver = User::model()->findByPk($receiverId);
					$receiverBalance = $receiver->balance;

					$amount = $model->amount;

					if ($senderBalance >= $amount) {
						
						$newTransaction = new Transaction();
						$newTransaction->id = Globals::generateTransactionId($senderId);
						$newTransaction->userId = $senderId;
						$newTransaction->destination = $receiverId;
						$newTransaction->type = '-';
						$newTransaction->amount = $amount;
						$newTransaction->action = Globals::TRANSFER_POINT;
						$newTransaction->dateCreated = date('Y-m-d H:i:s', time());
						$newTransaction->save();


						$newTransaction1 = new Transaction();
						$newTransaction1->id = Globals::generateTransactionId($receiverId);
						$newTransaction1->userId = $receiverId;
						$newTransaction1->type = '+';
						$newTransaction1->amount = $amount;
						$newTransaction1->destination = $senderId;
						$newTransaction1->action = Globals::TRANSFER_POINT;
						$newTransaction1->dateCreated = date('Y-m-d H:i:s', time());
						$newTransaction1->save();


						$sender->balance = floatval($senderBalance) - floatval($amount);
						$sender->save();

						$receiver->balance = floatval($receiverBalance) + floatval($amount);
						$receiver->save();

						$model->pushNotification(Globals::STATUS_ACTIVE);
						$model->delete();

						$success = true;

					} else {
						$model->pushNotification(Globals::STATUS_INACTIVE);
						$model->delete();
						$success = false;
						$message = 'User \'s balance is short, request is rejected';
					}
				}
			}


			echo CJSON::encode(array(
				'success' => $success,
				'message' => $message
			));
			return;
		}

	}

	public function actionRejectOne()
	{
		if (Yii::app()->request->isPostRequest) {
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			header('Content-type: application/json');
			if (isset($_POST['requestId'])) {
				//send request document email
				$model = PointTransfer::model()->findByPk($_POST['requestId']);
				if ($model)
				{
					$model->pushNotification(Globals::STATUS_INACTIVE);
					$model->delete();
				}
			}
			echo CJSON::encode(array(
				'success' => true
			));
			return;
		}

	}
}
