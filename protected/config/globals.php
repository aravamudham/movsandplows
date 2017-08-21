<?php
define('DS', DIRECTORY_SEPARATOR);
defined('UPLOAD_DIR') or define('UPLOAD_DIR', 'upload');
define('SITE', 'site');
define('CAR_DIR', 'car');
define('USER_DIR', 'user');
define('CAR_DOCUMENT_DIR', 'car_document');
define('UPDATE_PENDING_DIR', 'update_pending');
if (!isset($root_dir)) $root_dir = dirname(dirname(dirname(__FILE__)));
Yii::setPathOfAlias(UPLOAD_DIR, $root_dir . DS . UPLOAD_DIR);
Yii::setPathOfAlias(SITE, $root_dir);

class Globals
{
    const MAP_API_KEY = 'AIzaSyDglYesvhRwwYeAJJ4gRhq5iftGYpRXgD8';//&callback=initMap
    const
        SIGN_UP_START_POINTS = 'SIGN_UP_START_POINTS',
        DISTANCE_FOR_SEARCHING_DRIVER = 'DISTANCE_FOR_SEARCHING_DRIVER',
        MAX_REQUEST_ACCEPTABLE = 'MAX_REQUEST_ACCEPTABLE',
        ADMIN_EMAIL = 'ADMIN_EMAIL',
        GOOGLE_API_KEY = 'GOOGLE_API_KEY',
        PEM_FILE = 'PEM_FILE',
        SF_OF_LINK_I = 'SF_OF_LINK_I',
        SF_OF_LINK_II = 'SF_OF_LINK_II',
        SF_OF_LINK_III = 'SF_OF_LINK_III',
        PPM_OF_LINK_I = 'PPM_OF_LINK_I',
        PPM_OF_LINK_II = 'PPM_OF_LINK_II',
        PPM_OF_LINK_III = 'PPM_OF_LINK_III',
        PPK_OF_LINK_I = 'PPK_OF_LINK_I',
        PPK_OF_LINK_II = 'PPK_OF_LINK_II',
        PPK_OF_LINK_III = 'PPK_OF_LINK_III',
        DRIVER_SHARE_BONUS_AMOUNT = 'DRIVER_SHARE_BONUS_AMOUNT',
        PASSENGER_SHARE_BONUS_AMOUNT = 'PASSENGER_SHARE_BONUS_AMOUNT',
        APP_POST_PER_PAGE = 'APP_POST_PER_PAGE',
        MIN_REDEEM_AMOUNT = 'MIN_REDEEM_AMOUNT',
        MIN_TRANSFER_AMOUNT = 'MIN_TRANSFER_AMOUNT',
        CANCELLATION_FEE = 'CANCELLATION_FEE',
        MAX_DRIVER_RECEIVE_PUSH = 'MAX_DRIVER_RECEIVE_PUSH',
        DRIVER_EARN = 'DRIVER_EARN',
        MIN_BALANCE_PLACE_REQUEST = 'MIN_BALANCE_PLACE_REQUEST',
        ADMIN_PHONE_NUMBER = 'ADMIN_PHONE_NUMBER',
        AUTO_RELOAD_DATA_MAP = 'AUTO_RELOAD_DATA_MAP',
        TIME_AUTO_RELOAD_DATA_MAP = 'TIME_AUTO_RELOAD_DATA_MAP',


        TIME_TO_SEND_REQUEST_AGAIN = 'TIME_TO_SEND_REQUEST_AGAIN',
        MAX_TIME_SEND_REQUEST = 'MAX_TIME_SEND_REQUEST',
        ESTIMATE_FARE_SPEED = 'ESTIMATE_FARE_SPEED',
        AUTO_APPROVE_FOR_REGISTER_DRIVER = 'AUTO_APPROVE_FOR_REGISTER_DRIVER',

        DEVICE_TYPE_ANDROID = '1',
        DEVICE_TYPE_IOS = '2',
        STATUS_ONLINE = '1',
        STATUS_OFFLINE = '0',
        STATUS_IDLE = '1',
        STATUS_BUSY = '0',
        STATUS_ACTIVE = '1',
        STATUS_INACTIVE = '0',
        DOCUMENT = 1,
        MISSING_DOCUMENT = 0,
        GENDER_MALE = '1',
        GENDER_FEMALE = '2',
        GENDER_OTHER = '3',

        USER_TYPE_DRIVER = '1',
        USER_TYPE_DRIVER_PENDING = '0',
        INACTIVE_BY_ADMIN_CUSTOM_INDEX = '2',
        NEW_DRIVER_REGISTER_CUSTOM_INDEX = '3',

        INACTIVE_BY_ADMIN = '0',
        NEW_DRIVER_REGISTER = '1',
        NEW_DRIVER_ADMIN_APPROVED = '2';

    const
        TRIP_STATUS_APPROACHING = '1',
        TRIP_STATUS_IN_PROGRESS = '2',
        TRIP_STATUS_PENDING_PAYMENT = '3',
        TRIP_STATUS_FINISH = '4',
        TRIP_STATUS_NEED_HELP = '5';
    //action transaction
    const
        SYSTEM_ADJUST = '0',
        CANCELLATION_ORDER_FEE = '1',
        EXCHANGE_POINT = '2',
        REDEEM_POINT = '3',
        TRANSFER_POINT = '4',
        TRIP_PAYMENT = '5',
        PASSENGER_SHARE_BONUS = '6',
        DRIVER_SHARE_BONUS = '7',
        COMMISSION_WHEN_PAYMENT_BY_CASH = '8';

    //action payment method
    const PAYMENT_METHOD_PAYPAL = '1',
        PAYMENT_METHOD_CREDIT = '2';

    const ROLE_ADMIN = '0',
        ROLE_MODERATOR = '1';

    const
        TYPE_ACCOUNT_NORMAL = 0,
        TYPE_ACCOUNT_SOCIAL = 1;


    public static function getListStatusOfTrip()
    {
        $status = array();
        $status[Globals::TRIP_STATUS_APPROACHING] = Yii::t('trip', 'title.approaching');
        $status[Globals::TRIP_STATUS_IN_PROGRESS] = Yii::t('trip', 'title.in.process');
        $status[Globals::TRIP_STATUS_PENDING_PAYMENT] = Yii::t('trip', 'title.pending.payment');
        $status[Globals::TRIP_STATUS_FINISH] = Yii::t('trip', 'title.finish');

        return $status;
    }

    public static function pushAndroid($registrationIDs, $msg)
    {
        $apiKey = Settings::model()->getSettingValueByKey(Globals::GOOGLE_API_KEY);
        $url = 'https://android.googleapis.com/gcm/send';

        $loop = ceil(count($registrationIDs) / 1000);


        for ($i = 1; $i <= $loop; $i++) {
            if (0 < count($registrationIDs) && count($registrationIDs) < 1000)
                $registrationID = $registrationIDs;
            else {
                $registrationID = array_slice($registrationIDs, 0, 1000);
                $registrationIDs = array_slice($registrationIDs, 1000, count($registrationIDs));
            }

            $fields = array
            (
                'registration_ids' => $registrationID,
                'data' => $msg
            );

            $headers = array(
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            //echo $result;
        }
    }

    public static function pushIos($idevices, $message)
    {
        $pem_location = Settings::model()->getSettingValueByKey(Globals::PEM_FILE);
        $badge = 1;
        $sound = 'default';
        $development = true;//make it false if it is not in development mode
        $passphrase = 'linkrider';//your passphrase

        $payload = array();
        $payload['aps'] = array('alert' => $message, 'badge' => intval($badge), 'sound' => $sound);
        $payload = json_encode($payload);

        $apns_url = NULL;
        $apns_cert = NULL;
        $apns_port = 2195;

        if ($development) {
            $apns_url = 'gateway.sandbox.push.apple.com';
            $apns_cert = Yii::getPathOfAlias(UPLOAD_DIR) . DIRECTORY_SEPARATOR . 'pem' . DIRECTORY_SEPARATOR . $pem_location;
        } else {
            $apns_url = 'gateway.push.apple.com';
            $apns_cert = Yii::getPathOfAlias(UPLOAD_DIR) . DIRECTORY_SEPARATOR . 'pem' . DIRECTORY_SEPARATOR . $pem_location;
        }

        $stream_context = stream_context_create();
        stream_context_set_option($stream_context, 'ssl', 'local_cert', $apns_cert);
        stream_context_set_option($stream_context, 'ssl', 'passphrase', $passphrase);

        $apns = stream_socket_client('ssl://' . $apns_url . ':' . $apns_port, $error, $error_string, 2, STREAM_CLIENT_CONNECT, $stream_context);
        foreach ($idevices as $idevice) {
            $token = $idevice;
            $device_tokens = str_replace("<", "", $token);
            $device_tokens1 = str_replace(">", "", $device_tokens);
            $device_tokens2 = str_replace(' ', '', $device_tokens1);

            $apns_message = chr(0) . chr(0) . chr(32) . pack('H*', $device_tokens2) . chr(0) . chr(strlen($payload)) . $payload;

            fwrite($apns, $apns_message);
            //Globals::checkAppleErrorResponse($apns);
        }
        @socket_close($apns);
        @fclose($apns);
    }

    public static function generateTransactionId($userId)
    {
        $s = strtoupper(md5(uniqid(rand(), true)));
        return substr($s . $userId, 18, strlen($s));
    }

    public static function senEmail($toEmail, $view, $title, $data)
    {
        $status = true;
        try {
            $adminMail = Settings::model()->getSettingValueByKey(Globals::ADMIN_EMAIL);
            $message = new YiiMailMessage;
            $message->view = $view;
            $params = $data;
            $message->setBody($params, 'text/html');
            $message->subject = Yii::app()->name . ' - ' . $title;
            $message->addTo($toEmail);
            $message->from = $adminMail;
            Yii::app()->mail->send($message);
        } catch (Exception $e) {
            $status = false;
        }
        return $status;
    }

    public static function calculateFare($link, $startTime, $endTime, $distance)
    {
        $startFare = Settings::model()->getSettingValueByKey('SF_OF_LINK_' . $link);
        $ppm = Settings::model()->getSettingValueByKey('PPM_OF_LINK_' . $link);
        $ppk = Settings::model()->getSettingValueByKey('PPK_OF_LINK_' . $link);
        $minutes = ($endTime - $startTime) / 60;

        $fare = floatval($startFare) + floatval($ppm) * $minutes + floatval($ppk) * $distance;

        return round($fare, 2);
    }

    public static function checkAppleErrorResponse($fp)
    {

        //byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID).
        // Should return nothing if OK.

        //NOTE: Make sure you set stream_set_blocking($fp, 0) or else fread will pause your script and wait
        // forever when there is no response to be sent.

        $apple_error_response = fread($fp, 6);

        if ($apple_error_response) {

            // unpack the error response (first byte 'command" should always be 8)
            $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);

            if ($error_response['status_code'] == '0') {
                $error_response['status_code'] = '0-No errors encountered';

            } else if ($error_response['status_code'] == '1') {
                $error_response['status_code'] = '1-Processing error';

            } else if ($error_response['status_code'] == '2') {
                $error_response['status_code'] = '2-Missing device token';

            } else if ($error_response['status_code'] == '3') {
                $error_response['status_code'] = '3-Missing topic';

            } else if ($error_response['status_code'] == '4') {
                $error_response['status_code'] = '4-Missing payload';

            } else if ($error_response['status_code'] == '5') {
                $error_response['status_code'] = '5-Invalid token size';

            } else if ($error_response['status_code'] == '6') {
                $error_response['status_code'] = '6-Invalid topic size';

            } else if ($error_response['status_code'] == '7') {
                $error_response['status_code'] = '7-Invalid payload size';

            } else if ($error_response['status_code'] == '8') {
                $error_response['status_code'] = '8-Invalid token';

            } else if ($error_response['status_code'] == '255') {
                $error_response['status_code'] = '255-None (unknown)';

            } else {
                $error_response['status_code'] = $error_response['status_code'] . '-Not listed';

            }

            echo '<br><b>+ + + + + + ERROR</b> Response Command:<b>' . $error_response['command'] . '</b>&nbsp;&nbsp;&nbsp;Identifier:<b>' . $error_response['identifier'] . '</b>&nbsp;&nbsp;&nbsp;Status:<b>' . $error_response['status_code'] . '</b><br>';

            echo 'Identifier is the rowID (index) in the database that caused the problem, and Apple will disconnect you from server. To continue sending Push Notifications, just start at the next rowID after this Identifier.<br>';

            return true;
        }

        return false;
    }

    public static function getThumbnailUrl($type)
    {
        $link = Yii::app()->request->baseUrl . DIRECTORY_SEPARATOR . UPLOAD_DIR . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR;
        return $link;
    }

    public static function EstimateFare($link, $estimateDistance)
    {
        $estimate_fare = 0;
        switch ($link) {
            case 'I':
                $estimate_fare = ($estimateDistance / Settings::model()->getSettingValueByKey(Globals::ESTIMATE_FARE_SPEED)) * Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_I) * 60
                    + Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_I) * $estimateDistance
                    + Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_I);
                break;
            case 'II':
                $estimate_fare = ($estimateDistance / Settings::model()->getSettingValueByKey(Globals::ESTIMATE_FARE_SPEED)) * Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_II) * 60
                    + Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_II) * $estimateDistance
                    + Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_II);
                break;
            case 'III':
                $estimate_fare = ($estimateDistance / Settings::model()->getSettingValueByKey(Globals::ESTIMATE_FARE_SPEED)) * Settings::model()->getSettingValueByKey(Globals::PPM_OF_LINK_III) * 60
                    + Settings::model()->getSettingValueByKey(Globals::PPK_OF_LINK_III) * $estimateDistance
                    + Settings::model()->getSettingValueByKey(Globals::SF_OF_LINK_III);
                break;
        }
        return '$' . round($estimate_fare, 2) . ' ~ ' . $estimateDistance . 'KM';
    }

}
