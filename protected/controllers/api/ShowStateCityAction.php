<?php

/**
 * Created by PhpStorm.
 * User: KIEMDOAN
 * Date: 9/27/2016
 * Time: 4:55 PM
 */
class ShowStateCityAction extends CAction
{
    public function run()
    {
        $states = State::model()->findAll('status = 1 ORDER BY orderNumber');
        $data = array();
        if (count($states) > 0) {
            foreach ($states as $stateItem) {

                $cities = array();
                $listCity = City::model()->findAll('stateId = ' . $stateItem->id . ' AND status = 1 ORDER BY orderNumber');
                if (isset($listCity)) {
                    foreach ($listCity as $cityItem) {
                        $city = array(
                            'cityId' => $cityItem->id,
                            'cityName' => $cityItem->name,
                        );
                        $cities[] = $city;
                    }
                }

                $item = array(
                    'stateId' => $stateItem->id,
                    'stateName' => $stateItem->name,
                    'stateCities' => $cities,
                );
                $data[] = $item;
            }
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => $data,
                'message' => 'OK',)));
        } else {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Not found state',)));
        }
    }
}