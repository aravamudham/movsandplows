<?php

/**
 * This is the model class for table "transaction".
 *
 * The followings are the available columns in table 'transaction':
 * @property string $id
 * @property integer $userId
 * @property string $type
 * @property double $amount
 * @property integer $action
 * @property string $destination
 * @property integer $tripId
 * @property string $dateCreated
 */
class Transaction extends CActiveRecord
{
    public $start_date;
    public $end_date;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId', 'required'),
			array('userId, action, tripId', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('id', 'length', 'max'=>100),
			array('type', 'length', 'max'=>1),
			array('destination', 'length', 'max'=>255),
			array('dateCreated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userId, type, amount, action, destination, dateCreated, start_date, end_date', 'safe', 'on'=>'search'),
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
            'receiver' => array(self::BELONGS_TO, 'User', 'destination'),
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
			'type' => 'Type',
			'amount' => 'Amount',
			'action' => 'Action',
			'destination' => 'Destination',
			'dateCreated' => 'Date Created',
			'tripId'=> 'Trip ID'
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('action',$this->action);
		$criteria->compare('destination',$this->destination,true);
		$criteria->compare('tripId',$this->tripId,true);		
		$criteria->compare('dateCreated',$this->dateCreated,true);
        if(strlen($this->start_date)!=0) {
            $criteria->addCondition('date(dateCreated) >= \''.$this->start_date .'\'');
        }
        if(strlen($this->end_date)!= 0) {
            $criteria->addCondition('date(dateCreated) <= \''.$this->end_date .'\'');
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 'dateCreated DESC',
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transaction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getActionLabel($actions = FALSE)
    {
        if ($actions === FALSE) {
            $actions = $this->action;
        }
        $str = array(
            Globals::CANCELLATION_ORDER_FEE => '<span class="label label-sm label-danger">'. Yii::t('transaction', 'title.cancel.fee') .  '</span>',
            Globals::EXCHANGE_POINT => '<span class="label label-sm label-info">'. Yii::t('transaction', 'title.exchange') .  '</span>',
            Globals::REDEEM_POINT => '<span class="label label-sm label-info">'. Yii::t('transaction', 'title.redeem') .  '</span>',
            Globals::TRANSFER_POINT => '<span class="label label-sm label-info">'. Yii::t('transaction', 'title.transfer') .  '</span>',
            Globals::TRIP_PAYMENT => '<span class="label label-sm label-success">'. Yii::t('transaction', 'title.trip.payment') .  '</span>',
            Globals::PASSENGER_SHARE_BONUS => '<span class="label label-sm label-primary">'. Yii::t('transaction', 'title.passenger.share') .  '</span>',
            Globals::DRIVER_SHARE_BONUS => '<span class="label label-sm label-primary">'. Yii::t('transaction', 'title.driver.share') .  '</span>',
			Globals::COMMISSION_WHEN_PAYMENT_BY_CASH => '<span class="label label-sm label-primary">'. Yii::t('transaction', 'title.driver.share') .  '</span>',
        );
        return isset($str[$actions]) ? $str[$actions] : '';
    }
}
