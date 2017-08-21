<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    driverName =  $( '#driverName' ).val();
    if (driverName.length == 0){
        $('#Trip_driverId' ).removeAttr('value');
    }
    passengerName =  $( '#passengerName' ).val();
    if (passengerName.length == 0){
        $('#Trip_passengerId' ).removeAttr('value');
    }
	$('#trip-grid').yiiGridView('update', {
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
?>
<div class="search-form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'trip-grid-search',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'htmlOptions'=>array(
            'class'=>'form'
        )
    )); ?>
    <div class="form-body">
    <div class="row">
        <div class="col-sm-2">
            <?php echo $form->dropDownList($trip, 'status', $status, array('style' => '', 'class' => 'input-sm form-control', 'prompt' => Yii::t('common', 'title.allStatus'))); ?>
        </div>

        <div class="col-sm-2">
            <?php
            echo $form->hiddenField($trip, 'passengerId');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'passengerName',
                'sourceUrl' => array('auto/Complete'),
                'options' => array(
                    'showAnim' => 'fold',
                    'select' => 'js:function(event, ui){
                    jQuery("#Trip_passengerId").val(ui.item["id"]);
                    }'
                ),
                'htmlOptions' => array(
                    'type' => 'text',
                    'class' => 'input-sm form-control',
                    'placeholder' =>Yii::t('common', 'title.passenger.name'),
                ),
            ));
            ?>
        </div>

        <div class="col-sm-2">
            <?php
            echo $form->hiddenField($trip, 'driverId');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'driverName',
                'sourceUrl' => array('auto/Complete?isDriver='.Globals::STATUS_ACTIVE),
                'options' => array(
                    'showAnim' => 'fold',
                    'select' => 'js:function(event, ui){
                    jQuery("#Trip_driverId").val(ui.item["id"]);
                    }'
                ),
                'htmlOptions' => array(
                    'type' => 'text',
                    'class' => 'input-sm form-control',
                    'placeholder' =>Yii::t('common', 'title.driver.name'),
                ),
            ));
            ?>
        </div>

        <div class="col-sm-2">
            <?php echo $form->textField($trip, 'start_date', array(
                'class' => 'form-control form-control-inline input-sm date-picker',
                'data-date-format' => 'yyyy-mm-dd',
                'placeholder'=>Yii::t('common', 'title.from.date'),
                'size' => '16')); ?>
        </div>
        <div class="col-sm-2">
            <?php echo $form->textField($trip, 'end_date', array(
                'class' => 'form-control form-control-inline input-sm date-picker',
                'data-date-format' => 'yyyy-mm-dd',
                'placeholder'=>Yii::t('common', 'title.to.date'),
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
