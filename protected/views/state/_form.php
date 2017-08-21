<?php
/* @var $this StateController */
/* @var $model State */
/* @var $form CActiveForm */
?>


<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'state-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'role' => 'form',
        'class' => 'form-horizontal'
    )
)); ?>

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

<?php echo $form->errorSummary($model); ?>
<div class="form-body">
    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'col-md-3 control-label')); ?>
        <div class="col-md-6">
            <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'orderNumber', array('class' => 'col-md-3 control-label')); ?>
        <div class="col-md-6">
            <?php echo $form->textField($model, 'orderNumber', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'orderNumber'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', array('class' => 'col-md-3 control-label')); ?>
        <div class="col-md-6">
            <?php echo $form->dropDownList($model, 'status', array('1' => 'Active', '0' => 'Inactive'), array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'status'); ?>
        </div>
    </div>
    <div class="form-actions right">
        <?php
        if ($model->isNewRecord) {
            echo FHtml::button('submit', FHtml::BUTTON_ADD, array(
                'id' => 'my_search',
                'class' => 'btn-primary',
            ));
        } else {
            echo FHtml::button('submit', FHtml::BUTTON_SAVE, array(
                'id' => 'my_search',
                'class' => 'btn-primary',
            ));
        }
        ?>
    </div>
</div>
<?php $this->endWidget(); ?>

