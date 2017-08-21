<div class="content">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'result-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'class' => 'login-form',
        )
    )); ?>
    <?php if(!isset($error_code))
    echo '<h3 class="form-title">'. $message .'</h3>';

    else
    {
        echo '<h3 class="form-title" style="color: red">'. $message .'</h3>';
        echo '<br/>';
        if($error_code != 0 )
        echo '<a class="btn btn-primary" href="'.Yii::app()->request->baseUrl . '/driverRegister'.'"><i class="fa fa-arrow-left"></i> Back</a>';
    }

    ?>
    <?php $this->endWidget(); ?>
</div>
