<?php

Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){



    userName =  $( '#userName' ).val();

    if (userName.length == 0){

     $('#User_id' ).removeAttr('value');

    }



	$('#user-grid').yiiGridView('update', {

		data: $(this).serialize()

	});

	return false;

});

");

//data =  $(this).serialize();

//alert(decodeURIComponent(data));return;

$datePickerScript = <<<SCRIPT

 if (jQuery().datepicker) {

            $('.date-picker').datepicker({

                rtl: Metronic.isRTL(),

                orientation: "left",

                autoclose: true

            });

            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal

        }

 $('.date-picker').datepicker({language:"en", format: "yyyy-mm-dd"});



SCRIPT;

Yii::app()->getClientScript()->registerScript('datePickerScript', $datePickerScript);





if (isset($_GET['User']['driver_check']) && $_GET['User']['driver_check'] == Globals::NEW_DRIVER_REGISTER_CUSTOM_INDEX){

    $user->driver_check = $_GET['User']['driver_check'];

}



?>

<div class="search-form">

    <?php $form = $this->beginWidget('CActiveForm', array(

        'id' => 'user-grid-search',

        'action' => Yii::app()->createUrl($this->route),

        'method' => 'get',

        'htmlOptions' => array(

            'class' => 'form'

        )

    )); ?>

    <div class="form-body">

        <div class="row">

            <div class="col-sm-2">

                <?php echo $form->dropDownList($user, 'isActive', $status, array('style' => '', 'class' => 'input-sm form-control', 'prompt' => Yii::t('common', 'title.allStatus'))); ?>

            </div>

            <div class="col-sm-2">

                <?php echo $form->dropDownList($user, 'driver_check', $types, array('style' => '', 'class' => 'input-sm form-control', 'prompt' => Yii::t('common', 'title.allUsers'))); ?>

            </div>

            <div class="col-sm-2">

                <?php

                /*echo $form->hiddenField($user, 'fullName');

                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(

                    'name' => 'userName',

                    'sourceUrl' => array('auto/Complete'),

                    'value' => ($user->fullName) ? $user->fullName : $user->fullName,

                    'options' => array(

                        'showAnim' => 'fold',

                        'select' => 'js:function(event, ui){

                    jQuery("#User_fullName").val(ui.item["value"]);

                    }'

                    ),

                    'htmlOptions' => array(

                        'type' => 'text',

                        'class' => 'input-sm form-control',

                        'placeholder' => Yii::t('common', 'title.user.name'),

                    ),

                ));*/
                echo $form->textField($user, 'fullName', array(

                    'class' => 'form-control form-control-inline input-sm ',


                    'placeholder' => 'Full Name',

                    'size' => '16'));

                ?>

            </div>

            <div class="col-sm-2">

                <?php echo $form->textField($user, 'start_date', array(

                    'class' => 'form-control form-control-inline input-sm date-picker',

                    'data-date-format' => 'yyyy-mm-dd',

                    'placeholder' => Yii::t('common', 'title.from.date'),

                    'size' => '16')); ?>

            </div>

            <div class="col-sm-2">

                <?php echo $form->textField($user, 'end_date', array(

                    'class' => 'form-control form-control-inline input-sm date-picker',

                    'data-date-format' => 'yyyy-mm-dd',

                    'placeholder' => Yii::t('common', 'title.to.date'),

                    'size' => '16')); ?>

            </div>

            <div class="col-sm-1">

                <?php echo FHtml::button('submit', FHtml::BUTTON_SEARCH, array(

                    'id' => 'my_search',

                    'class' => 'btn-sm btn-primary',

                ));

                ?>

            </div>

        </div>

    </div>

    <?php $this->endWidget(); ?>

</div>

