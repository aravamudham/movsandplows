<?php

class ShowGeneralSettingsAction extends CAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

        if (strlen($token) == 0) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token missing',)));
            exit;
        }

        $checkToken = LoginToken::model()->find('token = :token', array('token' => $token));
        if (!isset($checkToken)) {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Token mismatch')));
            exit;
        }

        $sign_up_start_point = Settings::model()->getSettingValueByKey(Globals::SIGN_UP_START_POINTS);
        $admin_email = Settings::model()->getSettingValueByKey(Globals::ADMIN_EMAIL);
        $distance = Settings::model()->getSettingValueByKey(Globals::DISTANCE_FOR_SEARCHING_DRIVER);
        $driver_share_bonus = Settings::model()->getSettingValueByKey(Globals::DRIVER_SHARE_BONUS_AMOUNT);
        $passenger_share_bonus = Settings::model()->getSettingValueByKey(Globals::PASSENGER_SHARE_BONUS_AMOUNT);
        $min_redeem_amount = Settings::model()->getSettingValueByKey(Globals::MIN_REDEEM_AMOUNT);
        $min_transfer_amount = Settings::model()->getSettingValueByKey(Globals::MIN_TRANSFER_AMOUNT);
        $cancellation_fee = Settings::model()->getSettingValueByKey(Globals::CANCELLATION_FEE);
        $driver_earn = Settings::model()->getSettingValueByKey(Globals::DRIVER_EARN);
        $admin_phone_number = Settings::model()->getSettingValueByKey(Globals::ADMIN_PHONE_NUMBER);
        $time_to_send_request_again = Settings::model()->getSettingValueByKey(Globals::TIME_TO_SEND_REQUEST_AGAIN);
        $max_time_send_request = Settings::model()->getSettingValueByKey(Globals::MAX_TIME_SEND_REQUEST);

        $data = array(
            'sign_up_start_point' => $sign_up_start_point,
            'admin_email' => $admin_email,
            'distance' => $distance,
            'driver_share_bonus' => $driver_share_bonus,
            'passenger_share_bonus' => $passenger_share_bonus,
            'min_redeem_amount' => $min_redeem_amount,
            'min_transfer_amount' => $min_transfer_amount,
            'cancellation_fee' => $cancellation_fee,
            'driver_earn' => $driver_earn,
            'admin_phone_number' => $admin_phone_number,
            'time_to_send_request_again' => $time_to_send_request_again,
            'max_time_send_request' => $max_time_send_request,
            'estimate_fare_speed' => Settings::model()->getSettingValueByKey(Globals::ESTIMATE_FARE_SPEED),
            'ppm_of_link_i' => Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_I),
            'ppm_of_link_ii' => Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_II),
            'ppm_of_link_iii' => Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_III),
            'ppk_of_link_i' => Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_I),
            'ppk_of_link_ii' => Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_II),
            'ppk_of_link_iii' => Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_III),
            'sf_of_link_i' => Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_I),
            'sf_of_link_ii' => Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_II),
            'sf_of_link_iii' => Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_III),
        );


        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => $data,
            'message' => 'OK',)));

    }
}