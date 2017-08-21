<?php

/**
 * This is the model class for table "trip".
 *
 * The followings are the available columns in table 'trip':
 * @property integer $id
 * @property integer $passengerId
 * @property integer $driverId
 * @property string $link
 * @property string $startTime
 * @property string $endTime
 * @property string $startLat
 * @property string $startLong
 * @property string $startLocation
 * @property string $endLat
 * @property string $endLong
 * @property string $endLocation
 * @property double $distance
 * @property integer $status
 * @property double $estimateFare
 * @property double $actualFare
 * @property double $driverRate
 * @property double $passengerRate
 * @property string $dateCreated
 * @property integer $need_help
 *
 * The followings are the available model relations:
 * @property User $passenger
 * @property User $driver
 */
class Trip extends CActiveRecord
{
    public $start_date;
    public $end_date;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'trip';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('need_help, passengerId, driverId, status', 'numerical', 'integerOnly' => true),
            array('actualFare, distance, driverRate, passengerRate', 'numerical'),
            array('link', 'length', 'max' => 3),
            array('startLat, startLong, endLat, endLong', 'length', 'max' => 20),
            array('startLocation, endLocation', 'length', 'max' => 255),
            array('startTime, endTime, dateCreated', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, passengerId, driverId, link, startTime, endTime, startLat, startLong, startLocation, endLat, endLong, endLocation, distance, status, estimateFare, actualFare, driverRate, passengerRate, dateCreated, start_date, end_date', 'safe', 'on' => 'search'),
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
            'passenger' => array(self::BELONGS_TO, 'User', 'passengerId'),
            'driver' => array(self::BELONGS_TO, 'User', 'driverId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'passengerId' => 'Passenger',
            'driverId' => 'Driver',
            'link' => 'Car Type',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
            'startLat' => 'Start Lat',
            'startLong' => 'Start Long',
            'startLocation' => 'Start Location',
            'endLat' => 'End Lat',
            'endLong' => 'End Long',
            'endLocation' => 'End Location',
            'distance' => 'Distance',
            'status' => 'Status',
            'estimateFare' => 'Estimate Fare',
            'actualFare' => 'Actual Fare',
            'driverRate' => 'Driver Rate',
            'passengerRate' => 'Passenger Rate',
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
        $criteria->compare('passengerId', $this->passengerId);
        $criteria->compare('driverId', $this->driverId);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('startTime', $this->startTime, true);
        $criteria->compare('endTime', $this->endTime, true);
        $criteria->compare('startLat', $this->startLat, true);
        $criteria->compare('startLong', $this->startLong, true);
        $criteria->compare('startLocation', $this->startLocation, true);
        $criteria->compare('endLat', $this->endLat, true);
        $criteria->compare('endLong', $this->endLong, true);
        $criteria->compare('endLocation', $this->endLocation, true);
        $criteria->compare('distance', $this->distance);
        if ($this->status == Globals::TRIP_STATUS_NEED_HELP) {
            $criteria->compare('need_help', 1);
        } else {
            $criteria->compare('status', $this->status);
        }
        $criteria->compare('estimateFare', $this->estimateFare);
        $criteria->compare('actualFare', $this->actualFare);
        $criteria->compare('driverRate', $this->driverRate);
        $criteria->compare('passengerRate', $this->passengerRate);
        $criteria->compare('dateCreated', $this->dateCreated, true);

        $criteria->order = 'need_help DESC, id DESC';

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
     * @return Trip the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getLabelCarTypeFromKey($key){
        switch ($key){
            case 'I':
                return 'SEDAN 4';
            case 'II':
                return 'SUV 6';
            case 'III':
                return 'LUX';
        }
    }

    public function getStatusLabel($status = FALSE)
    {
        if ($status === FALSE) {
            $status = $this->status;
        }
        $str = array(
            Globals::TRIP_STATUS_APPROACHING => '<span class="label label-sm label-info">' . Yii::t('trip', 'title.approaching') . '</span>',
            Globals::TRIP_STATUS_IN_PROGRESS => '<span class="label label-sm label-success">' . Yii::t('trip', 'title.in.process') . '</span>',
            Globals::TRIP_STATUS_PENDING_PAYMENT => '<span class="label label-sm label-warning">' . Yii::t('trip', 'title.pending.payment') . '</span>',
            Globals::TRIP_STATUS_FINISH => '<span class="label label-sm label-primary">' . Yii::t('trip', 'title.finish') . '</span>',

        );
        return isset($str[$status]) ? $str[$status] : '';
    }

    public function checkDriverTrip($driverId){
        $data = Trip::model()->find('driverId = '.$driverId.' and status not in ('.Globals::TRIP_STATUS_FINISH.')');
        if (count($data)>0){
            return true;
        }
        return false;
    }

    public function checkDriverTripNeedhelp($driverId){
        $data = Trip::model()->find('driverId = '.$driverId.' and need_help = 1');
        if (count($data)>0){
            return true;
        }
        return false;
    }
}
