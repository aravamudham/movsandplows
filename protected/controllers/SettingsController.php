<?php

class SettingsController extends Controller
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
				'actions'=>array('index','pushNotification'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    
	public function actionIndex()
	{
        $this->pageTitle = Yii::t('settings', 'title.settings');

        $model = new SettingsForm();
        $model->loadModel();

        $oldPem = $model->pem;

        if(isset($_POST['SettingsForm'])){
            $model->attributes = $_POST['SettingsForm'];
            $uploadedFile = CUploadedFile::getInstance($model, 'pem');
            if($model->validate()) {
                if (is_object($uploadedFile) && get_class($uploadedFile) === 'CUploadedFile') {
                    $name_file = time().'.'.$uploadedFile->extensionName;
                    $model->pem = $name_file;
                }
                else
                    $model->pem = $oldPem;

                if ($model->save()) {
                    if (isset($uploadedFile) && $uploadedFile->size > 0) {
                        $uploadFolder = $this->uploadFolder . DIRECTORY_SEPARATOR . 'pem';
                        if (!file_exists($uploadFolder)) {
                            mkdir($uploadFolder, 0777, true);
                        }
                        $uploadedFile->saveAs($uploadFolder . DIRECTORY_SEPARATOR . $model->pem);  // image
                        $oThumb = $uploadFolder . DIRECTORY_SEPARATOR . $oldPem;
                        if (file_exists($oThumb) && is_file($oThumb)) {
                            unlink($oThumb);
                        }
                    }
                }
            }
        }

        $this->render('index', array(
            'model' => $model,
        ));
	}

	/**
	 * Performs the AJAX validation.
	 * @param Settings $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
		public function actionPushNotification()
	{
		$this->pageTitle = "Push Notification";

		$model = new Settings();

		if (isset($_POST['Settings'])) {
				$receiver = $_POST['Settings']['receiver'];
				$message = $_POST['Settings']['message'];
			$adevices = array();
			$idevices = array();
			if($receiver == 'all'){
				$devices = Device::model()->findAll();
				foreach ($devices as $device)
				{
					if($device->type == Globals::DEVICE_TYPE_ANDROID)
						$adevices[] = $device->gcm_id;
					if($device->type == Globals::DEVICE_TYPE_IOS)
						$idevices[] = $device->gcm_id;
				}

			}
			elseif($receiver == 'driver')
			{
				$sql = "select * from user_driver ud INNER JOIN device d ON ud.userId = d.userId where ud.isActive=".Globals::STATUS_ACTIVE;
				$devices = Yii::app()->db->createCommand($sql)->queryAll();

				foreach ($devices as $device)
				{
					if($device['type'] == Globals::DEVICE_TYPE_ANDROID)
						$adevices[] = $device['gcm_id'];
					if($device['type'] == Globals::DEVICE_TYPE_IOS)
						$idevices[] = $device['gcm_id'];
				}
			}
			$msg = array
			(
				'data' => array(),
				'action' => 'promotion',
				'body' => $message
			);

			if(count($adevices)!= 0)
			{
				Globals::pushAndroid($adevices,$msg);
			}
			if(count($idevices)!= 0)
			{
				Globals::pushIos($idevices,$msg);
			}

		}

		$this->render('pushNotification', array(
			'model' => $model,
		));
	}

}
