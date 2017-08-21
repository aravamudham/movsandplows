<?php
/* @var $this AccountController */
/* @var $model Account */

$this->breadcrumbs = array(
    $this->pageTitle,
);

$this->toolBarActions = array(
    'linkButton' => array(
        array(
            'label' => 'Update',
            'icon' => 'fa fa-pencil',
            'htmlOptions' => array('class' => 'btn  btn-success', 'href' => Yii::app()->createUrl('account/update', array('id' => $model->id)))
        ),
    ),
    'button' => array(),
    'dropdown' => array(),
);


?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i><?php echo $this->getPageTitle() ?>
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <?php $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'attributes' => array(
                        'id',
                        'username',
                        //'password',
                        'email',
                        'fullName',
                        'phone',
                        'address',
                        array(
                            'name' => 'role',
                            'type' => 'raw',
                            'value' => $model->getRoleLabel(),
                        ),
                        array(
                            'name' => 'status',
                            'type' => 'raw',
                            'value' => $model->getStatusLabel(),
                        ),
                    ),
                    'htmlOptions' => array('class' => 'table table-striped table-bordered table-hover dataTable'),
                )); ?>
            </div>
        </div>
    </div>
</div>
