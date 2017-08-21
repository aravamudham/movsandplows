<?php
/* @var $this StateController */
/* @var $model State */

$this->breadcrumbs = array(
    'States' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List State', 'url' => array('index')),
    array('label' => 'Create State', 'url' => array('create')),
    array('label' => 'View State', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage State', 'url' => array('admin')),
);
?>
<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-suitcase"></i>Update State <?php echo $model->id; ?>
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



