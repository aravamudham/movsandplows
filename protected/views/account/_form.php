<?php
/* @var $this AccountController */
/* @var $model Account */
/* @var $form CActiveForm */

?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet box blue-hoki">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-user"></i><?php echo $this->getPageTitle()?>
				</div>
				<div class="tools">
					<a href="" class="collapse">
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<?php $form = $this->beginWidget('CActiveForm', array(
					'id' => 'account-form',
					'enableAjaxValidation' => false,
					'htmlOptions' => array(
						'role' => 'form',
						'class' => 'form-horizontal'
					)
				)); ?>
				<?php echo $form->errorSummary($model); ?>
				<div class="form-body">
					<div class="form-group">
						<?php echo $form->labelEx($model, 'username', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->textField($model,'username',array('class' => 'form-control', 'readonly'=>true)); ?>
							<?php echo $form->error($model, 'username'); ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo $form->labelEx($model, 'oldPass', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->passwordField($model, 'oldPass', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'oldPass'); ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo $form->labelEx($model, 'newPass', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->passwordField($model, 'newPass', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'newPass'); ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo $form->labelEx($model, 'confPass', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->passwordField($model, 'confPass', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'confPass'); ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo $form->labelEx($model, 'email', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->textField($model, 'email', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'email'); ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo $form->labelEx($model, 'fullName', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->textField($model, 'fullName', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'fullName'); ?>
						</div>

					</div>
					<div class="form-group">
						<?php echo $form->labelEx($model, 'phone', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->textField($model, 'phone', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'phone'); ?>
						</div>

					</div>
					<div class="form-group">
						<?php echo $form->labelEx($model, 'address', array('class' => 'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php echo $form->textField($model, 'address', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'address'); ?>
						</div>

					</div>
				</div>
				<div class="form-actions right">
					<?php echo FHtml::button('submit', FHtml::BUTTON_EDIT, array(
						'id' => 'my_search',
						'class' => 'btn-primary',
					));
					if(!$model->isNewRecord)
					{
					echo FHtml::showLink('Back', array('class' => 'btn btn-primary', 'href' => Yii::app()->createUrl('account/view',array('id'=>$model->id))), 'fa fa-arrow-left');
					}
					?>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>