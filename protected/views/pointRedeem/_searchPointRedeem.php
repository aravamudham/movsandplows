<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    userName =  $( '#userName' ).val();
    if (userName.length == 0){
        $('#PointRedeem_userId' ).removeAttr('value');
    }

	$('#pointRedeem-grid').yiiGridView('update', {
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
        'id' => 'pointRedeem-grid-search',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'htmlOptions'=>array(
            'class'=>'form'
        )
    )); ?>
    <div class="form-body">
    <div class="row">
        <div class="col-sm-2">
            <?php
            echo $form->hiddenField($pointRedeem, 'userId');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'userName',
                'sourceUrl' => array('auto/Complete'),
                'options' => array(
                    'showAnim' => 'fold',
                    'select' => 'js:function(event, ui){
                    jQuery("#PointRedeem_userId").val(ui.item["id"]);
                    }'
                ),
                'htmlOptions' => array(
                    'type' => 'text',
                    'class' => 'input-sm form-control',
                    'placeholder' =>Yii::t('common', 'title.user'),
                ),
            ));
            ?>
        </div>

        <div class="col-sm-2">
            <?php echo $form->textField($pointRedeem, 'start_date', array(
                'class' => 'form-control form-control-inline input-sm date-picker',
                'data-date-format' => 'yyyy-mm-dd',
                'placeholder'=>Yii::t('common', 'title.from.date'),
                'size' => '16')); ?>
        </div>
        <div class="col-sm-2">
            <?php echo $form->textField($pointRedeem, 'end_date', array(
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
