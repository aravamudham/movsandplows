<?php
/* @var $this TripController */
/* @var $model Vehicle */

$this->breadcrumbs=array(
	'Transactions',
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
$deleteUrl = Yii::app()->createUrl('transaction/multipleDelete');

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
                data: decodeURIComponent($('#transaction-action-form').serialize()) + '&ajax=1&action=delete_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#transaction-grid').yiiGridView('update', {
                        data: $('#transaction-grid-search').serialize()
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

Yii::app()->clientScript->registerScript('reload_transaction', "
var reload_transaction = function() {
    $('#transaction-grid').yiiGridView('update', {
        data: $('#transaction-grid-search').serialize()
    });
}
", CClientScript::POS_END);
?>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-usd"></i>Transactions
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_transaction();'))?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->renderPartial('_searchTransaction', array(
            'actions'=>$actions,
            'transaction'=>$model,
        ));?>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'method' => 'post',
            'id' => 'transaction-action-form'
        )); ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'transaction-grid',
            'dataProvider' => $transaction->search(),
            'columns' => array(
                [
                    'id' => 'checkedIds',
                    'class' => 'CCheckBoxColumn',
                    'selectableRows' => 2,
                ],
                array(
                    'name' => 'userId',
                    'type' => 'raw',
                    'value'=>function ($data) {
                        return $data->user->fullName;
                    },
                ),
                'type',
                'amount',
                array(
                    'name' => 'action',
                    'type' => 'raw',
                    'value' => '$data->getActionLabel()',
                ),
                array(
                    'name' => 'destination',
                    'type' => 'raw',
                    'value'=>function ($data) {
                        return isset($data->receiver)?$data->receiver->fullName:'';
                    },
                ),
				'tripId',
                'dateCreated',
            ),
            'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
