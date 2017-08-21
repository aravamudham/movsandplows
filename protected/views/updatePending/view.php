<?php
/* @var $this UpdatePendingController */
/* @var $model UpdatePending */

$this->breadcrumbs = array(
    'Pending Driver Updates' => array('index'),
    $model->id,
);

$this->toolBarActions = array(
    'linkButton' => array(
        array(
            'label' => 'Back',
            'icon' => 'fa fa-arrow-left',
            'htmlOptions' => array('class' => 'btn btn-primary', 'href' => Yii::app()->createUrl('updatePending/index'))
        )
    ),
    'button' => array(),
    'dropdown' => array(),
);
?>

<div class="row">
    <?php
    if ($model->user) {
        ?>
        <div class="col-md-12">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="glyphicon glyphicon-pencil"></i>Pending Update Detail - ID: #<?php echo $model->id; ?>
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
                                'label' => Yii::t('trip', 'title.driver'),
                                'type' => 'raw',
                                'value' => function ($data) {
                                    return $data->user->fullName;
                                },
                            ),
                            'carPlate',
                            'brand',
                            'model',
                            'year',
                            'status',
                            array(
                                'name' => 'document',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    if (isset($data->document))
                                        return '<a href="' . $this->createUrl('/updatePending/download', array('item_id' => $data->id)) . '">' . $data->document . '</a>';
                                    else
                                        return '';
                                },
                            ),
                            array(
                                'name' => 'image',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    if (isset($data->image))
                                        return FHtml::showImage(UPDATE_PENDING_DIR . DS . $data->image, 100, 100, FALSE);
                                    else
                                        return '';
                                },
                            ),
                            array(
                                'name' => 'image2',
                                'type' => 'raw',
                                'value' => function ($data) {
                                    if (isset($data->image2))
                                        return FHtml::showImage(UPDATE_PENDING_DIR . DS . $data->image2, 100, 100, FALSE);
                                    else
                                        return '';
                                },
                            ),
                            'phone',
                            'dateCreated'

                        ),
                        'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                    )); ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="col-md-12">
            <div class="alert alert-danger">
                <strong>Error!</strong> User is not exist.
            </div>
        </div>
        <?php
    }
    ?>
</div>
