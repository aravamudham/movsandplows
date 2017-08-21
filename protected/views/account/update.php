<?php
/* @var $this AccountController */
/* @var $model Account */
$this->breadcrumbs=array(
    $this->getPageTitle()=>array('view','id'=>$model->id),
    'Update'
);
$this->toolBarActions = array(
    'linkButton' => array(
        array(
            'label' => 'Back',
            'icon' => 'fa fa-arrow-left',
            'htmlOptions' => array('class' => 'btn btn-danger', 'href' => Yii::app()->createUrl('account/view',array('id'=>$model->id)))
        )
    ),
    'button' => array(),
    'dropdown' => array(),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>