<?php
class TestAction extends CAction
{
    public function run()
    {

                $token = '1481420c229598b6496f26093e43ebcd0b9ff48b266361a074b53fa175e07c0c';
                $gcmId = 'APA91bFbSoV9058qOjNSVEhUOvbSEacd6R-aQ9VgzEceSfL6Xb_4IdxgLLg0-0gsdT6DTcw9bSTVu3uUfE2_iOLYzBuWiPqQZsb-UZyB4_F2MQxUQipy7frKEvO-tjAlQaQEBREllp4e';
                $adevices = array();
                $idevices = array();

                array_push($adevices, $gcmId);
                array_push($idevices, $token);


        $msg = array
        (
        
            'data' => array(
				'tripId'=>'1',
            ),
            'action' => 'driverConfirm',
			'body' => 'Hello Baby',
        );
            if (count($adevices) != 0)
                Globals::pushAndroid($adevices, $msg);
            if (count($idevices) != 0)
                Globals::pushIos($idevices, $msg);
    }
}