<?php

/**
 * This is the model class for table "account".
 *
 * The followings are the available columns in table 'account':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $fullName
 * @property string $phone
 * @property string $address
 * @property integer $role
 * @property integer $status
 */
class Account extends CActiveRecord
{
	public $oldPass;
	public $newPass;
	public $confPass;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, oldPass', 'required'),
			array('role, status', 'numerical', 'integerOnly'=>true),
			array('username, phone', 'length', 'max'=>30),
			array('password', 'length', 'max'=>60),
			array('email, fullName', 'length', 'max'=>100),
			array('address', 'length', 'max'=>255),
			array('oldPass, newPass, confPass', 'length', 'max'=>30),
			array('oldPass','compareCurrentPassword'),
			array(
				'confPass', 'compare',
				'compareAttribute'=>'newPass',
			),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, email, fullName, phone, address, role, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'fullName' => 'Full Name',
			'phone' => 'Phone',
			'address' => 'Address',
			'role' => 'Role',
			'status' => 'Status',
			'oldPass'=> 'Old Password',
			'newPass'=> 'New Password',
			'confPass'=> 'Confirm New Password',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('fullName',$this->fullName,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Account the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function compareCurrentPassword()
	{
		$user_id = $this->id;
		$model = Account::model()->findByPk($user_id);
		if($model->password != sha1($this->oldPass))
			$this->addError('currentPassword', 'Current pass is invalid');
	}

	public function getStatusLabel($status = FALSE)
	{
		if ($status === FALSE) {
			$status = $this->status;
		}
		$str = array(
			Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">'. Yii::t('common', 'label.active') .  '</span>',
			Globals::STATUS_INACTIVE => '<span class="label label-sm label-danger">'. Yii::t('common', 'label.inactive') .  '</span>',
		);
		return isset($str[$status]) ? $str[$status] : '';
	}
	public function getRoleLabel($role = FALSE)
	{
		if ($role === FALSE) {
			$role = $this->role;
		}

		if($role == Globals::ROLE_ADMIN)
		{
			return 'Admin';
		}elseif($role == Globals::ROLE_MODERATOR)
		{
			return 'Moderator';
		}
		else
			return 'N/A';

	}
}
