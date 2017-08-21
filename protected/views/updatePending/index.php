<?php
/* @var $this UpdatePendingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Pending Driver Updates',
);

$this->toolBarActions = array(
    'linkButton' => array(),
    'button' => array(
        array(
            'label' => 'Approve',
            'icon' => 'fa fa-check',
            'htmlOptions' => array('class' => 'btn btn-success', 'onclick' => "approve();")
        ),
        array(
            'label' => 'Reject',
            'icon' => 'fa fa-minus-circle',
            'htmlOptions' => array('class' => 'btn btn-warning', 'onclick' => "reject();")
        ),
    ),
    'dropdown' => array(),
);
$confirmMessage = Yii::t('common', 'confirmMessage.delete');
$selectItemText = Yii::t('common', 'errorMessage.selectItem');
$activeUrl = Yii::app()->createUrl('updatePending/approve');
$inactiveUrl = Yii::app()->createUrl('updatePending/reject');


Yii::app()->clientScript->registerScript('activeManyScript', "
var approve = function() {
    if ($('input[id^=\"checkedIds\"]:checked').length <= 0) {
	    alert(\"$selectItemText\");
        return false;
	} else {
            $('#loading').show();
            $.ajax({
                url: \"$activeUrl\",
                dataType: 'json',
                type: 'POST',
                data: decodeURIComponent($('#pending-action-form').serialize()) + '&ajax=1&action=approve_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pending-grid').yiiGridView('update', {
                        data: $('#pending-grid-search').serialize()
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
var reject = function() {
    if ($('input[id^=\"checkedIds\"]:checked').length <= 0) {
	    alert(\"$selectItemText\");
        return false;
	} else {
            $('#loading').show();
            $.ajax({
                url: \"$inactiveUrl\",
                dataType: 'json',
                type: 'POST',
                data: decodeURIComponent($('#pending-action-form').serialize()) + '&ajax=1&action=reject_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pending-grid').yiiGridView('update', {
                        data: $('#pending-grid-search').serialize()
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
", CClientScript::POS_BEGIN);

Yii::app()->clientScript->registerScript('reload_pending', "
var reload_pending = function() {
$('#pending-grid').yiiGridView('update', {
data: $('#pending-grid-search').serialize()
});
}
", CClientScript::POS_END);
?>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="glyphicon glyphicon-pencil"></i>Pending Driver Updates
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_pending();')) ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->renderPartial('_searchPending', array(
            'pending' => $model
        )); ?>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'method' => 'post',
            'id' => 'pending-action-form'
        )); ?>
        <div class="table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'pending-grid',
                'dataProvider' => $pending->search(),
                'columns' => array(
                    [
                        'id' => 'checkedIds',
                        'class' => 'CCheckBoxColumn',
                        'selectableRows' => 2,
                    ],
                    //'id',
                    //userId
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
                    //'status',
                    array(
                        'name' => 'document',
                        'type' => 'raw',
                        'value' =>function($data){
                            if(isset($data->document))
                                return  '<a href="'.$this->createUrl('/updatePending/download', array('item_id' => $data->id)).'">'. $data->document .'</a>';
								else
								return '';
                        },
                    ),
                    array(
                        'name' => 'image',
                        'type' => 'raw',
                        'value' =>function($data){
                            if(isset($data->image))
                                return FHtml::showImage(UPDATE_PENDING_DIR.DS.$data->image,100,100,FALSE);
								else
								return '';
                        },
                    ),
                    array(
                        'name' => 'image2',
                        'type' => 'raw',
                        'value' =>function($data){
                            if(isset($data->image2))
                                return FHtml::showImage(UPDATE_PENDING_DIR.DS.$data->image2,100,100,FALSE);
								else
								return '';
                        },
                    ),
                    'phone',
                    'dateCreated',
                    array(
                        'class' => 'CButtonColumn',
                        'template'=>'{view}',
                    ),
                ),
                'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
            )); ?>

        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>