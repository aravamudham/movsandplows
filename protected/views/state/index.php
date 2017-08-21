<?php
/* @var $this StateController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'States',
);
$this->menu = array(
    array('label' => 'Create State', 'url' => array('create')),
    array('label' => 'Manage State', 'url' => array('admin')),
);

$this->toolBarActions = array(
    'linkButton' => array(
        array(
            'label' => 'Create',
            'icon' => 'fa fa-plus',
            'htmlOptions' => array('class' => 'btn btn-primary', 'href' => Yii::app()->createUrl('state/create'))
        )
    ),
    'button' => array(
//        array(
//            'label' => 'create',
//            'icon' => 'fa fa-plus',
//            'htmlOptions' => array('class' => 'btn btn-primary', 'onclick' => "alert(123);")
//        ),
    ),

    'dropdown' => array(),
);

?>
<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-location-arrow"></i>States
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_vehicle();')) ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'vehicle-grid',
            'dataProvider' => $dataProvider,
            'columns' => array(
                'id',
                'name',
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value' => '$data->getActiveStatusLabel()',
                ),
                'orderNumber',
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{update}{delete}',
                ),
            ),
            'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
        )); ?>
    </div>
</div>


