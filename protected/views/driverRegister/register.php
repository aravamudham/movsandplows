<?php
/* @var $model UserDriver */
/* @var $form CActiveForm */
/* @var $this DriverRegisterController */

$link = Yii::app()->getBaseUrl(true) ;

?>

<div class="content">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'driver-register-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'class' => 'login-form',
            'enctype' => 'multipart/form-data',
        )
    )); ?>
    <h3 class="form-title">Step 2: Driver Register</h3>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Token</label>
        <?php echo $form->textField($model, 'token', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Token')); ?>
        <?php echo $form->error($model, 'token'); ?>
        <p class="help-block">
            Token that was sent to your email.
        </p>
    </div>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Car Plate</label>
            <?php echo $form->textField($model, 'carPlate', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Car Plate')); ?>
            <?php echo $form->error($model, 'carPlate'); ?>
    </div>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Brand</label>
            <?php echo $form->textField($model, 'brand', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Brand')); ?>
            <?php echo $form->error($model, 'brand'); ?>
    </div>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Model</label>
            <?php echo $form->textField($model, 'model', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Model')); ?>
            <?php echo $form->error($model, 'model'); ?>
    </div>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Year</label>
            <?php echo $form->textField($model, 'year', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Year')); ?>
            <?php echo $form->error($model, 'year'); ?>
    </div>
	<?php /*
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Status</label>
            <?php echo $form->textField($model, 'status', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Status')); ?>
            <?php echo $form->error($model, 'status'); ?>
    </div>
	<div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Account</label>
            <?php echo $form->textField($model, 'account', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Account')); ?>
            <?php echo $form->error($model, 'account'); ?>
    </div>
	 <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label">Car Document</label>
        <?php echo $form->fileField($model, 'document', array('class' => 'form-control form-control-solid')); ?>
        <?php echo $form->error($model, 'document'); ?>
    </div>
	*/ ?>
    
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label">Car Image 1</label>
        <img src="<?php echo $link. '/images/img_car_one.png'?>" alt="image1" width="100px" style="float: right"/>
        <?php echo $form->fileField($model, 'image1', array('class' => 'form-control form-control-solid')); ?>
        <?php echo $form->error($model, 'image1'); ?>
    </div>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label">Car Image 2</label>
        <img src="<?php echo $link. '/images/img_car_two.png'?>" alt="image1" width="100px" style="float: right"/>
        <?php echo $form->fileField($model, 'image2', array('class' => 'form-control form-control-solid')); ?>
        <?php echo $form->error($model, 'image2'); ?>
    </div>
   
    <div class="form-actions">
        <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-success uppercase')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
