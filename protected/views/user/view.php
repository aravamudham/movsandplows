<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->id,
);
if ($driverData != NULL) {
    $vehicle = Vehicle::model()->find('userId = :userId', array('userId' => $model->id));
}

$this->toolBarActions = array(
    'linkButton' => ($driverData != NULL) ?
        ($driverData->isActive == Globals::STATUS_ACTIVE) ?
            array(
                array(
                    'label' => 'Inactive Driver',
                    'icon' => 'fa fa-minus-circle',
                    'htmlOptions' => array('class' => 'btn  btn-warning', 'href' => Yii::app()->createUrl('user/inactiveDriver', array('id' => $driverData->id)))
                ),
                array(
                    'label' => 'View Vehicle Detail',
                    'icon' => 'fa fa-car',
                    'htmlOptions' => array('class' => 'btn btn-info', 'href' => Yii::app()->createUrl('vehicle/view', array('id' => $vehicle->id)))
                )
            ) : array(
            array(
                'label' => 'Active Driver',
                'icon' => 'fa fa-check',
                'htmlOptions' => array('class' => 'btn  btn-success', 'href' => Yii::app()->createUrl('user/activeDriver', array('id' => $driverData->id)))
            ),
            array(
                'label' => 'View Vehicle Detail',
                'icon' => 'fa fa-car',
                'htmlOptions' => array('class' => 'btn btn-info', 'href' => Yii::app()->createUrl('vehicle/view', array('id' => $vehicle->id)))
            )
        )
        : array(),
    'button' =>
        ($driverData != NULL) ?
            array(
                array(
                    'label' => 'Request Document',
                    'icon' => 'fa fa-chain',
                    'htmlOptions' => array('class' => 'btn btn-primary', 'onclick' => "request(" . $model->id . ");")
                ),
            )
            : array(),
    'dropdown' => array(),
);

$activeUrl = Yii::app()->createUrl('user/requestDocument');
Yii::app()->clientScript->registerScript('requestDocument', "
var request = function(userId) {
            $('#loading').show();
            $.ajax({
                url: \"$activeUrl\",
                dataType: 'json',
                type: 'POST',
                 data: { requestId : requestId },
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

        <div>
            <div class="product-item">
                <?php
                $img = "";
                if (stripos($model->image, 'user.') != false) {
                    $img = Yii::app()->getBaseUrl(true) . '/upload/user/' . $model->image;
                } else {
                    $img = $model->image;
                }
                ?>
                <a href="<?php echo $img; ?>"
                   class="fancybox-button" data-rel="fancybox-button">
                    <img src="<?php echo $img ?>" class="img-responsive"
                         alt="<?php echo $model->fullName ?>">
                </a>
                <p></p>
            </div>
        </div>
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-suitcase"></i>Passenger Detail
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <?php $this->widget('zii.widgets.CDetailView', array(
                    'data' => $passengerData,
                    'attributes' => array(
                        'rate',
                        'rateCount'
                    ),
                    'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                )); ?>
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
                        <?php echo $form->labelEx($model, 'isActive', array('class' => 'col-md-6 control-label')); ?>
                        <div class="col-md-6">
                            <?php echo $form->dropDownList($model, 'isActive', array('1' => 'Yes', '0' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'isActive'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <div class="col-md-offset-6 col-md-1">
                        <?php echo FHtml::button('submit', FHtml::BUTTON_SAVE, array(
                            'id' => 'edit-settings-button',
                            'class' => 'btn-primary ',
                        ));
                        ?></div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <?php if ($driverData != NULL) { ?>
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-car"></i>Driver Detail
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data' => $driverData,
                        'attributes' => array(
//                            'bankAccount',
                            //'status',
                            array(
                                'name' => 'status',
                                'type' => 'raw',
                                'value' => $driverData->getStatusLabel(),
                            ),
                            //'isOnline',
                            array(
                                'name' => 'isOnline',
                                'type' => 'raw',
                                'value' => $driverData->getOnlineStatusLabel(),
                            ),
                            'rate',
                            'rateCount',
//                            'document',
                            array(
                                'label' => Yii::t('vehicle', 'title.document'),
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return '<a href="' . $this->createUrl('/user/download', array('user_id' => $data->id)) . '">' . $data->document . '</a>';
                                },
                            ),
                            //'isActive',
                            array(
                                'name' => 'isActive',
                                'type' => 'raw',
                                'value' => $driverData->getActiveStatusLabel(),
                            ),
                        ),
                        'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                    )); ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-9">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-user"></i>User Detail - ID: #<?php echo $model->id; ?>
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
                        'fullName',
                        //'image',
                        'email',
                        //'password',
                        'description',
//                        'gender',
                        'phone',
//                        'dob',
                        'address',
                        'balance',
                        'lat',
                        'long',
//                        'cardNumber',
//                        'cvv',
//                        'exp',
//                        'isOnline',
//                        'isActive',
//                        'isDriver',
                        array(
                            'name' => 'isOnline',
                            'type' => 'raw',
                            'value' => $model->getOnlineStatusLabel(),
                        ),
                        array(
                            'name' => 'isActive',
                            'type' => 'raw',
                            'value' => $model->getActiveStatusLabel(),
                        ),
                        array(
                            'name' => 'isDriver',
                            'type' => 'raw',
                            'value' => $model->getDriverStatusLabel(),
                        ),

                        //'token',
                        'dateCreated',
                    ),
                    'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                )); ?>
            </div>
        </div>
        <!--        --><?php //if(count($vehicleData) != NULL) { ?>
        <?php if (isset($vehicle) != NULL) {
            ?>
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-car"></i>Vehicle Detail
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data' => $vehicle,
                        'attributes' => array(
                            'id',
                            array(
                                'label' => Yii::t('vehicle', 'title.driver'),
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return $data->user->fullName;
                                },
                            ),
                            array(
                                'label' => Yii::t('vehicle', 'title.image'),
                                'type' => 'raw',
                                'value' => function ($data) {
                                    $link = Yii::app()->request->baseUrl . '/upload/car/';
                                    $images = $data->images;
                                    if (count($images) == 2) {
                                        return '<div class="col-md-3" ><a href="' . $link . $images[0]->image . '"  class="fancybox-button" data-rel="fancybox-button">' . FHtml::showImage(CAR_DIR . DS . $images[0]->image, 100, 100, FALSE) . '</a></div>
                                            <div class ="col-md-3" ><a href="' . $link . $images[1]->image . '"  class="fancybox-button" data-rel="fancybox-button">' . FHtml::showImage(CAR_DIR . DS . $images[1]->image, 100, 100, FALSE) . '</a></div>';
                                    } elseif (count($images) == 1) {
                                        return '<div class="col-md-3" ><a href="' . $link . $images[0]->image . '"  class="fancybox-button" data-rel="fancybox-button">' . FHtml::showImage(CAR_DIR . DS . $images[0]->image, 100, 100, FALSE) . '</a></div>';
                                    } else
                                        return 'No Image';

                                },
                            ),
                            'carPlate',
                            'brand',
                            'model',
                            'year',
                            'status',
                            array(
                                'label' => Yii::t('vehicle', 'title.document'),
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return '<a href="' . $this->createUrl('/vehicle/download', array('vehicle_id' => $data->id)) . '">' . $data->document . '</a>';
                                },
                            ),
                            'dateCreated',
                        ),
                        'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                    )); ?>
                    <div>
                        <?php echo FHtml::showLink('Edit', array('class' => 'btn btn-success', 'href' => Yii::app()->createUrl('vehicle/update', array('id' => $vehicle->id))), 'fa fa-pencil') ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



