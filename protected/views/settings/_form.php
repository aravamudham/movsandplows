<?php
/* @var $this SettingsController */
/* @var $model SettingsForm */
/* @var $form CActiveForm */
$this->breadcrumbs = array(
    'Settings',
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
                    <i class="fa fa-cog"></i> Settings
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'vehicle-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'role' => 'form',
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                    )
                )); ?>
                <?php echo $form->errorSummary($model); ?>
                <div class="form-body">

                    <div class="form-group">
                        <?php
                        /*$this->widget('application.extensions.juiclockpick.EClockpick', array(
                         'model'            => $model,
                         'attribute'        =>'end',
                         'options'          =>array(
                             'starthour'    => 8,
                             'endhour'      => 20,
                             'showminutes'  => TRUE,
                            'minutedivisions'   => 12,
                            'military'      => TRUE,
                         ),
                         'htmlOptions'      => array('size'=>5,'maxlength'=>5)
                        )); */
                        ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'admin_phone_number', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'admin_phone_number', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'admin_phone_number'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'sign_up_start_points', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'sign_up_start_points', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'sign_up_start_points'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'admin_email', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'admin_email', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'admin_email'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'api_key', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'api_key', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'api_key'); ?>
                        </div>
                    </div>
                    <div class="form-group hide">
                        <?php echo $form->labelEx($model, 'distance_search', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'distance_search', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'distance_search'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'max_request', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'max_request', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'max_request'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'max_driver', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'max_driver', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'max_driver'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'pem', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->fileField($model, 'pem'); ?>
                            <p class="help-block"><?php echo $model->pem; ?></p>
                            <?php echo $form->error($model, 'pem'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'sf1', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'sf1', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'sf1'); ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'sf2', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'sf2', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'sf2'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'sf3', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'sf3', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'sf3'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'ppm1', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'ppm1', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ppm1'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'ppm2', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'ppm2', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ppm2'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'ppm3', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'ppm3', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ppm3'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'ppk1', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'ppk1', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ppk1'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'ppk2', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'ppk2', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ppk2'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'ppk3', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'ppk3', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ppk3'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'driver_share_bonus', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'driver_share_bonus', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'driver_share_bonus'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'passenger_share_bonus', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'passenger_share_bonus', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'passenger_share_bonus'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'app_post_per_page', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'app_post_per_page', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'app_post_per_page'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'min_redeem_amount', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'min_redeem_amount', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'min_redeem_amount'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'min_transfer_amount', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'min_transfer_amount', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'min_transfer_amount'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'cancellation_fee', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'cancellation_fee', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'cancellation_fee'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'driver_earn', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'driver_earn', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'driver_earn'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'min_balance_place_request', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'min_balance_place_request', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'min_balance_place_request'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'reload_map', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->dropDownList($model, 'reload_map',
                                array(
                                    '0' => 'No reload map',
                                    '5' => 'Reload map after 5 seconds',
                                    '10' => 'Reload map after 10 seconds',
                                    '15' => 'Reload map after 15 seconds'),
                                array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'reload_map'); ?>
                        </div>
                    </div>
                    <div class="form-group hide">
                        <?php echo $form->labelEx($model, 'time_reload_map', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'time_reload_map', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'time_reload_map'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'time_to_send_request_again', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'time_to_send_request_again', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'time_to_send_request_again'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'max_time_send_request', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'max_time_send_request', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'max_time_send_request'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'estimate_fare_speed', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'estimate_fare_speed', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'estimate_fare_speed'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'auto_approve_for_register_driver', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->dropDownList($model, 'auto_approve_for_register_driver', array('1' => 'Yes', '0' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'auto_approve_for_register_driver'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                <!-- 
                    <?php echo FHtml::button('submit', FHtml::BUTTON_SAVE, array(
                        'id' => 'edit-settings-button',
                        'class' => 'btn-primary',
                    ));
                    ?>
                    -->
                                       <button type="button" onclick="thisIsDemo()" id="edit-settings-button" class="btn-primary btn btn-save">  <i class="fa fa-save"></i>Save</button>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    function thisIsDemo() {
        alert("Demo version doesn't allow to save the change");
    }
</script>
