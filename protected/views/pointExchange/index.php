<?php
/* @var $this PointExchangeController */
/* @var $model PointExchange */

$this->breadcrumbs=array(
    'Exchange',
);

$this->toolBarActions = array(
    'linkButton' => array(
    ),
    'button' => array(
//        array(
//            'label' => 'Delete',
//            'icon' => 'fa fa-trash',
//            'htmlOptions' => array('class' => 'btn btn-danger', 'onclick' => "deleteAll();")
//        )
    ),
    'dropdown' => array(
    ),
);

$confirmMessage = Yii::t('common', 'confirmMessage.delete');
$selectItemText = Yii::t('common', 'errorMessage.selectItem');
$deleteUrl = Yii::app()->createUrl('pointExchange/multipleDelete');

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
                data: decodeURIComponent($('#pointExchange-action-form').serialize()) + '&ajax=1&action=delete_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pointExchange-grid').yiiGridView('update', {
                        data: $('#pointExchange-grid-search').serialize()
                    });
                    $('#loading').hide();
                }
            }).fail(function (jqXHR, textStatus) {
                $('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest'). "\");
            });
            }
        return false;
	}
}
", CClientScript::POS_BEGIN);

Yii::app()->clientScript->registerScript('reload_pointExchange', "
var reload_pointExchange = function() {
    $('#pointExchange-grid').yiiGridView('update', {
        data: $('#pointExchange-grid-search').serialize()
    });
}
", CClientScript::POS_END);
?>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-money"></i>Exchange
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_pointExchange();'))?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->renderPartial('_searchPointExchange', array(
            'paymentMethod'=>$paymentMethod,
            'pointExchange'=>$model,
        ));?>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'method' => 'post',
            'id' => 'pointExchange-action-form'
        )); ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'pointExchange-grid',
            'dataProvider' => $pe->search(),
            'columns' => array(
                [
                    'id' => 'checkedIds',
                    'class' => 'CCheckBoxColumn',
                    'selectableRows' => 2,
                ],
//                'userId',
                array(
                    'name' => 'userId',
                    'type' => 'raw',
                    'value'=>function ($data) {
                        return CHtml::link($data->user->fullName,array('user/view', 'id'=>$data->userId));
                    },
                ),
                'amount',
                //'paymentMethod',
                array(
                    'name' => 'paymentMethod',
                    'type' => 'raw',
                    'value' => '$data->getPaymentMethodLabel()',
                ),
                //'status',
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value' => '$data->getStatusLabel()',
                ),
                'dateCreated',
            ),
            'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
