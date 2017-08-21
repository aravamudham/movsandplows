<?php

/**
 * This is the model class for table "user_driver".
 *
 * The followings are the available columns in table 'user_driver':
 * @property integer $id
 * @property integer $userId
 * @property string $bankAccount
 * @property integer $status
 * @property integer $isOnline
 * @property double $rate
 * @property integer $rateCount
 * @property string $document
 * @property integer $isActive
 * @property integer $inactiveByAdmin
 * @property integer $sharedF
 * @property string $linkType
 */
class UserDriver extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user_driver';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, status, rateCount, isActive, isOnline, sharedF, sharedG', 'numerical', 'integerOnly' => true),
            array('inactiveByAdmin, rate', 'numerical'),
            array('bankAccount', 'length', 'max' => 255),
            array('linkType', 'length', 'max' => 10),
            array('document', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, bankAccount, status, isOnline, rate, rateCount, document, isActive, inactiveByAdmin', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userId' => 'User',
            'bankAccount' => 'Bank Account',
            'status' => 'Status',
            'isOnline' => 'Online',
            'rate' => 'Rate',
            'rateCount' => 'Rate Count',
            'document' => 'Document',
            'isActive' => 'Driver Active',
            'inactiveByAdmin' => 'Inactive by Admin',
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
        $criteria->compare('bankAccount', $this->bankAccount, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('isOnline', $this->isOnline, true);
        $criteria->compare('rate', $this->rate);
        $criteria->compare('rateCount', $this->rateCount);
        $criteria->compare('document', $this->document, true);
        $criteria->compare('isActive', $this->isActive);
        $criteria->compare('inactiveByAdmin', $this->inactiveByAdmin);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserDriver the static model class
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

    public function getInactiveByAdminStatusLabel($status = FALSE)
    {
        if ($status === FALSE) {
            $status = $this->inactiveByAdmin;
        }
        $str = array(
            Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">' . Yii::t('common', 'label.active') . '</span>',
            Globals::STATUS_INACTIVE => '<span class="label label-sm label-default">' . Yii::t('common', 'label.inactive') . '</span>',
        );
        return isset($str[$status]) ? $str[$status] : '';
    }

    public function getStatusLabel($status = FALSE)
    {
        if ($status === FALSE) {
            $status = $this->status;
        }
        $str = array(
            Globals::STATUS_IDLE => '<span class="label label-sm label-success">' . Yii::t('common', 'label.idle') . '</span>',
            Globals::STATUS_BUSY => '<span class="label label-sm label-danger">' . Yii::t('common', 'label.busy') . '</span>',
        );
        return isset($str[$status]) ? $str[$status] : '';
    }

    public function getUserDriversFreeOnline()
    {
        $userDrivers = UserDriver::model()->findAll('isOnline = ' . Globals::STATUS_ONLINE . ' and userId not in (select driverId from trip where status not in (' . Globals::TRIP_STATUS_FINISH . ') )');
        $userDriversOnilne = count($userDrivers);
        return $userDriversOnilne;
    }

    public function getUserDriversBusyOnline()
    {
        $userDrivers = UserDriver::model()->findAll('isOnline = ' . Globals::STATUS_ONLINE . ' and userId in (select driverId from trip where status not in (' . Globals::TRIP_STATUS_FINISH . ') )');
        $userDriversOnilne = count($userDrivers);
        return $userDriversOnilne;
    }

    public function getUserDriversNeedHelp()
    {
        $userDrivers = UserDriver::model()->findAll('isOnline = ' . Globals::STATUS_ONLINE . ' and userId in (select driverId from trip where need_help = 1 )');
        $userDriversOnilne = count($userDrivers);
        return $userDriversOnilne;
    }

}
