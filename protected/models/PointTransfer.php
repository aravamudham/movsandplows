<?php

/**
 * This is the model class for table "point_transfer".
 *
 * The followings are the available columns in table 'point_transfer':
 * @property integer $id
 * @property integer $senderId
 * @property integer $receiverId
 * @property integer $amount
 * @property integer $status
 * @property string $note
 * @property string $dateCreated
 */
class PointTransfer extends CActiveRecord
{
	public $start_date;
	public $end_date;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'point_transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('senderId, receiverId, amount, status', 'numerical', 'integerOnly'=>true),
			array('dateCreated', 'safe'),
            array('note', 'length', 'max'=>255),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, senderId, receiverId, amount, status, dateCreated, start_date, end_date', 'safe', 'on'=>'search'),
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
			'sender' => array(self::BELONGS_TO, 'User', 'senderId'),
			'receiver' => array(self::BELONGS_TO, 'User', 'receiverId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'senderId' => 'Sender',
			'receiverId' => 'Receiver',
			'amount' => 'Amount',
			'status' => 'Status',
			'dateCreated' => 'Date Created',
            'note'=>'Note'
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
		$criteria->compare('senderId',$this->senderId);
		$criteria->compare('receiverId',$this->receiverId);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('status',$this->status);
		$criteria->compare('dateCreated',$this->dateCreated,true);
        $criteria->compare('note',$this->note,true);
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
	 * @return PointTransfer the static model class
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
			Globals::STATUS_INACTIVE => '<span class="label label-sm label-info">'. Yii::t('transaction', 'label.pending') .  '</span>',
		);
		return isset($str[$status]) ? $str[$status] : '';
	}

	public function pushNotification($type)
	{
		$message = '';
		if($type == Globals::STATUS_ACTIVE)
		{
			$message = Yii::t('common', 'message.transfer.approve');
		}
		elseif($type == Globals::STATUS_INACTIVE)
		{
			$message = Yii::t('common', 'message.transfer.reject');

		}

		$registrationIDs = array();
		$device = Device::model()->find('userId ='.$this->senderId);
		$msg = array
		(
			'data' => array(),
			'action' => 'transferApproval',
			'body' => $message
		);
		if($device->type == Globals::DEVICE_TYPE_ANDROID )
		{
			array_push($registrationIDs, $device->gcm_id);
			Globals::pushAndroid($registrationIDs,$msg);
		}
		elseif($device->type == Globals::DEVICE_TYPE_IOS)
		{
			array_push($registrationIDs, $device->gcm_id);
			Globals::pushIos($registrationIDs,$msg);
		}
	}
}
