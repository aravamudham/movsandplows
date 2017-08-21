<?php
/* @var $this VehicleController */
/* @var $model Vehicle */

$this->breadcrumbs = array(
    'Vehicles',
);
$this->toolBarActions = array(
    'linkButton' => array(
//        array(
//            'label' => 'create',
//            'icon' => 'fa fa-plus',
//            'htmlOptions' => array('class' => 'btn purple', 'href' => Yii::app()->createUrl('vehicle/create'))
//        )
    ),
    'button' => array(
//        array(
//            'label' => 'create',
//            'icon' => 'fa fa-plus',
//            'htmlOptions' => array('class' => 'btn btn-primary', 'onclick' => "alert(123);")
//        ),
        array(
            'label' => 'Active',
            'icon' => 'fa fa-check',
            'htmlOptions' => array('class' => 'btn btn-success', 'onclick' => "active();")
        ),
        array(
            'label' => 'Inactive',
            'icon' => 'fa fa-minus-circle',
            'htmlOptions' => array('class' => 'btn btn-warning', 'onclick' => "inactive();")
        ),
//        array(
//            'label' => 'Delete',
//            'icon' => 'fa fa-trash',
//            'htmlOptions' => array('class' => 'btn btn-danger', 'onclick' => "deleteAll();")
//        )
    ),

    'dropdown' => array(
//        array(
//            'label' => 'index',
//            'htmlOptions' => array('href' => Yii::app()->createUrl('vehicle/create'))
//        ),
//        array(
//            'label' => 'divider',
//            'htmlOptions' => array()
//        ),
//        array(
//            'label' => 'index',
//            'htmlOptions' => array('href' => Yii::app()->createUrl('vehicle/create'))
//        ),
    ),
);

$confirmMessage = Yii::t('common', 'confirmMessage.delete');
$selectItemText = Yii::t('common', 'errorMessage.selectItem');
$activeUrl = Yii::app()->createUrl('vehicle/active');
$inactiveUrl = Yii::app()->createUrl('vehicle/inactive');
$deleteUrl = Yii::app()->createUrl('vehicle/multipleDelete');

Yii::app()->clientScript->registerScript('activeManyScript', "
var active = function() {
    if ($('input[id^=\"checkedIds\"]:checked').length <= 0) {
	    alert(\"$selectItemText\");
        return false;
	} else {
            $('#loading').show();
            $.ajax({
                url: \"$activeUrl\",
                dataType: 'json',
                type: 'POST',
                data: decodeURIComponent($('#vehicle-action-form').serialize()) + '&ajax=1&action=active_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#vehicle-grid').yiiGridView('update', {
                        data: $('#vehicle-grid-search').serialize()
                    });
                    $('#loading').hide();
                }
            }).fail(function (jqXHR, textStatus) {
                $('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest') . "\");
            });
        return false;
	}
}
var inactive = function() {
    if ($('input[id^=\"checkedIds\"]:checked').length <= 0) {
	    alert(\"$selectItemText\");
        return false;
	} else {
            $('#loading').show();
            $.ajax({
                url: \"$inactiveUrl\",
                dataType: 'json',
                type: 'POST',
                data: decodeURIComponent($('#vehicle-action-form').serialize()) + '&ajax=1&action=inactive_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#vehicle-grid').yiiGridView('update', {
                        data: $('#vehicle-grid-search').serialize()
                    });
                    $('#loading').hide();
                }
            }).fail(function (jqXHR, textStatus) {
                $('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest') . "\");
            });
        return false;
	}
}
var deleteAll = function() {
    if ($('input[id^=\"checkedIds\"]:checked').length <= 0) {
	    alert(\"$selectItemText\");
        return false;
	} else {
	if (confirm(\"$confirmMessage\")) {
            $('#loading').show();
            $.ajax({
                url: \"$deleteUrl\",
                dataType: 'json',
                type: 'POST',
                data: decodeURIComponent($('#vehicle-action-form').serialize()) + '&ajax=1&action=delete_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#vehicle-grid').yiiGridView('update', {
                        data: $('#vehicle-grid-search').serialize()
                    });
                    $('#loading').hide();
                }
            }).fail(function (jqXHR, textStatus) {
                $('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest') . "\");
            });
            }
        return false;
	}
}
", CClientScript::POS_BEGIN);

Yii::app()->clientScript->registerScript('reload_vehicle', "
var reload_vehicle = function() {
    $('#vehicle-grid').yiiGridView('update', {
        data: $('#vehicle-grid-search').serialize()
    });
}
", CClientScript::POS_END);
?>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-car"></i>Vehicles
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_vehicle();'))?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->renderPartial('_searchVehicle', array(
            'status'=>$status,
            'vehicle'=>$model,
            'issetDocument'=>$issetDocument
        ));?>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'method' => 'post',
            'id' => 'vehicle-action-form'
        )); ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'vehicle-grid',
            'dataProvider' => $vehicle->search(),
            'columns' => array(
                [
                    'id' => 'checkedIds',
                    'class' => 'CCheckBoxColumn',
                    'selectableRows' => 2,
                ],
                'id',
                //'userId',
                //'driver.fullName',
                array(
                    'header' => Yii::t('vehicle', 'title.driver'),
                    'name' => 'userId',
                    'type' => 'raw',
                    'value' => '$data->getDriverName()',
                ),
                'carPlate',
                'brand',
                'model',
                'year',
                'status',
                'document',
                'dateCreated',
                array(
                    'class' => 'CButtonColumn',
                    'template'=>'{view}{update}',
                ),
            ),
            'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>