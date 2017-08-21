<?php
/* @var $this PointRedeemController */
/* @var $model PointRedeem */

$this->breadcrumbs=array(
    'Payout',
);

$this->toolBarActions = array(
    'linkButton' => array(
    ),

    'button' => array(
//        array(
//            'label' => 'Approve',
//            'icon' => 'fa fa-check',
//            'htmlOptions' => array('class' => 'btn btn-success', 'onclick' => "approve();")
//        ),
        array(
            'label' => 'Reject',
            'icon' => 'fa fa-minus-circle',
            'htmlOptions' => array('class' => 'btn btn-warning', 'onclick' => "reject();")
        ),
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
$deleteUrl = Yii::app()->createUrl('pointRedeem/multipleDelete');
$activeUrl = Yii::app()->createUrl('pointRedeem/approve');
$inactiveUrl = Yii::app()->createUrl('pointRedeem/reject');
$approveUrl = Yii::app()->createUrl('pointRedeem/approveOne');
$rejectUrl = Yii::app()->createUrl('pointRedeem/rejectOne');

Yii::app()->clientScript->registerScript('deleteManyScript', "
var approveOne = function(requestId) {
            $('#loading').show();
            $.ajax({
                url: \"$approveUrl\",
                dataType: 'json',
                type: 'POST',
                 data: { requestId : requestId },
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pointRedeem-grid').yiiGridView('update', {
                        data: $('#pointRedeem-grid-search').serialize()
                    });
                    $('#loading').hide();
                }
            }).fail(function (jqXHR, textStatus) {
                $('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest') . "\");
            });
        return false;
}
var rejectOne = function(requestId) {
            $('#loading').show();
            $.ajax({
                url: \"$rejectUrl\",
                dataType: 'json',
                type: 'POST',
                 data: { requestId : requestId },
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pointRedeem-grid').yiiGridView('update', {
                        data: $('#pointRedeem-grid-search').serialize()
                    });
                    $('#loading').hide();
                }
            }).fail(function (jqXHR, textStatus) {
                $('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest') . "\");
            });
        return false;
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
                data: decodeURIComponent($('#pointRedeem-action-form').serialize()) + '&ajax=1&action=delete_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pointRedeem-grid').yiiGridView('update', {
                        data: $('#pointRedeem-grid-search').serialize()
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
                data: decodeURIComponent($('#pointRedeem-action-form').serialize()) + '&ajax=1&action=approve_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pointRedeem-grid').yiiGridView('update', {
                        data: $('#pointRedeem-grid-search').serialize()
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
                data: decodeURIComponent($('#pointRedeem-action-form').serialize()) + '&ajax=1&action=reject_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#pointRedeem-grid').yiiGridView('update', {
                        data: $('#pointRedeem-grid-search').serialize()
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

Yii::app()->clientScript->registerScript('reload_pointRedeem', "
var reload_pointRedeem = function() {
    $('#pointRedeem-grid').yiiGridView('update', {
        data: $('#pointRedeem-grid-search').serialize()
    });
}
", CClientScript::POS_END);
?>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-money"></i>Payout
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_pointRedeem();'))?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->renderPartial('_searchPointRedeem', array(
            'pointRedeem'=>$model,
        ));?>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'method' => 'post',
            'id' => 'pointRedeem-action-form'
        )); ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'pointRedeem-grid',
            'dataProvider' => $pr->search(),
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
                'payoutPaypalAddress',
                //'status',
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'value' => '$data->getStatusLabel()',
                ),
                'dateCreated',
                array(
                    'type' => 'raw',
                    'value'=>function ($data) {
                        return  FHtml::showLink('', array('class' => 'btn btn-sm btn-success', 'href' => 'javascript:;', 'onclick' => 'approveOne('.$data->id.');'), 'fa fa-check').
                                FHtml::showLink('', array('class' => 'btn btn-sm btn-warning', 'href' => 'javascript:;', 'onclick' => 'rejectOne('.$data->id.');'),'fa fa-minus-circle');
                    },
                    'headerHtmlOptions' => array(
                        'class' => 'col-md-1'
                    )
                )
            ),
            'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
