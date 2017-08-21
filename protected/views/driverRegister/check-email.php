<?php
/* @var $model CheckEmailForm */
/* @var $form CActiveForm */
/* @var $this DriverRegisterController */

?>
<div class="content">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'check-email-form',
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
		),
		'htmlOptions' => array(
			'class' => 'login-form',
		)
	)); ?>
	<h3 class="form-title">Step 1: Validate Your Email</h3>

	<div class="form-group">
		<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		<label class="control-label visible-ie8 visible-ie9">Your Email</label>

		<div class="input-icon">
			<i class="fa fa-email"></i>
			<?php echo $form->textField($model, 'email', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Your Email')); ?>
			<?php echo $form->error($model, 'email'); ?>
		</div>
		<br/>
		<p class="help-block">
			If your email is valid, a message contain token will be sent to your email address.
			Please check your email and get that token for next step.
		</p>
	</div>

	<div class="form-actions">
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-success uppercase')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
