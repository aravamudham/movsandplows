<?php

/**
 * Created by Fruity Solution Co.Ltd.
 * User: Jackfruit
 * Date: 7/10/13 - 10:22 AM
 *
 * Please keep copyright headers of source code files when use it.
 * Thank you!
 */
class ApiController extends Controller
{

    public function actions()
    {
        return array(
            'login' => 'application.controllers.api.LoginAction',
            'updateProfile' => 'application.controllers.api.UpdateProfileAction',
            'changePassword' => 'application.controllers.api.ChangePasswordAction',
            'showUserInfo' => 'application.controllers.api.ShowUserInfoAction',
            'forgetPassword' => 'application.controllers.api.ForgetPasswordAction',
            'resetPassword' => 'application.controllers.api.ResetPasswordAction',
            'logout' => 'application.controllers.api.LogoutAction',

            'register' => 'application.controllers.api.RegisterAction',
            'driverRegister' => 'application.controllers.api.DriverRegisterAction',
            'serverTime' => 'application.controllers.api.ServerTimeAction',
            'selectLink' => 'application.controllers.api.SelectLinkAction',//not use
            'createRequest' => 'application.controllers.api.CreateRequestAction',
            'searchDriver' => 'application.controllers.api.SearchDriverAction', // not use
            'showMyRequest' => 'application.controllers.api.ShowMyRequestAction',
            'driverConfirm' => 'application.controllers.api.DriverConfirmAction',
            'cancelRequest' => 'application.controllers.api.CancelRequestAction',
            'showMyTrip' => 'application.controllers.api.ShowMyTripAction',
            'showTripDetail' => 'application.controllers.api.ShowTripDetailAction',
            'cancelTrip' => 'application.controllers.api.CancelTripAction',
            'updateCoordinate' => 'application.controllers.api.UpdateCoordinateAction',
            'showDistance' => 'application.controllers.api.ShowDistanceAction',
            'startTrip' => 'application.controllers.api.StartTripAction',
            'endTrip' => 'application.controllers.api.EndTripAction',
            'ratePassenger' => 'application.controllers.api.RatePassengerAction',
            'rateDriver' => 'application.controllers.api.RateDriverAction',
            'pointExchange' => 'application.controllers.api.PointExchangeAction',
            'pointRedeem' => 'application.controllers.api.PointRedeemAction',
            'pointTransfer' => 'application.controllers.api.PointTransferAction',
            'test' => 'application.controllers.api.TestAction',
            'searchUser' => 'application.controllers.api.SearchUserAction',

            //2/7
            'online' => 'application.controllers.api.OnlineAction',
            'addInfo' => 'application.controllers.api.AddInfoAction',
            'transactionHistory' => 'application.controllers.api.TransactionHistoryAction',
            //15/7
            'updateDriverData' => 'application.controllers.api.UpdateDriverDataAction',
            'tripPayment' => 'application.controllers.api.TripPaymentAction',
            //22/7
            'shareApp' => 'application.controllers.api.ShareAppAction',
            //28/7
            'authorize' => 'application.controllers.api.AuthorizeAction',
            'prepareLogin' => 'application.controllers.api.PrepareLoginAction',
            //5/8
            'generalSettings' => 'application.controllers.api.ShowGeneralSettingsAction',
            //27/09
            'showStateCity' => 'application.controllers.api.ShowStateCityAction',

            //base64
            'driverRegisterAndroid' => 'application.controllers.api.DriverRegisterAndroidAction',
            'updateDriverDataAndroid' => 'application.controllers.api.UpdateDriverDataAndroidAction',
            'getDriverLocation' => 'application.controllers.api.GetDriverLocation',
            'needHelpTrip' => 'application.controllers.api.NeedHelpTripAction',
            'driverArrived' => 'application.controllers.api.DriverArrivedAction',

            //
            'signupAndroid'=>'application.controllers.api.SignupAndroidAction',
            'signup'=>'application.controllers.api.SignupAction',
            'loginNormal'=>'application.controllers.api.LoginNormalAction',
            'forgotPassword'=>'application.controllers.api.ForgetPasswordAction',
        );
    }


    public static function getStatusCodeMessage($status)
    {
        $codes = array(
            200 => 'OK',
            500 => 'ERROR: Bad request. API doesn\'t exist OR request failed due to some reason.',
        );
        return (isset($codes[$status])) ? $codes[$status] : null;
    }

    public static function sendResponse($status = 200, $body = '', $content_type = 'application/json')
    {
        header('HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status));
        header('Content-type: ' . $content_type);
        if (trim($body) != '') echo $body;
        Yii::app()->end();
    }

    public static function ActionXml($status = 200, $body = '', $content_type = 'application/xml')
    {
        /*var_dump(Trip::model()->find('driverId = 100 and status not in ('.Globals::TRIP_STATUS_FINISH.')'));
        exit();
        var_dump(UserDriver::model()->findAll('isOnline = '.Globals::STATUS_ONLINE));
        exit();
        var_dump(UserDriver::model()->findAll('isOnline = '.Globals::STATUS_ONLINE.' and userId in (select driverId from trip where status not in ('.Globals::TRIP_STATUS_FINISH.') )'));
        exit();*/
        header('HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status));
        header('Content-type: ' . $content_type);

        $userDrivers = UserDriver::model()->findAll('isOnline = ' . Globals::STATUS_ONLINE);

        $body = '<markers>';
        if (count($userDrivers) > 0) {
            foreach ($userDrivers as $driver) {
                $lat_long = User::model()->getLatLong($driver->userId);
                $vehicle = Vehicle::model()->getInfoVehicles($driver->userId);

                $driverName = User::model()->getFullName($driver->userId);
                $carPlate = $brand = $model = $year = '';
                if (count($vehicle) > 0) {
                    $carPlate = $vehicle->carPlate;
                    $brand = $vehicle->brand;
                    $model = $vehicle->model;
                    $year = $vehicle->year;
                }

                $type = 'free';
                //var_dump(Trip::model()->checkDriverTripNeedhelp($driver->userId));die;
                $tripData = Trip::model()->find('driverId = ' . $driver->userId . ' and need_help = 1');
                $tripId = '';
                /*var_dump($tripData);
                die;*/
                if (count($tripData) > 0) {
                    $type = 'help';
                    $tripId = $tripData->id;
                } else if (Trip::model()->checkDriverTrip($driver->userId)) {
                    $type = 'busy';
                    $tripData = Trip::model()->find('driverId = ' . $driver->userId . '');
                    $tripId = $tripData->id;
                }
                $body .= '<marker id="' . $driver->userId . '" tripId="' . $tripId . '" name="' . $driverName . '" carPlate="' . $carPlate . '" brand="' . $brand . '" model="' . $model . '" year="' . $year . '" lat="' . $lat_long["lat"] . '" lng="' . $lat_long["long"] . '" type="' . $type . '"/>';
            }
        }
        $body .= '</markers>';
        if (trim($body) != '') echo $body;
        Yii::app()->end();
    }

    public static function ActionDetailTripXml($status = 200, $body = '', $content_type = 'application/xml')
    {
        /*var_dump(Trip::model()->find('driverId = 100 and status not in ('.Globals::TRIP_STATUS_FINISH.')'));
        exit();
        var_dump(UserDriver::model()->findAll('isOnline = '.Globals::STATUS_ONLINE));
        exit();
        var_dump(UserDriver::model()->findAll('isOnline = '.Globals::STATUS_ONLINE.' and userId in (select driverId from trip where status not in ('.Globals::TRIP_STATUS_FINISH.') )'));
        exit();*/
        header('HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status));
        header('Content-type: ' . $content_type);
        //echo $_GET['id'];
        $tripData = Trip::model()->findByPk($_GET['id']);
        //var_dump($tripData);exit();
        $driver = UserDriver::model()->find('userId = ' . $tripData->driverId);//.' and isOnline = '.Globals::STATUS_ONLINE

        $body = '<markers>';
        if (count($driver) > 0) {
            //foreach ($userDrivers as $driver){
            $lat_long = User::model()->getLatLong($driver->userId);
            $vehicle = Vehicle::model()->getInfoVehicles($driver->userId);

            $driverName = User::model()->getFullName($driver->userId);
            $carPlate = $brand = $model = $year = '';
            if (count($vehicle) > 0) {
                $carPlate = $vehicle->carPlate;
                $brand = $vehicle->brand;
                $model = $vehicle->model;
                $year = $vehicle->year;
            }

            $type = 'free';
            //var_dump(Trip::model()->checkDriverTripNeedhelp($driver->userId));die;
            //$tripData = Trip::model()->find('driverId = '.$driver->userId.' and need_help = 1');
            $tripId = $tripData->id;

            $body .= '<marker id="' . $tripData->id . '" tripId="' . $tripId . '" name="' . $driverName . '" carPlate="' . $carPlate . '" brand="' . $brand . '" model="' . $model . '" year="' . $year . '" lat="' . $lat_long["lat"] . '" lng="' . $lat_long["long"] . '" latTrip="' . $tripData->startLat . '" lngTrip="' . $tripData->startLong . '" type="u"/>';
            $body .= '<marker id="' . $tripData->id . '" tripId="' . $tripId . '" name="' . $driverName . '" carPlate="' . $carPlate . '" brand="' . $brand . '" model="' . $model . '" year="' . $year . '" lat="' . $lat_long["lat"] . '" lng="' . $lat_long["long"] . '" latTrip="' . $tripData->startLat . '" lngTrip="' . $tripData->startLong . '" type="a"/>';
            $body .= '<marker id="' . $tripData->id . '" tripId="' . $tripId . '" name="' . $driverName . '" carPlate="' . $carPlate . '" brand="' . $brand . '" model="' . $model . '" year="' . $year . '" lat="' . $lat_long["lat"] . '" lng="' . $lat_long["long"] . '" latTrip="' . $tripData->endLat . '" lngTrip="' . $tripData->endLong . '" type="b"/>';
            //}
        }
        $body .= '</markers>';
        if (trim($body) != '') echo $body;
        Yii::app()->end();
    }
}