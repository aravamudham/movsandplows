<?php

/**
 * This is the model class for table "update_pending".
 *
 * The followings are the available columns in table 'update_pending':
 * @property integer $id
 * @property integer $userId
 * @property string $carPlate
 * @property string $brand
 * @property string $model
 * @property string $year
 * @property string $status
 * @property string $document
 * @property string $image
 * @property string $image2
 * @property string $image_avt
 * @property string $phone
 * @property string $dateCreated
 * @property string $fullName
 * @property string $address
 * @property string $description
 * @property string $payoutPaypalAddress
 * @property string $linkType
 */
class UpdatePending extends CActiveRecord
{
    public $start_date;
    public $end_date;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'update_pending';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId', 'numerical', 'integerOnly' => true),
            array('carPlate, brand, model, status, image, image2, fullName, image_avt', 'length', 'max' => 255),
            array('year', 'length', 'max' => 4),
            array('linkType', 'length', 'max' => 10),
            array('phone', 'length', 'max' => 40),
            array('address, payoutPaypalAddress', 'length', 'max' => 255),
            array('description', 'length', 'max' => 2000),
            array('document, dateCreated', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, carPlate, brand, model, year, status, document, image, image2, address, description , image_avt, phone, dateCreated, start_date, end_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userId' => 'User',
            'carPlate' => 'Car Plate',
            'brand' => 'Brand',
            'model' => 'Model',
            'year' => 'Year',
            'status' => 'Status',
            'document' => 'Document',
            'image' => 'Image',
            'image2' => 'Image2',
            'image_avt' => 'Avatar',
            'phone' => 'Phone',
            'dateCreated' => 'Date Created',
            'fullName' => 'New Full Name',
            'address' => 'Address',
            'description' => 'Description',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('userId', $this->userId);
        $criteria->compare('carPlate', $this->carPlate, true);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('model', $this->model, true);
        $criteria->compare('year', $this->year, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('document', $this->document, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('image2', $this->image2, true);
        $criteria->compare('image_avt', $this->image_avt, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('dateCreated', $this->dateCreated, true);
        $criteria->compare('fullName', $this->fullName, true);
        $criteria->compare('address', $this->address, true);
        if (strlen($this->start_date) != 0) {
            $criteria->addCondition('date(dateCreated) >= \'' . $this->start_date . '\'');
        }
        if (strlen($this->end_date) != 0) {
            $criteria->addCondition('date(dateCreated) <= \'' . $this->end_date . '\'');
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UpdatePending the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDriverName($userId = FALSE)
    {
        if ($userId === FALSE) {
            $userId = $this->userId;
        }
        $check = User::model()->findByPk($userId);
        return isset($check) ? $check->fullName : '';
    }

    public function safeDelete()
    {
        $transaction = FALSE;
        if (!Yii::app()->db->currentTransaction) {
            $transaction = Yii::app()->db->beginTransaction();
        }
        try {
            if (strlen($this->image_avt) > 0) {
                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image_avt))
                    unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image_avt);
            }
            if (strlen($this->image) > 0) {
                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image))
                    unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image);
            }
            if (strlen($this->image2) > 0) {
                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image2))
                    unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image2);
            }
            if (strlen($this->document) > 0) {
                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->document))
                    unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->document);
            }
            $this->delete();
            $transaction AND $transaction->commit();
        } catch (Exception $e) // an exception is raised if a query fails
        {
            $transaction AND $transaction->rollback();
        }
    }

    public function safeMove()
    {
        $transaction = FALSE;
        if (!Yii::app()->db->currentTransaction) {
            $transaction = Yii::app()->db->beginTransaction();
        }
        try {

            $car = Vehicle::model()->find('userId = :userId', array('userId' => $this->userId));
            /** @var User $user */
            $user = User::model()->findByPk($this->userId);
            /** @var UserDriver $userDriver */
            $userDriver = UserDriver::model()->find('userId ='.$this->userId);
            $car_id = $car->id;

            //replace linkType
            if (strlen($this->linkType) > 0) {
                $userDriver->linkType = $this->linkType;
                $userDriver->save();
            }

            //replace full name
            if (strlen($this->fullName) > 0) {
                $user->fullName = $this->fullName;
                $user->save();
            }

            //replace address
            if (strlen($this->address) > 0) {
                $user->address = $this->address;
                $user->save();
            }

            //replace desciption
            if (strlen($this->description) > 0) {
                $user->description = $this->description;
                $user->save();
            }

            //replace avatar
            if (strlen($this->image_avt) > 0) {
                $oldAvatar = $user->image;

                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image_avt)) {
                    rename(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image_avt, Yii::getPathOfAlias(UPLOAD_DIR) . DS . USER_DIR . DS . $this->image_avt);
                    $user->image = $this->image_avt;
                    $user->save();
                    if (is_file(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $oldAvatar)) {
                        unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $oldAvatar);
                    }
                }
            }
            /////////////////////////
            if ((strlen($this->image) > 0) && (strlen($this->image2) > 0)) {
                $oldImages = VehicleImg::model()->findAll('carId =' . $car_id);
                if (count($oldImages) != 0) {
                    foreach ($oldImages as $image) {
                        unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . CAR_DIR . DS . $image->image);
                    }
                    VehicleImg::model()->deleteAll('carId =' . $car_id);
                }

                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image)) {
                    rename(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image, Yii::getPathOfAlias(UPLOAD_DIR) . DS . CAR_DIR . DS . $this->image);
                    $new_car_image = new VehicleImg();
                    $new_car_image->carId = $car_id;
                    $new_car_image->image = $this->image;
                    $new_car_image->save();
                }
                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image2)) {
                    rename(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->image2, Yii::getPathOfAlias(UPLOAD_DIR) . DS . CAR_DIR . DS . $this->image2);
                    $new_car_image2 = new VehicleImg();
                    $new_car_image2->carId = $car_id;
                    $new_car_image2->image = $this->image2;
                    $new_car_image2->save();
                }
            }
            if (strlen($this->document) > 0) {
                $oldDoc = $car->document;
                if (strlen($oldDoc) != 0) {
                    unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . CAR_DOCUMENT_DIR . DS . $oldDoc);
                }

                if (file_exists(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->document)) {
                    rename(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $this->document, Yii::getPathOfAlias(UPLOAD_DIR) . DS . CAR_DOCUMENT_DIR . DS . $this->document);
                    $car->document = $this->document;
                }
            }
            $car->userId = $this->userId;
            $car->carPlate = $this->carPlate;
            $car->brand = $this->brand;
            $car->model = $this->model;
            $car->year = $this->year;
            $car->status = $this->status;
            $car->userId = $this->userId;
            $car->dateCreated = date('Y-m-d H:i:s', time());
            $car->save();

            $user->phone = $this->phone;
            $user->save();

            $this->delete();

            $transaction AND $transaction->commit();
        } catch (Exception $e) // an exception is raised if a query fails
        {
            $transaction AND $transaction->rollback();
        }
    }


    public function deleteByUserId($userId)
    {
        $pending = UpdatePending::model()->find('userId = :userId', array('userId' => $userId));
        if (isset($pending)) {
            if (isset($pending->image))
                unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $pending->image);
            if (isset($pending->image2))
                unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $pending->image2);
            if (isset($pending->document))
                unlink(Yii::getPathOfAlias(UPLOAD_DIR) . DS . UPDATE_PENDING_DIR . DS . $pending->document);
            $pending->delete();
        }
    }

    public function pushNotification($type)
    {
        $message = '';
        if ($type == Globals::STATUS_ACTIVE) {
            $message = Yii::t('common', 'message.update.approve');
        } elseif ($type == Globals::STATUS_INACTIVE) {
            $message = Yii::t('common', 'message.update.reject');

        }

        $registrationIDs = array();
        $device = Device::model()->find('userId =' . $this->userId);
        $msg = array
        (
            'data' => array(),
            'action' => 'updateApproval',
            'body' => $message
        );
        if ($device->type == Globals::DEVICE_TYPE_ANDROID) {
            array_push($registrationIDs, $device->gcm_id);
            Globals::pushAndroid($registrationIDs, $msg);
        } elseif ($device->type == Globals::DEVICE_TYPE_IOS) {
            array_push($registrationIDs, $device->gcm_id);
            Globals::pushIos($registrationIDs, $msg);
        }
    }

}
