<?php
/* @var $this TransactionController */
/* @var $model Transaction */
/* @var $form CActiveForm */


$this->breadcrumbs = array(
    'Push Notification',
);
$this->toolBarActions = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog"></i>Push Notification
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'transaction-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'role' => 'form',
                        'class' => 'form-horizontal'
                    )
                )); ?>
                <?php echo $form->errorSummary($model); ?>
                <div class="form-body">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'message', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?php echo $form->textField($model, 'message', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'message'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'receiver', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?php echo $form->dropDownList($model, 'receiver', array('all'=>'All Users', 'driver'=>'Drivers'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'receiver'); ?>
                        </div>
                    </div>
                    <div class="form-actions right">
                        <?php echo FHtml::button('submit', FHtml::BUTTON_SEND, array(
                            'id' => 'my_search',
                            'class' => 'btn-primary',
                        ));
                        ?>
                    </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
