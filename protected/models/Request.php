<?php

/**
 * This is the model class for table "request".
 *
 * The followings are the available columns in table 'request':
 * @property integer $id
 * @property integer $passengerId
 * @property integer $driverId
 * @property string $requestTime
 * @property string $link
 * @property string $startTime
 * @property string $startLat
 * @property string $startLong
 * @property string $startLocation
 * @property string $endLat
 * @property string $endLong
 * @property string $endLocation
 * @property string $estimateFare
 *
 * The followings are the available model relations:
 * @property User $passenger
 * @property User $driver
 */
class Request extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('passengerId, driverId', 'numerical', 'integerOnly'=>true),
			array('requestTime, startLocation, endLocation', 'length', 'max'=>255),
			array('estimateFare', 'length', 'max'=>255),
			array('link', 'length', 'max'=>3),
			array('startLat, startLong, endLat, endLong', 'length', 'max'=>20),
			array('startTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, passengerId, driverId, requestTime, link, startTime, startLat, startLong, startLocation, endLat, endLong, endLocation', 'safe', 'on'=>'search'),
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
			'requestTime' => 'Request Time',
			'link' => 'Link',
			'startTime' => 'Start Time',
			'startLat' => 'Start Lat',
			'startLong' => 'Start Long',
			'startLocation' => 'Start Location',
			'endLat' => 'End Lat',
			'endLong' => 'End Long',
			'endLocation' => 'End Location',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('passengerId',$this->passengerId);
		$criteria->compare('driverId',$this->driverId);
		$criteria->compare('requestTime',$this->requestTime,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('startTime',$this->startTime,true);
		$criteria->compare('startLat',$this->startLat,true);
		$criteria->compare('startLong',$this->startLong,true);
		$criteria->compare('startLocation',$this->startLocation,true);
		$criteria->compare('endLat',$this->endLat,true);
		$criteria->compare('endLong',$this->endLong,true);
		$criteria->compare('endLocation',$this->endLocation,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Request the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
