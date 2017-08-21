<div class="content">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'class' => 'login-form',
        )
    )); ?>
    <h3 class="form-title">Sign In</h3>

    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Username</label>

        <div class="input-icon">
            <i class="fa fa-user"></i>
            <?php echo $form->textField($model, 'username', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Username')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>

        <div class="input-icon">
            <i class="fa fa-lock"></i>
            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => 'Password')); ?>
        </div>
    </div>
    <div class="form-actions">
        <?php echo CHtml::submitButton('Login', array('class' => 'btn btn-success uppercase')); ?>
        <label class="rememberme check">
            <?php echo $form->checkBox($model, 'rememberMe'); ?> Remember
        </label>
    </div>
    <?php $this->endWidget(); ?>
</div>
