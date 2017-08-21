<?php

class SettingsForm extends CFormModel
{
    public $sign_up_start_points;
    public $admin_email;
    public $max_request;
    public $distance_search;
    public $api_key;
    public $sf1;
    public $ppm1;
    public $ppk1;
    public $sf2;
    public $ppm2;
    public $ppk2;
    public $sf3;
    public $ppm3;
    public $ppk3;
    public $pem;
    public $driver_share_bonus;
    public $passenger_share_bonus;
    public $app_post_per_page;
    public $min_redeem_amount;
    public $min_transfer_amount;
    public $cancellation_fee;
    public $max_driver;
    public $driver_earn;
    public $min_balance_place_request;
    public $time;
    public $admin_phone_number;
    public $time_reload_map;
    public $reload_map;
    public $time_to_send_request_again;
    public $max_time_send_request;
    public $estimate_fare_speed;
    public $auto_approve_for_register_driver;


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('admin_phone_number, api_key', 'length', 'max' => 300),
            array('admin_email', 'email'),
            array('sign_up_start_points, min_redeem_amount, min_transfer_amount, distance_search, max_request, app_post_per_page, max_driver, time_reload_map, reload_map', 'numerical', 'integerOnly' => true),
            array('auto_approve_for_register_driver, time_to_send_request_again, max_time_send_request, estimate_fare_speed', 'numerical', 'integerOnly' => true),
            array('ppm1, ppm2, ppm3, ppk1, ppk2, ppk3, sf1, sf2, sf3, driver_share_bonus, passenger_share_bonus, cancellation_fee, driver_earn, min_balance_place_request', 'numerical'),
            array('pem', 'file', 'allowEmpty' => true, 'types' => 'pem', 'minSize' => 1),
            //array('yourfile','file', 'types'=>'jpg, gif, png, jpeg', 'minSize'=>1024 * 1024 * 50, 'tooLarge'=>'File has to be bigger than 50MB')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'sign_up_start_points' => 'Sign up start points',
            'admin_email' => 'Admin email',
            'api_key' => 'Google API Key',
            'distance_search' => 'Distance for searching driver ',
            'max_request' => 'Max Request Acceptable',
            'pem' => 'Pem File',
            'sf1' => 'Start Fare SEDAN 4',
            'sf2' => 'Start Fare SUV 6',
            'sf3' => 'Start Fare LUX',
            'ppm1' => 'Fee per minute SEDAN 4',
            'ppm2' => 'Fee per minute SUV 6',
            'ppm3' => 'Fee per minute LUX',
            'ppk1' => 'Fee per kilometer SEDAN 4',
            'ppk2' => 'Fee per kilometer SUV 6',
            'ppk3' => 'Fee per kilometer LUX',
            'driver_share_bonus' => 'Driver share bonus',
            'passenger_share_bonus' => 'Passenger share bonus',
            'app_post_per_page' => 'App post per page',
            'min_redeem_amount' => 'Min Payout Amount',
            'min_transfer_amount' => 'Min Transfer Amount',
            'cancellation_fee' => 'Cancellation Fee',
            'max_driver' => 'Number of driver receive push',
            'driver_earn' => 'Driver Earn Rate',
            'min_balance_place_request' => 'Min balance to create request',
            'time_reload_map' => 'Time Auto Reload Map (s)',
            'reload_map' => 'Time to reload map',
            'time_to_send_request_again' => 'Time To Send Request Again',
            'max_time_send_request' => 'Max Time Send Request',
            'estimate_fare_speed' => 'Estimate Fare Speed (Km/h)',
            'auto_approve_for_register_driver' => 'Auto approve for register driver',
        );
    }

    /**
     * Create instance form $id of model
     */
    public function loadModel()
    {
        /** @var Settings $model */
        $model = Settings::model();
        $this->sign_up_start_points = $model->getSettingValueByKey(Globals::SIGN_UP_START_POINTS);
        $this->admin_email = $model->getSettingValueByKey(Globals::ADMIN_EMAIL);
        $this->api_key = $model->getSettingValueByKey(Globals::GOOGLE_API_KEY);
        $this->distance_search = $model->getSettingValueByKey(Globals::DISTANCE_FOR_SEARCHING_DRIVER);
        $this->max_request = $model->getSettingValueByKey(Globals::MAX_REQUEST_ACCEPTABLE);
        $this->pem = $model->getSettingValueByKey(Globals::PEM_FILE);
        $this->sf1 = $model->getSettingValueByKey(Globals::SF_OF_LINK_I);
        $this->sf2 = $model->getSettingValueByKey(Globals::SF_OF_LINK_II);
        $this->sf3 = $model->getSettingValueByKey(Globals::SF_OF_LINK_III);
        $this->ppm1 = $model->getSettingValueByKey(Globals::PPM_OF_LINK_I);
        $this->ppm2 = $model->getSettingValueByKey(Globals::PPM_OF_LINK_II);
        $this->ppm3 = $model->getSettingValueByKey(Globals::PPM_OF_LINK_III);
        $this->ppk1 = $model->getSettingValueByKey(Globals::PPK_OF_LINK_I);
        $this->ppk2 = $model->getSettingValueByKey(Globals::PPK_OF_LINK_II);
        $this->ppk3 = $model->getSettingValueByKey(Globals::PPK_OF_LINK_III);
        $this->driver_share_bonus = $model->getSettingValueByKey(Globals::DRIVER_SHARE_BONUS_AMOUNT);
        $this->passenger_share_bonus = $model->getSettingValueByKey(Globals::PASSENGER_SHARE_BONUS_AMOUNT);
        $this->app_post_per_page = $model->getSettingValueByKey(Globals::APP_POST_PER_PAGE);
        $this->min_redeem_amount = $model->getSettingValueByKey(Globals::MIN_REDEEM_AMOUNT);
        $this->min_transfer_amount = $model->getSettingValueByKey(Globals::MIN_TRANSFER_AMOUNT);
        $this->cancellation_fee = $model->getSettingValueByKey(Globals::CANCELLATION_FEE);
        $this->max_driver = $model->getSettingValueByKey(Globals::MAX_DRIVER_RECEIVE_PUSH);
        $this->driver_earn = $model->getSettingValueByKey(Globals::DRIVER_EARN);
        $this->min_balance_place_request = $model->getSettingValueByKey(Globals::MIN_BALANCE_PLACE_REQUEST);
        $this->admin_phone_number = $model->getSettingValueByKey(Globals::ADMIN_PHONE_NUMBER);
        $this->reload_map = $model->getSettingValueByKey(Globals::AUTO_RELOAD_DATA_MAP);
        $this->time_reload_map = $model->getSettingValueByKey(Globals::TIME_AUTO_RELOAD_DATA_MAP);
        $this->time_to_send_request_again = $model->getSettingValueByKey(Globals::TIME_TO_SEND_REQUEST_AGAIN);
        $this->max_time_send_request = $model->getSettingValueByKey(Globals::MAX_TIME_SEND_REQUEST);
        $this->estimate_fare_speed = $model->getSettingValueByKey(Globals::ESTIMATE_FARE_SPEED);
        $this->auto_approve_for_register_driver = $model->getSettingValueByKey(Globals::AUTO_APPROVE_FOR_REGISTER_DRIVER);
    }

    public function save()
    {

        $isSave = FALSE;
        if (!Yii::app()->db->currentTransaction) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                Settings::model()->setSettingValueByKey(Globals::SIGN_UP_START_POINTS, $this->sign_up_start_points == null ? Settings::model()->getSettingValueByKey(Globals::SIGN_UP_START_POINTS) : $this->sign_up_start_points);
                Settings::model()->setSettingValueByKey(Globals::ADMIN_EMAIL, $this->admin_email == null ? Settings::model()->getSettingValueByKey(Globals::ADMIN_EMAIL) : $this->admin_email);
                Settings::model()->setSettingValueByKey(Globals::GOOGLE_API_KEY, $this->api_key == null ? Settings::model()->getSettingValueByKey(Globals::GOOGLE_API_KEY) : $this->api_key);
                Settings::model()->setSettingValueByKey(Globals::DISTANCE_FOR_SEARCHING_DRIVER, $this->distance_search == null ? Settings::model()->getSettingValueByKey(Globals::DISTANCE_FOR_SEARCHING_DRIVER) : $this->distance_search);
                Settings::model()->setSettingValueByKey(Globals::MAX_REQUEST_ACCEPTABLE, $this->max_request == null ? Settings::model()->getSettingValueByKey(Globals::MAX_REQUEST_ACCEPTABLE) : $this->max_request);
                Settings::model()->setSettingValueByKey(Globals::PEM_FILE, $this->pem == null ? Settings::model()->getSettingValueByKey(Globals::PEM_FILE) : $this->pem);
                Settings::model()->setSettingValueByKey(Globals::SF_OF_LINK_I, $this->sf1 == null ? Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_I) : $this->sf1);
                Settings::model()->setSettingValueByKey(Globals::SF_OF_LINK_II, $this->sf2 == null ? Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_II) : $this->sf2);
                Settings::model()->setSettingValueByKey(Globals::SF_OF_LINK_III, $this->sf3 == null ? Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_III) : $this->sf3);
                Settings::model()->setSettingValueByKey(Globals::PPM_OF_LINK_I, $this->ppm1 == null ? Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_I) : $this->ppm1);
                Settings::model()->setSettingValueByKey(Globals::PPM_OF_LINK_II, $this->ppm2 == null ? Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_II) : $this->ppm2);
                Settings::model()->setSettingValueByKey(Globals::PPM_OF_LINK_III, $this->ppm3 == null ? Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_III) : $this->ppm3);
                Settings::model()->setSettingValueByKey(Globals::PPK_OF_LINK_I, $this->ppk1 == null ? Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_I) : $this->ppk1);
                Settings::model()->setSettingValueByKey(Globals::PPK_OF_LINK_II, $this->ppk2 == null ? Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_II) : $this->ppk2);
                Settings::model()->setSettingValueByKey(Globals::PPK_OF_LINK_III, $this->ppk3 == null ? Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_III) : $this->ppk3);
                Settings::model()->setSettingValueByKey(Globals::DRIVER_SHARE_BONUS_AMOUNT, $this->driver_share_bonus == null ? Settings::model()->getSettingValueByKey(Globals::DRIVER_SHARE_BONUS_AMOUNT) : $this->driver_share_bonus);
                Settings::model()->setSettingValueByKey(Globals::PASSENGER_SHARE_BONUS_AMOUNT, $this->passenger_share_bonus == null ? Settings::model()->getSettingValueByKey(Globals::PASSENGER_SHARE_BONUS_AMOUNT) : $this->passenger_share_bonus);
                Settings::model()->setSettingValueByKey(Globals::APP_POST_PER_PAGE, $this->app_post_per_page == null ? Settings::model()->getSettingValueByKey(Globals::APP_POST_PER_PAGE) : $this->app_post_per_page);
                Settings::model()->setSettingValueByKey(Globals::MIN_REDEEM_AMOUNT, $this->min_redeem_amount == null ? Settings::model()->getSettingValueByKey(Globals::MIN_REDEEM_AMOUNT) : $this->min_redeem_amount);
                Settings::model()->setSettingValueByKey(Globals::MIN_TRANSFER_AMOUNT, $this->min_transfer_amount == null ? Settings::model()->getSettingValueByKey(Globals::MIN_TRANSFER_AMOUNT) : $this->min_transfer_amount);
                Settings::model()->setSettingValueByKey(Globals::CANCELLATION_FEE, $this->cancellation_fee == null ? Settings::model()->getSettingValueByKey(Globals::CANCELLATION_FEE) : $this->cancellation_fee);
                Settings::model()->setSettingValueByKey(Globals::MAX_DRIVER_RECEIVE_PUSH, $this->max_driver == null ? Settings::model()->getSettingValueByKey(Globals::MAX_DRIVER_RECEIVE_PUSH) : $this->max_driver);
                Settings::model()->setSettingValueByKey(Globals::DRIVER_EARN, $this->driver_earn == null ? Settings::model()->getSettingValueByKey(Globals::DRIVER_EARN) : $this->driver_earn);
                Settings::model()->setSettingValueByKey(Globals::MIN_BALANCE_PLACE_REQUEST, $this->min_balance_place_request == null ? Settings::model()->getSettingValueByKey(Globals::MIN_BALANCE_PLACE_REQUEST) : $this->min_balance_place_request);
                Settings::model()->setSettingValueByKey(Globals::ADMIN_PHONE_NUMBER, $this->admin_phone_number == null ? Settings::model()->getSettingValueByKey(Globals::ADMIN_PHONE_NUMBER) : $this->admin_phone_number);
                Settings::model()->setSettingValueByKey(Globals::AUTO_RELOAD_DATA_MAP, $this->reload_map == null ? Settings::model()->getSettingValueByKey(Globals::AUTO_RELOAD_DATA_MAP) : $this->reload_map);
                Settings::model()->setSettingValueByKey(Globals::TIME_AUTO_RELOAD_DATA_MAP, $this->time_reload_map == null ? Settings::model()->getSettingValueByKey(Globals::TIME_AUTO_RELOAD_DATA_MAP) : $this->time_reload_map);
                Settings::model()->setSettingValueByKey(Globals::TIME_TO_SEND_REQUEST_AGAIN, $this->time_to_send_request_again == null ? Settings::model()->getSettingValueByKey(Globals::TIME_TO_SEND_REQUEST_AGAIN) : $this->time_to_send_request_again);
                Settings::model()->setSettingValueByKey(Globals::MAX_TIME_SEND_REQUEST, $this->max_time_send_request == null ? Settings::model()->getSettingValueByKey(Globals::MAX_TIME_SEND_REQUEST) : $this->max_time_send_request);
                Settings::model()->setSettingValueByKey(Globals::ESTIMATE_FARE_SPEED, $this->estimate_fare_speed == null ? Settings::model()->getSettingValueByKey(Globals::ESTIMATE_FARE_SPEED) : $this->estimate_fare_speed);
                Settings::model()->setSettingValueByKey(Globals::AUTO_APPROVE_FOR_REGISTER_DRIVER, $this->auto_approve_for_register_driver == null ? Settings::model()->getSettingValueByKey(Globals::AUTO_APPROVE_FOR_REGISTER_DRIVER) : $this->auto_approve_for_register_driver);

                $transaction->commit();
                $isSave = true;

            } catch (Exception $e) {
                $transaction->rollback();
            }
        }
        if (!$isSave) {
            return false;
        }
        return true;
    }
}