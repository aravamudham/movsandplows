<?php
/* @var $this VehicleController */
/* @var $model Vehicle */
/* @var $form CActiveForm */


$this->breadcrumbs = array(
    'Vehicles' => array('index'),
    'Detail',
);
$this->toolBarActions = array(
    'linkButton' => array(),
    'button' => array(
        array(
            'label' => 'Request Document',
            'icon' => 'fa fa-chain',
            'htmlOptions' => array('class' => 'btn btn-primary', 'onclick' => "request(" . $model->userId . ");")
        ),
    ),
    'dropdown' => array(),
);
$activeUrl = Yii::app()->createUrl('vehicle/requestDocument');
Yii::app()->clientScript->registerScript('requestDocument', "
var request = function(userId) {
            $('#loading').show();
            $.ajax({
                url: \"$activeUrl\",
                dataType: 'json',
                type: 'POST',
                data: 'userId ='+ userId
            }).done(function (data) {
                if (data == 'NOT_LOGGED_IN') {
                    window.location.reload();
                    return;
                }
                if (!data.success) {
                    $('#loading').hide();
                    alert(data.message);
                } else {
                    $('#loading').hide();
					alert('Request has been sent!');
                }
            }).fail(function (jqXHR, textStatus) {
                $('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest') . "\");
            });
        return false;
}
", CClientScript::POS_BEGIN);
?>
<div class="row">
    <div class="col-md-3">
        <h6 class="block-center"></h6>
                <?php
                $images = VehicleImg::model()->findAll('carId=' . $model->id);
                if (count($images) != 0)
                    foreach ($images as $item) {
                        $link = Yii::app()->request->baseUrl . '/upload/car/';
                        ?>

                            <a href="<?php echo $link . $item->image; ?>"
                                   class="fancybox-button" data-rel="fancybox-button">
                                <img src="<?php echo $link . $item->image; ?>" class="img-responsive"
                                     alt="<?php echo $model->carPlate ?>">
                            </a>
                        <p></p>
                    <?php
                    }
                ?>
    </div>
    <div class="col-md-9">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-car"></i> Update Vehicle - ID: #<?php echo $model->id; ?>
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
                        <?php echo $form->labelEx($model, 'userId', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'userId', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'userId'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'carPlate', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'carPlate', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'carPlate'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'brand', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'brand', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'brand'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'model', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'model', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'model'); ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'year', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'year', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'year'); ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'status', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->textField($model, 'status', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'status'); ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'document', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->fileField($model, 'document'); ?>
                            <a href="<?php echo $this->createUrl('/vehicle/download', array('vehicle_id' => $model->id))?>"><?php echo $model->document; ?></a>
                            <?php echo $form->error($model, 'document'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'images', array('class' => 'col-md-3 control-label')); ?>
                        <div class="col-md-9">
                            <?php echo $form->fileField($model, 'images', array( 'name' => 'images[]','multiple'=>TRUE)); ?>
                            <?php echo $form->error($model, 'images'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <?php echo FHtml::showLink('Back', array('class' => 'btn btn-primary', 'href' => Yii::app()->request->baseUrl . '/vehicle/index'), 'fa fa-arrow-left') ?>
                    <?php echo FHtml::button('submit', FHtml::BUTTON_EDIT, array(
                        'id' => 'my_search',
                        'class' => 'btn-primary',
                    ));
                    ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
