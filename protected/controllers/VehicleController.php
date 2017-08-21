<?php

class VehicleController extends Controller
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
                'actions' => array('index', 'view', 'create', 'update', 'active', 'inactive', 'multipleDelete', 'requestDocument', 'download', 'delete'),
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
        $this->pageTitle = "Vehicle Detail";

        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }


        public function actionUpdate($id)
    {
        $this->pageTitle = "Update Vehicle";

        $model = $this->loadModel($id);
        $oldDoc = $model->document;
        $oldImages = $model->images;
        $userId = Yii::app()->user->id;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Vehicle'])) {
            $model->attributes = $_POST['Vehicle'];
            $uploadedFile = CUploadedFile::getInstance($model, 'document');
            $images = CUploadedFile::getInstancesByName('images');


           if (is_object($uploadedFile) && get_class($uploadedFile) === 'CUploadedFile') {
                $document_ext = $uploadedFile->extensionName;
                $documentName = time() . $userId . 'document.' . $document_ext;
                $model->document = $documentName;
            } else
            {
                $documentName = $oldDoc;
                $model->document = $documentName;
            }


            if ($model->save()) {
                if (isset($uploadedFile) && $uploadedFile->size > 0) {
                    $uploadFolder = $this->uploadFolder . DS . CAR_DOCUMENT_DIR;
                    if (!file_exists($uploadFolder)) {
                        mkdir($uploadFolder, 0777, true);
                    }
                    $uploadedFile->saveAs($uploadFolder . DS . $documentName);
                    $oDoc = $uploadFolder . DS . $oldDoc;
                    if (file_exists($oDoc) && is_file($oDoc)) {
                        unlink($oDoc);
                    }
                }

                if (isset($images) && count($images) > 0) {
                    $imageUploadFolder = $this->uploadFolder . DS . CAR_DIR;
                    if (count($oldImages) != 0) {
                        foreach ($oldImages as $image) {
							if(file_exists($imageUploadFolder . DS . $image->image))
                            unlink($imageUploadFolder . DS . $image->image);
                        }
                        VehicleImg::model()->deleteAll('carId =' . $id);
                    }

                    $i = 0;
                    foreach ($images as $item) {
                        $image_ext = $item->extensionName;
                        $imageName = time() . $userId . 'image'.$i.'.'. $image_ext;
                        $i += 1;
                        if ($item->saveAs($imageUploadFolder . DS . $imageName)) {
                            $new_car_image = new VehicleImg();
                            $new_car_image->carId = $id;
                            $new_car_image->image = $imageName;
                            $new_car_image->save();

                        }
                    }
                }

                $this->redirect(array('view', 'id' => $model->id));

            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->safeDelete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->pageTitle = "Vehicles";

        $vehicle = new Vehicle('search');
        $vehicle->unsetAttributes();  // clear any default values
        if (isset($_GET['Vehicle'])) {
            $vehicle->attributes = $_GET['Vehicle'];

        }
        $model = new Vehicle();
        $status = array();
        $status[Globals::STATUS_ACTIVE] = Yii::t('common', 'label.active');
        $status[Globals::STATUS_INACTIVE] = Yii::t('common', 'label.inactive');

        $issetDocument = array();
        $issetDocument[Globals::DOCUMENT] = Yii::t('common', 'label.document');
        $issetDocument[Globals::MISSING_DOCUMENT] = Yii::t('common', 'label.missing.document');

        $this->render('index', array(
            'model' => $model,
            'status' => $status,
            'vehicle' => $vehicle,
            'issetDocument' => $issetDocument
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Vehicle the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Vehicle::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Vehicle $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'vehicle-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    public function actionActive()
    {
        if (Yii::app()->request->isPostRequest) {
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/vehicle/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'active_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = Vehicle::model()->findByPk($id);
                            $model->status = Globals::STATUS_ACTIVE;
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
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/vehicle/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'inactive_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = Vehicle::model()->findByPk($id);
                            $model->status = Globals::STATUS_INACTIVE;
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
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/vehicle/index'));
            } else {
                header('Content-type: application/json');
                if (isset($_POST['action']) AND $_POST['action'] == 'delete_many') {
                    if (isset($_POST['checkedIds'])) {
                        foreach ($_POST['checkedIds'] as $id) {
                            $model = Vehicle::model()->findByPk($id);
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
            $message = 'Missing userId';
            if (isset($_POST['userId'])) {
                //send request document email
                $user = User::model()->findByPk($_POST['userId']);
                $toEmail = $user->email;
                $view = 'requestDocument';
                $title = 'Document Request';
                $data = array(
                    'user'=>$user->fullName
                );
                $send = Globals::senEmail($toEmail, $view, $title, $data);
                if ($send)
                    $message = '';
                else
                    $message = 'Request fail!';
            }


            echo CJSON::encode(array(
                'success' => true,
                'message' => $message
            ));
            return;
        }

    }

    public function actionDownload()
    {
        if (isset($_GET['vehicle_id'])) {
            $vehicle_id = $_GET['vehicle_id'];
            $command = Yii::app()->db->createCommand();
            $file = $command->select('document')
                ->from('vehicle')
                ->where('id=:id', array(':id' => $vehicle_id))
                ->queryRow();
            $path = Yii::getPathOfAlias('site') . DS . 'upload' . DS . 'car_document' . '\\' . $file['document'];
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
