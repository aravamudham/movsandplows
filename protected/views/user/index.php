<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
    'Users',
);
$this->toolBarActions = array(
    'linkButton' => array(),
    'button' => array(
        array(
            'label' => 'Active User',
            'icon' => 'fa fa-check',
            'htmlOptions' => array('class' => 'btn btn-success', 'onclick' => "active();")
        ),
        array(
            'label' => 'Inactive User',
            'icon' => 'fa fa-minus-circle',
            'htmlOptions' => array('class' => 'btn btn-warning', 'onclick' => "inactive();")
        ),
        array(
            'label' => 'Delete',
            'icon' => 'fa fa-trash',
            'htmlOptions' => array('class' => 'btn btn-danger', 'onclick' => "deleteAll();")
        )
    ),
    'dropdown' => array(),
);

$confirmMessage = Yii::t('common', 'confirmMessage.delete');
$selectItemText = Yii::t('common', 'errorMessage.selectItem');
$activeUrl = Yii::app()->createUrl('user/active');
$inactiveUrl = Yii::app()->createUrl('user/inactive');
$deleteUrl = Yii::app()->createUrl('user/multipleDelete');


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
                data: decodeURIComponent($('#user-action-form').serialize()) + '&ajax=1&action=active_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#user-grid').yiiGridView('update', {
                        data: $('#user-grid-search').serialize()
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
                data: decodeURIComponent($('#user-action-form').serialize()) + '&ajax=1&action=inactive_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#user-grid').yiiGridView('update', {
                        data: $('#user-grid-search').serialize()
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
                data: decodeURIComponent($('#user-action-form').serialize()) + '&ajax=1&action=delete_many'
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#user-grid').yiiGridView('update', {
                        data: $('#user-grid-search').serialize()
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


Yii::app()->clientScript->registerScript('reload_user', "
var reload_user = function() {
    $('#user-grid').yiiGridView('update', {
        data: $('#user-grid-search').serialize()
    });
}
", CClientScript::POS_END);
?>
<style>
    .grid-view .button-column {
        text-align: center;
         width: 5%;
    }
</style>
<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="glyphicon glyphicon-user"></i>Users
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <?php echo FHtml::showLink('', array('class' => 'reload', 'href' => 'javascript:;', 'onclick' => 'reload_user();')) ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php $this->renderPartial('_searchUser', array(
            'status' => $status,
            'types' => $types,
            'user' => $model
        )); ?>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'method' => 'post',
            'id' => 'user-action-form'
        )); ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'user-grid',
            'dataProvider' => $user->search(),
            'columns' => array(
                [
                    'id' => 'checkedIds',
                    'class' => 'CCheckBoxColumn',
                    'selectableRows' => 2,
                ],
                'id',
                'fullName',
                array(
                    'name' => 'image',
                    'type' => 'raw',
                    'value' => function ($data) {
                        $image = $data->image;
                        if ($data->typeAccount == \Globals::TYPE_ACCOUNT_NORMAL) {
                            $image = Yii::app()->getBaseUrl(true) . '/upload/user/' . $data->image;
                        }
                        //echo $image; exit;
                        return (!empty($image)) ? CHtml::image($image, "", array("style" => "height: 10em")) : "no image";
                    },

                ),
                'email',
                array(
                    'name' => 'stateId',
                    'type' => 'raw',
                    'value' => function ($data) {
                        /** @var State $state */
                        $state = State::model()->findByPk($data->stateId);
                        if (isset($state)) {
                            return $state->name;
                        } else {
                            return "Not set";
                        }
                    },
                ),
                //array(
                //    'name' => 'cityId',
                //    'type' => 'raw',
                //    'value' => function ($data) {
                //        /** @var State $state */
                //        return $data->cityId;
                //    },
                //),            
                'phone',
                array(
                    'name' => 'isActive',
                    'type' => 'raw',
                    'value' => '$data->getActiveStatusLabel()',
                ),
                array(
                    'name' => 'isDriver',
                    'type' => 'raw',
                    'value' => '$data->getDriverStatusLabel()',
                ),
                /*array(
                    'name' => 'Driver Is Active',
                    'type' => 'raw',
                    'value' => '$data->getDriverIsActiveStatusLabel()',
                ),*/
                array(
                    'name' => 'Driver Is Active',
                    'type' => 'raw',
                    'value' => function($data){
                        $userDriver = UserDriver::model()->find("userId = " . $data->id);
                        //var_dump($userDriver);die;
                        $status = null;
                        $byAdmin = null;
                        if ($userDriver != null) {
                            $status = $userDriver->isActive;
                            $byAdmin = $userDriver->inactiveByAdmin;
                        }
                        /*if ($data->isDriver == \Globals::STATUS_INACTIVE){
                            var_dump($userDriver);
                        }*/
                        //return $data->isDriver.' '.$status;
                        if ($data->isDriver == \Globals::STATUS_ACTIVE && $status == \Globals::STATUS_INACTIVE){
                            return  $data->getDriverIsActiveStatusLabel(false, $byAdmin);
                        }
                        return $data->getDriverIsActiveStatusLabel();
                    },
                ),
                /*
                'token',
                */
                'dateCreated',

                array(
                    'class' => 'CButtonColumn',
                    'template' => '{view}{delete}',
                ),
            ),
            'itemsCssClass' => 'table table-striped table-bordered table-hover dataTable',
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>