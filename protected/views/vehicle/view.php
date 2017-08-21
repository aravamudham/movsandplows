<?php
/* @var $this VehicleController */
/* @var $model Vehicle */

$this->breadcrumbs = array(
    'Vehicles' => array('index'),
    $model->id,
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
            //$('#loading').show();
            alert('Request sent!');
            $.ajax({
                url: \"$activeUrl\",
                type: 'POST',
                data: { userId : userId },
            }).done(function (data) {
                    //$('#loading').hide();
                    if(data.message.length != 0)
                    alert(data.message);
            }).fail(function (jqXHR, textStatus) {
                //$('#loading').hide();
                alert(\"" . Yii::t('common', 'errorMessage.invalidRequest') . "\");
            });
        return false;
}
", CClientScript::POS_BEGIN);

?>
<div class="row">
        <div class="col-md-3">
            <h6 class="block-center"></h6>

            <div>
                <div class="product-item">
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
            </div>
        </div>
        <div class="col-md-9">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-car"></i>Vehicle Detail - ID: #<?php echo $model->id; ?>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data' => $model,
                        'attributes' => array(
                            'id',
//                            'userId',
//                            'driver.fullName',
                            array(               // related city displayed as a link
                                'label'=>Yii::t('vehicle', 'title.driver'),
                                'type'=>'raw',
                                'value'=>function ($data) {
                                    return $data->user->fullName;
                                },
                            ),
                            'carPlate',
                            'brand',
                            'model',
                            'year',
                            'status',
//                            array(
//                                'label'=>Yii::t('vehicle', 'title.status'),
//                                'type'=>'raw',
//                                'value'=>function ($data) {
//                                    return $data->getStatusLabel();
//                                },
//                            ),
                            array(
                                'label'=>Yii::t('vehicle', 'title.document'),
                                'type'=>'raw',
                                'value'=>function ($data) {
                                    return  '<a href="'.$this->createUrl('/vehicle/download', array('vehicle_id' => $data->id)).'">'. $data->document .'</a>';
                                },
                            ),
                            'dateCreated',
                        ),
                        'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                    )); ?>
                </div>
            </div>
        </div>
</div>