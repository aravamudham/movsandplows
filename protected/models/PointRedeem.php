<?php

/**
 * This is the model class for table "point_redeem".
 *
 * The followings are the available columns in table 'point_redeem':
 * @property integer $id
 * @property integer $userId
 * @property integer $amount
 * @property integer $status
 * @property string $dateCreated
 * @property string $payoutPaypalAddress
 */
class PointRedeem extends CActiveRecord
{

    public $start_date;
    public $end_date;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'point_redeem';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId, amount, status', 'numerical', 'integerOnly' => true),
            array('dateCreated', 'safe'),
            array('payoutPaypalAddress', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userId, amount, status, dateCreated, start_date, end_date', 'safe', 'on' => 'search'),
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
            'status' => 'Status',
            'dateCreated' => 'Date Created',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('userId', $this->userId);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('status', $this->status);
        $criteria->compare('dateCreated', $this->dateCreated, true);
        if (strlen($this->start_date) != 0) {
            $criteria->addCondition('date(dateCreated) >= \'' . $this->start_date . '\'');
        }
        if (strlen($this->end_date) != 0) {
            $criteria->addCondition('date(dateCreated) <= \'' . $this->end_date . '\'');
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'dateCreated DESC',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PointRedeem the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getStatusLabel($status = FALSE)
    {
        if ($status === FALSE) {
            $status = $this->status;
        }
        $str = array(
            Globals::STATUS_ACTIVE => '<span class="label label-sm label-success">' . Yii::t('transaction', 'label.approve') . '</span>',
            Globals::STATUS_INACTIVE => '<span class="label label-sm label-info">' . Yii::t('transaction', 'label.pending') . '</span>',
        );
        return isset($str[$status]) ? $str[$status] : '';
    }

    public function pushNotification($type)
    {
        $message = '';
        if ($type == Globals::STATUS_ACTIVE) {
            $message = Yii::t('common', 'message.redeem.approve');
        } elseif ($type == Globals::STATUS_INACTIVE) {
            $message = Yii::t('common', 'message.redeem.reject');
        }

        $registrationIDs = array();
        $device = Device::model()->find('userId =' . $this->userId);
        $msg = array
        (
            'data' => array(),
            'action' => 'redeemApproval',
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
