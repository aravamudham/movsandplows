<?php
/* @var $this StateController */
/* @var $model State */

$this->breadcrumbs = array(
    'States' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List State', 'url' => array('index')),
    array('label' => 'Manage State', 'url' => array('admin')),
);
?>
<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-location-arrow"></i>Create State
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse" data-original-title="" title="">
            </a>
        </div>
    </div>
    <div class="portlet-body form">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
</div>
