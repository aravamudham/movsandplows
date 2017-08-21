<?php
class ServerTimeAction extends CAction
{
    public function run()
    {
        ApiController::sendResponse(200, CJSON::encode(array(
            'status' => 'SUCCESS',
            'data' => date('Y-m-d H:00:00',time()),
            'message' => 'OK',)));
    }
}