<?php

class AutoController extends Controller
{
    public function actionComplete($isDriver = NULL) {
        $criteria = new CDbCriteria;
        $criteria->select = array('id', 'fullName', 'email');
        $criteria->addSearchCondition('fullName',  strtoupper( $_GET['term']) ) ;
        if($isDriver == Globals::STATUS_ACTIVE)
        {
            $criteria->addCondition('isDriver ='.Globals::STATUS_ACTIVE);
        }
        $criteria->limit = 15;
        $data = User::model()->findAll($criteria);

        $arr = array();

        foreach ($data as $item) {

            $arr[] = array(
                'id' => $item->id,
                'value' => $item->fullName,
                'label' => $item->fullName. ' <'. $item->email. '>',
            );
        }

        echo CJSON::encode($arr);

    }

}