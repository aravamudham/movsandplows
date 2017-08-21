<?php

/**
 * This is the model class for table "vehicle".
 *
 * The followings are the available columns in table 'vehicle':
 * @property integer $id
 * @property integer $userId
 * @property string $carPlate
 * @property string $brand
 * @property string $model
 * @property string $year
 * @property string $status
 * @property string $document
 * @property string $dateCreated
 */
class Vehicle extends CActiveRecord
{
    public $issetDocument;
    public $start_date;
    public $end_date;
    public $fullName;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'vehicle';
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
            array('carPlate, brand, model, status', 'length', 'max' => 255),
            array('year', 'length', 'max' => 4),
            array('document, dateCreated', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, carPlate, brand, model, year, status, document, dateCreated, issetDocument, start_date, end_date', 'safe', 'on' => 'search'),
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
            'images' => array(self::HAS_MANY, 'VehicleImg', 'carId'),
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
            'dateCreated' => 'Date Created',
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
        $criteria->compare('dateCreated', $this->dateCreated, true);
        if (strlen($this->issetDocument) != 0) {
            if ($this->issetDocument == Globals::MISSING_DOCUMENT) {
                $criteria->addCondition('document IS NULL');
            }
            if ($this->issetDocument == Globals::DOCUMENT) {
                $criteria->addCondition('document IS NOT NULL');
            }
        }
        if (strlen($this->start_date)!=0) {
            $criteria->addCondition('date(dateCreated) >= \'' . $this->start_date . '\'');
        }
        if (strlen($this->end_date)!= 0) {
            $criteria->addCondition('date(dateCreated) <= \'' . $this->end_date . '\'');
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

//    public function searchByUserId($userId)
//    {
//        // @todo Please modify the following code to remove attributes that should not be searched.
//
//        $criteria = new CDbCriteria;
//
//        $criteria->compare('userId', $userId);
//
//        return new CActiveDataProvider($this, array(
//            'criteria' => $criteria,
//        ));
//    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Vehicle the static model class
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
            $oldImages = VehicleImg::model()->findAll('carId ='.$this->id);
            if(count($oldImages)!= 0)
            {
                foreach($oldImages as $image)
                {
                    unlink(Yii::getPathOfAlias(UPLOAD_DIR).DIRECTORY_SEPARATOR.CAR_DIR.DIRECTORY_SEPARATOR. $image->image);
                }
                VehicleImg::model()->deleteAll('carId ='.$this->id);
				if(file_exists(Yii::getPathOfAlias(UPLOAD_DIR).DIRECTORY_SEPARATOR.CAR_DOCUMENT_DIR.DIRECTORY_SEPARATOR. $this->document))
                unlink(Yii::getPathOfAlias(UPLOAD_DIR).DIRECTORY_SEPARATOR.CAR_DOCUMENT_DIR.DIRECTORY_SEPARATOR. $this->document);
            }
            $this->delete();
            $transaction AND $transaction->commit();
        } catch (Exception $e) // an exception is raised if a query fails
        {
            $transaction AND $transaction->rollback();
        }
    }


    /**
     * @param $userId
     */
    public function deleteByUserId($userId)
    {
        $vehicle = Vehicle::model()->find('userId = :userId', array('userId' => $userId));
        if(isset($vehicle))
        {
            $oldImages = VehicleImg::model()->findAll('carId ='.$vehicle->id);
            if(count($oldImages)!= 0)
            {
                foreach($oldImages as $image)
                {
                    unlink(Yii::getPathOfAlias(UPLOAD_DIR).DIRECTORY_SEPARATOR.CAR_DIR.DIRECTORY_SEPARATOR. $image->image);
                }
                VehicleImg::model()->deleteAll('carId ='.$vehicle->id);
                unlink(Yii::getPathOfAlias(UPLOAD_DIR).DIRECTORY_SEPARATOR.CAR_DOCUMENT_DIR.DIRECTORY_SEPARATOR. $vehicle->document);
            }
            $vehicle->delete();
        }

    }

    public function getInfoVehicles($userId){
        $data = Vehicle::model()->find('userId = '.$userId);
        if (count($data)>0){
            return $data;
        }
        return null;
    }
}
