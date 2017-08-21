<?php

/**
 * This is the model class for table "point_exchange".
 *
 * The followings are the available columns in table 'point_exchange':
 * @property integer $id
 * @property integer $userId
 * @property double $amount
 * @property integer $paymentMethod
 * @property integer $status
 * @property string $dateCreated
 */
class PointExchange extends CActiveRecord
{
	public $start_date;
	public $end_date;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'point_exchange';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, paymentMethod, status', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('dateCreated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userId, amount, paymentMethod, status, dateCreated, start_date, end_date', 'safe', 'on'=>'search'),
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
			'amount' => 'Amount',
			'paymentMethod' => 'Payment Method',
			'status' => 'Status',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('paymentMethod',$this->paymentMethod);
		$criteria->compare('status',$this->status);
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
	 * @return PointExchange the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getStatusLabel($status = FALSE)
	{
		if ($status === FALSE) {
			$status = $this->status;
		}
		$str = array(
			Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">'. Yii::t('transaction', 'label.approve') .  '</span>',
			Globals::STATUS_INACTIVE => '<span class="label label-sm label-danger">'. Yii::t('transaction', 'label.reject') .  '</span>',
		);
		return isset($str[$status]) ? $str[$status] : '';
	}
	public function getPaymentMethodLabel($status = FALSE)
	{
		if ($status === FALSE) {
			$status = $this->paymentMethod;
		}
		$str = array(
			Globals::PAYMENT_METHOD_PAYPAL => '<span class="label label-sm label-success">'. Yii::t('transaction', 'label.paypal') .  '</span>',
			Globals::PAYMENT_METHOD_CREDIT => '<span class="label label-sm label-info">'. Yii::t('transaction', 'label.credit') .  '</span>',
		);
		return isset($str[$status]) ? $str[$status] : '';
	}
}
