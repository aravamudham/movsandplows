<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $fullName
 * @property string $image
 * @property string $email
 * @property string $password
 * @property string $description
 * @property string $gender
 * @property string $phone
 * @property string $dob
 * @property string $address
 * @property double $balance
 * @property string $lat
 * @property string $long
 * @property string $cardNumber
 * @property string $cvv
 * @property string $exp
 * @property integer $isOnline
 * @property integer $isActive
 * @property integer $isDriver
 * @property string $token
 * @property string $dateCreated
 * @property UserDriver $driver
 * @property UserPassenger $passenger
 * @property Vehicle $vehicle
 * @property string $cityId
 * @property string $payoutPaypalAddress
 * @property integer $stateId
 * @property integer $typeAccount
 */
class User extends CActiveRecord
{

    public $start_date;
    public $end_date;
    public $driver_check;
    public $driver_is_online;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('stateId, isOnline, isActive, isDriver', 'numerical', 'integerOnly' => true),
            array('fullName, image, email, address, payoutPaypalAddress', 'length', 'max' => 255),
            array('cityId', 'length', 'max' => 200),
            array('password, token', 'length', 'max' => 40),
            array('gender', 'length', 'max' => 10),
            array('phone, exp', 'length', 'max' => 20),
            array('lat, long', 'length', 'max' => 18),
            array('cardNumber', 'length', 'max' => 30),
            array('cvv', 'length', 'max' => 10),
            array('description, dob, dateCreated', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fullName, image, email, password, description, gender, phone, dob, address, balance, lat, long, cardNumber, cvv, exp, isOnline, isActive, isDriver, token, dateCreated, start_date, end_date, driver_check', 'safe', 'on' => 'search'),
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
            'driver' => array(self::HAS_ONE, 'UserDriver', 'userId'),
            'passenger' => array(self::HAS_ONE, 'UserPassenger', 'userId'),
            'vehicle' => array(self::HAS_ONE, 'Vehicle', 'userId'),
            'state' => array(self::BELONGS_TO, 'State', 'stateId'),
            'city' => array(self::BELONGS_TO, 'City', 'cityId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'fullName' => 'Full Name',
            'image' => 'Image',
            'email' => 'Email',
            'password' => 'Password',
            'description' => 'Description',
            'gender' => 'Gender',
            'phone' => 'Phone',
            'dob' => 'Dob',
            'address' => 'Address',
            'balance' => 'Balance',
            'lat' => 'Lat',
            'long' => 'Long',
            'cardNumber' => 'Card Number',
            'cvv' => 'Cvv',
            'exp' => 'Exp',
            'isOnline' => 'Is Online',
//            'isActive' => 'Is Active',
            'isActive' => 'Active User',
            'isDriver' => 'Is Driver',
            'token' => 'Token',
            'dateCreated' => 'Date Created',
            'stateId' => 'State',
            'cityId' => 'City',
            'payoutPaypalAddress' => 'Payout PayPal Address',
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
    public function search($byAdmin = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);   // add t.id, t......... for duplicate field
        $criteria->compare('fullName', $this->fullName, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('gender', $this->gender, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('dob', $this->dob, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('balance', $this->balance);
        $criteria->compare('lat', $this->lat, true);
        $criteria->compare('long', $this->long, true);
        $criteria->compare('cardNumber', $this->cardNumber, true);
        $criteria->compare('cvv', $this->cvv, true);
        $criteria->compare('exp', $this->exp, true);
        $criteria->compare('isOnline', $this->isOnline);
        $criteria->compare('t.isActive', $this->isActive);
        $criteria->compare('isDriver', $this->isDriver);
        $criteria->compare('token', $this->token, true);
        $criteria->compare('dateCreated', $this->dateCreated, true);
        $criteria->compare('stateId', $this->stateId, true);
        $criteria->compare('cityId', $this->cityId, true);

        if (strlen($this->driver_check) != 0) {
            /******IMPORTANT********/
            $criteria->with = "driver"; // search with related model
            /*******END************/

            if ($this->driver_check == Globals::USER_TYPE_DRIVER) {
                $criteria->compare('isDriver', Globals::STATUS_ACTIVE);
                $criteria->compare('driver.isActive', $this->driver_check);

            } elseif ($this->driver_check == Globals::USER_TYPE_DRIVER_PENDING) {
                $criteria->compare('isDriver', Globals::STATUS_ACTIVE);
                $criteria->compare('driver.isActive', $this->driver_check);

            } elseif ($this->driver_check == Globals::NEW_DRIVER_REGISTER_CUSTOM_INDEX) {
                $criteria->compare('driver.isActive', Globals::STATUS_INACTIVE);
                $criteria->compare('driver.inactiveByAdmin', Globals::NEW_DRIVER_REGISTER);

            } elseif ($this->driver_check == Globals::INACTIVE_BY_ADMIN_CUSTOM_INDEX) {
                $criteria->compare('driver.isActive', Globals::STATUS_INACTIVE);
                $criteria->compare('driver.inactiveByAdmin', Globals::INACTIVE_BY_ADMIN);

            }
        }
        if ($byAdmin != null && $byAdmin == Globals::NEW_DRIVER_REGISTER_CUSTOM_INDEX){
            $criteria->compare('isDriver', Globals::STATUS_ACTIVE);
            $criteria->compare('driver.inactiveByAdmin', Globals::NEW_DRIVER_REGISTER);
        }

        if (strlen($this->start_date) != 0) {
            $criteria->addCondition('date(dateCreated) >= \'' . $this->start_date . '\'');
        }
        if (strlen($this->end_date) != 0) {
            $criteria->addCondition('date(dateCreated) <= \'' . $this->end_date . '\'');
        }
        if ($this->driver_is_online == 1){
            /******IMPORTANT********/
            $criteria->with = "driver"; // search with related model
            /*******END************/
            $criteria->compare('isDriver', Globals::STATUS_ACTIVE);
            $criteria->compare('driver.isOnline', 1);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getOnlineStatusLabel($status = FALSE)
    {
        if ($status === FALSE) {
            $status = $this->isOnline;
        }
        $str = array(
            Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">' . Yii::t('common', 'label.online') . '</span>',
            Globals::STATUS_INACTIVE => '<span class="label label-sm label-default">' . Yii::t('common', 'label.offline') . '</span>',
        );
        return isset($str[$status]) ? $str[$status] : '';
    }

    public function getActiveStatusLabel($status = FALSE)
    {
        if ($status === FALSE) {
            $status = $this->isActive;
        }
        $str = array(
            Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">' . Yii::t('common', 'label.active') . '</span>',
            Globals::STATUS_INACTIVE => '<span class="label label-sm label-default">' . Yii::t('common', 'label.inactive') . '</span>',
        );
        return isset($str[$status]) ? $str[$status] : '';
    }

    public function getDriverStatusLabel($status = FALSE)
    {
        if ($status === FALSE) {
            $status = $this->isDriver;
        }
        $str = array(
            Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">' . Yii::t('common', 'label.yes') . '</span>',
            Globals::STATUS_INACTIVE => '<span class="label label-sm label-default">' . Yii::t('common', 'label.no') . '</span>',
        );
        return isset($str[$status]) ? $str[$status] : '';
    }

    public function getDriverIsActiveStatusLabel($status = FALSE, $byAdmin = null)
    {
        if ($status === FALSE) {
            /** @var UserDriver $userDriver */
            $userDriver = UserDriver::model()->find("userId = " . $this->id);
            if ($userDriver != null) {
                $status = $userDriver->isActive;
            }
        }
        $str = array(
            Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">' . Yii::t('common', 'label.yes') . '</span>',
            Globals::STATUS_INACTIVE => '<span class="label label-sm label-default">' . Yii::t('common', 'label.no') . '</span>',
            /*Globals::NEW_DRIVER_REGISTER => '<span class="label label-sm label-primary">' . Yii::t('common', 'title.driver.newRegister') . '</span>',
            Globals::INACTIVE_BY_ADMIN => '<span class="label label-sm label-warning">' . Yii::t('common', 'title.driver.inactiveByAdmin') . '</span>',*/
        );

        $echo = isset($str[$status]) ? $str[$status] : '';

        if ($byAdmin != null){

            $str = array(
                Globals::NEW_DRIVER_REGISTER => '<span class="label label-sm label-primary">' . Yii::t('common', 'title.driver.newRegister') . '</span>',
                Globals::INACTIVE_BY_ADMIN => '<span class="label label-sm label-warning">' . Yii::t('common', 'title.driver.inactiveByAdmin') . '</span>',
                Globals::NEW_DRIVER_ADMIN_APPROVED => '',//<span class="label label-sm label-primary">Approve</span>
            );

            $echo = $echo.'<br/><br/>'.(isset($str[$byAdmin]) ? $str[$byAdmin] : '');
        }

        return $echo;
    }

    public function safeDelete()
    {
        $transaction = FALSE;
        if (!Yii::app()->db->currentTransaction) {
            $transaction = Yii::app()->db->beginTransaction();
        }
        try {
            UserDriver::model()->deleteAll('userId = :userId', array('userId' => $this->id));
            UserPassenger::model()->deleteAll('userId = :userId', array('userId' => $this->id));
            Trip::model()->deleteAll('driverId = :userId OR passengerId =:userId', array('userId' => $this->id));
            Vehicle::model()->deleteByUserId($this->id);
            UpdatePending::model()->deleteByUserId($this->id);
            Transaction::model()->deleteAll('userId = :userId OR destination =:userId', array('userId' => $this->id));
            PointExchange::model()->deleteAll('userId = :userId', array('userId' => $this->id));
            PointRedeem::model()->deleteAll('userId = :userId', array('userId' => $this->id));
            PointTransfer::model()->deleteAll('senderId = :userId OR receiverId =:userId', array('userId' => $this->id));
            $this->delete();
            $transaction AND $transaction->commit();
        } catch (Exception $e) // an exception is raised if a query fails
        {
            $transaction AND $transaction->rollback();
        }
    }

    public function getLatLong($userId)
    {
        $lat_long = array('lat' => '', 'long' => '');
        $data = User::model()->findByPk($userId);
        if (count($data) > 0) {
            $lat_long['lat'] = $data->lat;
            $lat_long['long'] = $data->long;
        }
        return $lat_long;
    }

    public function getFullName($userId)
    {
        $data = User::model()->findByPk($userId);
        if (count($data) > 0) {
            return $data->fullName;
        }
        return '';
    }
}
