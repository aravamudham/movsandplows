<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    senderName =  $( '#senderName' ).val();
    if (senderName.length == 0){
        $('#PointTransfer_senderId' ).removeAttr('value');
    }
    receiverName =  $( '#receiverName' ).val();
    if (receiverName.length == 0){
        $('#PointTransfer_receiverId' ).removeAttr('value');
    }

	$('#pointTransfer-grid').yiiGridView('update', {
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
        'id' => 'pointTransfer-grid-search',
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
            echo $form->hiddenField($pointTransfer, 'senderId');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'senderName',
                'sourceUrl' => array('auto/Complete'),
                'options' => array(
                    'showAnim' => 'fold',
                    'select' => 'js:function(event, ui){
                    jQuery("#PointTransfer_senderId").val(ui.item["id"]);
                    }'
                ),
                'htmlOptions' => array(
                    'type' => 'text',
                    'class' => 'input-sm form-control',
                    'placeholder' =>Yii::t('transaction', 'title.sender'),
                ),
            ));
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            echo $form->hiddenField($pointTransfer, 'receiverId');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'receiverName',
                'sourceUrl' => array('auto/Complete'),
                'options' => array(
                    'showAnim' => 'fold',
                    'select' => 'js:function(event, ui){
                    jQuery("#PointTransfer_receiverId").val(ui.item["id"]);
                    }'
                ),
                'htmlOptions' => array(
                    'type' => 'text',
                    'class' => 'input-sm form-control',
                    'placeholder' =>Yii::t('transaction', 'title.receiver'),
                ),
            ));
            ?>
        </div>

        <div class="col-sm-2">
            <?php echo $form->textField($pointTransfer, 'start_date', array(
                'class' => 'form-control form-control-inline input-sm date-picker',
                'data-date-format' => 'yyyy-mm-dd',
                'placeholder'=>Yii::t('common', 'title.from.date'),
                'size' => '16')); ?>
        </div>
        <div class="col-sm-2">
            <?php echo $form->textField($pointTransfer, 'end_date', array(
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
