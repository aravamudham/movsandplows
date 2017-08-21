<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
driverName =  $( '#driverName' ).val();
    if (driverName.length == 0){

        //$( '#Vehicle_userId' ).val('');

        //var elem = document.getElementById('Vehicle_userId');
        //elem.value = '';

        $('#Vehicle_userId' ).removeAttr('value');
    }
	$('#vehicle-grid').yiiGridView('update', {
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
        'id' => 'vehicle-grid-search',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'htmlOptions'=>array(
            'class'=>'form'
        )
    )); ?>
    <div class="form-body">
    <div class="row">
        <div class="col-sm-2">
            <?php echo $form->dropDownList($vehicle, 'status', $status, array('style' => '', 'class' => 'input-sm form-control', 'prompt' => Yii::t('common', 'title.allStatus'))); ?>
        </div>
        <div style="width: 20%; float: left;">
            <?php echo $form->dropDownList($vehicle, 'issetDocument', $issetDocument, array('style' => '', 'class' => 'input-sm form-control', 'prompt' => Yii::t('common', 'title.all'))); ?>
        </div>
        <div class="col-sm-2">
            <?php
            echo $form->hiddenField($vehicle, 'userId');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'driverName',
                'sourceUrl' => array('auto/Complete?isDriver='.Globals::STATUS_ACTIVE),
                'value' =>  ($vehicle->fullName) ? $vehicle->fullName: $vehicle->fullName,
                'options' => array(
                    'showAnim' => 'fold',
                    'select' => 'js:function(event, ui){
                    jQuery("#Vehicle_userId").val(ui.item["id"]);
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
            <?php echo $form->textField($vehicle, 'start_date', array(
                'class' => 'form-control form-control-inline input-sm date-picker',
                'data-date-format' => 'yyyy-mm-dd',
                'placeholder'=>Yii::t('common', 'title.from.date'),
                'size' => '16')); ?>
        </div>
        <div class="col-sm-2">
            <?php echo $form->textField($vehicle, 'end_date', array(
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
