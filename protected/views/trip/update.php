<?php
/* @var $this TripController */
/* @var $model Trip */

$this->breadcrumbs = array(
    'Trips' => array('index'),
    $model->id,
);

$this->toolBarActions = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
?>

<div class="row">
    <div class="col-md-4">
        <div class="portlet box blue-hoki form">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-suitcase"></i>Update Trip - ID: #<?php echo $model->id; ?>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'state-form',
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            'role' => 'form',
                            'class' => 'form-horizontal'
                        )
                    )); ?>
                    <?php echo $form->errorSummary($model); ?>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'status', array('class' => 'col-md-5 control-label')); ?>
                        <div class="col-md-7">
                            <?php echo $form->dropDownList($model, 'status', Globals::getListStatusOfTrip(), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'status'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'actualFare', array('class' => 'col-md-5 control-label')); ?>
                        <div class="col-md-7">
                            <?php echo $form->textField($model, 'actualFare', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'actualFare'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'need_help', array('class' => 'col-md-5 control-label')); ?>
                        <div class="col-md-7">
                            <?php echo $form->dropDownList($model, 'need_help', array('1' => 'Yes', '0' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'need_help'); ?>
                        </div>
                    </div>
                    <hr>
                    <div style="text-align: center;"><h3>Update Option</h3></div>
                    <br>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="radio" name="option_way" value="1"/>
                            Option 1: Deduct fare from passenger’s
                            balance and Add fare to driver’s balance <br/><br/>
                            <input type="radio" name="option_way" value="2"/>
                            Option 2: Deduct commission from driver’s
                            balance <br/><br/>
                            <input type="radio" name="option_way" value="3" checked/>
                            Option 3: Do nothing to driver and
                            passenger’s balance <br/><br/>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <?php
                    echo FHtml::button('submit', FHtml::BUTTON_SAVE, array(
                        'id' => 'my_search',
                        'class' => 'btn-primary',
                    ));
                    ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <div>
            <div class="product-item">
                <?php $passenger = User::model()->findByPk($model->passengerId) ?>
                <a href="<?php echo $passenger->image; ?>"
                   class="fancybox-button" data-rel="fancybox-button">
                    <img src="<?php echo $passenger->image; ?>" class="img-responsive"
                         alt="<?php echo $passenger->image ?>">
                </a>

                <?php if (isset($model->driverId)) {
                    $driver = User::model()->findByPk($model->driverId);
                    ?>
                    <p></p>
                    <a href="<?php echo $driver->image; ?>"
                       class="fancybox-button" data-rel="fancybox-button">
                        <img src="<?php echo $driver->image; ?>" class="img-responsive"
                             alt="<?php echo $driver->image ?>">
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-suitcase"></i>Trip Detail - ID: #<?php echo $model->id; ?>
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
                        array(
                            'label' => Yii::t('trip', 'title.passenger'),
                            'type' => 'raw',
                            'value' => function ($data) {
                                return $data->passenger->fullName;
                            },
                        ),
                        array(
                            'label' => Yii::t('trip', 'title.driver'),
                            'type' => 'raw',
                            'value' => function ($data) {
                                return $data->driver->fullName;
                            },
                        ),
                        array(
                            'label' => 'Car Type',
                            'type' => 'raw',
                            'value' => function ($data) {
                                return Trip::model()->getLabelCarTypeFromKey($data->link);
                            },
                        ),
                        'startTime',
                        'endTime',
                        'startLat',
                        'startLong',
                        'startLocation',
                        'endLat',
                        'endLong',
                        'endLocation',
                        'distance',
                        array(
                            'name' => 'status',
                            'type' => 'raw',
                            'value' => $model->getStatusLabel(),
                        ),
                        'estimateFare',
                        'actualFare',
                        'driverRate',
                        'passengerRate',
                        'dateCreated',
                    ),
                    'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                )); ?>
            </div>
        </div>
    </div>
</div>
