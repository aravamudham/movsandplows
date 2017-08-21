<?php
/* @var $this TransactionController */
/* @var $model Transaction */
/* @var $form CActiveForm */


$this->breadcrumbs = array(
    'Adjust Balance',
);
$this->toolBarActions = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);

Yii::app()->clientScript->registerScript('checkUser', "

$('#transaction-form').submit(function() {
  userName =  $( '#userName' ).val();
    if (userName.length == 0){
        $('#Transaction_userId' ).removeAttr('value');
    }
});
");
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-money"></i>Adjust Balance
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
                        <?php echo $form->labelEx($model, 'userId', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?php
                            echo $form->hiddenField($model, 'userId');
                            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                            'name' => 'userName',
                            'sourceUrl' => array('auto/Complete'),
                            'options' => array(
                            'showAnim' => 'fold',
                            'select' => 'js:function(event, ui){
                            jQuery("#Transaction_userId").val(ui.item["id"]);
                            }'
                            ),
                            'htmlOptions' => array(
                            'type' => 'text',
                            'class' => 'form-control',
                            'placeholder' =>Yii::t('common', 'title.user'),
                            ),
                            ));

                            ?>
                            <?php echo $form->error($model, 'userId'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'amount', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?php echo $form->textField($model, 'amount', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'amount'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'type', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-6">
                            <?php echo $form->dropDownList($model, 'type', array('+'=>'Add (+)', '-'=>'Deduct (-)'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'type'); ?>
                        </div>
                    </div>
                    <div class="form-actions right">
                        <?php echo FHtml::button('submit', FHtml::BUTTON_SAVE, array(
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
