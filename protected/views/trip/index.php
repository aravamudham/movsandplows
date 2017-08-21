<?php
/* @var $this TripController */
/* @var $model Vehicle */

$this->breadcrumbs = array(
    'Trips',
);

$this->toolBarActions = array(
    'linkButton' => array(),
    'button' => array(
//        array(
//            'label' => 'Delete',
//            'icon' => 'fa fa-trash',
//            'htmlOptions' => array('class' => 'btn btn-danger', 'onclick' => "deleteAll();")
//        )
    ),
    'dropdown' => array(),
);

$confirmMessage = Yii::t('common', 'confirmMessage.delete');
$selectItemText = Yii::t('common', 'errorMessage.selectItem');
$deleteUrl = Yii::app()->createUrl('trip/multipleDelete');

Yii::app()->clientScript->registerScript('deleteManyScript', "
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
                data: decodeURIComponent($('#trip-action-form').serialize()) + '&ajax=1&action=delete_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#trip-grid').yiiGridView('update', {
                        data: $('#trip-grid-search').serialize()
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

Yii::app()->clientScript->registerScript('reload_trip', "
var reload_trip = function() {
    $('#trip-grid').yiiGridView('update', {
        data: $('#trip-grid-search').serialize()
    });
}
", CClientScript::POS_END);
?>
<style>
    .tr_need_help {
        background-color: #F3565D !important;
        color: white !important;
    }
</style>
<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-suitcase"></i>Trips
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_trip();')) ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->renderPartial('_searchTrip', array(
            'status' => $status,
            'trip' => $model,
        )); ?>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'method' => 'post',
            'id' => 'trip-action-form'
        )); ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'trip-grid',
            'dataProvider' => $trip->search(),
            'rowCssClassExpression' => '$data->need_help == 1 ? "tr_need_help" : ""',
            'columns' => array(
                [
                    'id' => 'checkedIds',
                    'class' => 'CCheckBoxColumn',
                    'selectableRows' => 2,
                ],
                array(
                    'name' => 'id',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'passengerId',
                    'type' => 'raw',
                    'value' => function ($data) {
                        return $data->passenger->fullName;
                    },
                ),
                array(
                    'name' => 'driverId',
                    'type' => 'raw',
                    'value' => function ($data) {
                        return $data->driver->fullName;
                    },
                ),
                //'link',
                array(
                    'name' => 'link',
                    'type' => 'raw',
                    'value' => function ($data) {
                        return Trip::model()->getLabelCarTypeFromKey($data->link);
                    },
                ),
                'startTime',
                'endTime',
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value' => '$data->getStatusLabel()',
                ),
                'dateCreated',
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{view}{update}{delete}',
                ),
            ),
            'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
