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
//echo $model->id;

$reload_map = Settings::model()->getSettingValueByKey(Globals::AUTO_RELOAD_DATA_MAP);
$time_reload_map = Settings::model()->getSettingValueByKey(Globals::TIME_AUTO_RELOAD_DATA_MAP);
?>
<div class="row">
    <div class="col-md-12">
        <style>

            /* Always set the map height explicitly to define the size of the div
             * element that contains the map. */
            #map {
                width: 100%;
                min-height: 300px;
                height: 100%;
            }
            /* Optional: Makes the sample page fill the window. */
        </style>

        <div id="map"></div>

        <script>
            /*$(function () {
             $('[data-toggle="tooltip"]').tooltip()
             })*/
            var iconBase = '<?php echo Yii::app()->request->baseUrl."/images/icon" ?>/';
            var customLabelIcon = {
                a: {
                    label: 'A',
                    icon: iconBase + 'drivers-online-a.png'
                },
                b: {
                    label: 'B',
                    icon: iconBase + 'drivers-online-b.png'
                },
                u: {
                    label: 'B',
                    icon: iconBase + 'drivers-online-busy.png'
                },
            };

            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: new google.maps.LatLng(<?php echo $model->startLat ?>, <?php echo $model->endLong ?>),
                     zoom: 11
                    /*zoom: 2,
                    center: new google.maps.LatLng(2.8,-187.3)*/
                });
                var infoWindow = new google.maps.InfoWindow;

                // Change this depending on the name of your PHP or XML file
                downloadUrl('<?php echo Yii::app()->request->baseUrl."/api/detailTripXml?id=".$model->id ?>', function(data) {
                    var xml = data.responseXML;
                    var markers = xml.documentElement.getElementsByTagName('marker');
                    Array.prototype.forEach.call(markers, function(markerElem) {
                        var name = markerElem.getAttribute('name');
                        var tripId = markerElem.getAttribute('tripId');
                        var carPlate = markerElem.getAttribute('carPlate');
                        var brand = markerElem.getAttribute('brand');
                        var model = markerElem.getAttribute('model');
                        var year = markerElem.getAttribute('year');
                        var type = markerElem.getAttribute('type');
                        var infowincontent = '';
                        if (type == 'u'){
                            var point = new google.maps.LatLng(
                                parseFloat(markerElem.getAttribute('lat')),
                                parseFloat(markerElem.getAttribute('lng')));
                        }else{

                            infowincontent = "<div>"
                                +"Driver: <strong>"+name+"</strong><br/>"
                                +"Car Plate: <strong>"+carPlate+"</strong><br/>"
                                +"Brand: <strong>"+brand+"</strong><br/>"
                                +"Model: <strong>"+model+"</strong><br/>"
                                +"Year: <strong>"+year+"</strong>"
                                +"</div>";
                            var point = new google.maps.LatLng(
                                parseFloat(markerElem.getAttribute('latTrip')),
                                parseFloat(markerElem.getAttribute('lngTrip')));
                        }


                        var text = document.createElement('text');
                        //text.textContent = address
                        //infowincontent.appendChild(text);
                        var iconCustom = customLabelIcon[type] || {};
                        var marker = new google.maps.Marker({
                            map: map,
                            position: point,
                            label: '',
                            icon: iconCustom.icon,
                        });
                        marker.addListener('click', function() {
                            //infoWindow.setContent(infowincontent);
                            //infoWindow.open(map, marker);
                        });
                    });
                });
            }



            function downloadUrl(url, callback) {
                var request = window.ActiveXObject ?
                    new ActiveXObject('Microsoft.XMLHTTP') :
                    new XMLHttpRequest;

                request.onreadystatechange = function() {
                    if (request.readyState == 4) {
                        request.onreadystatechange = doNothing;
                        callback(request, request.status);
                    }
                };

                request.open('GET', url, true);
                request.send(null);
            }
            initMap();
            function doNothing() {}
        </script>
    </div>
</div>
<hr>
<div class="row hide">
    <div class="col-md-12">
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog"></i> Settings Map
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
                <?php echo $form->errorSummary($model2); ?>
                <div class="form-body">

                    <div class="form-group">
                        <?php echo $form->labelEx($model2, 'reload_map', array('class' => 'col-md-5 control-label')); ?>
                        <div class="col-md-5">
                            <?php echo $form->dropDownList($model2, 'reload_map', array('1' => 'Yes', '0' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model2, 'reload_map'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model2, 'time_reload_map', array('class' => 'col-md-5 control-label')); ?>
                        <div class="col-md-5">
                            <?php echo $form->textField($model2, 'time_reload_map', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model2, 'time_reload_map'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <div class="col-md-offset-5 col-md-1">
                        <?php echo FHtml::button('submit', FHtml::BUTTON_SAVE, array(
                            'id' => 'edit-settings-button',
                            'class' => 'btn-primary ',
                        ));
                        ?></div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3">
        <h6 class="block-center"></h6>
        <div>
            <div class="product-item">
                <?php $passenger = User::model()->findByPk($model->passengerId) ?>
                <?php
                $imgPassenger = "";
                if (stripos($passenger->image, 'user.') != false) {
                    $imgPassenger = Yii::app()->getBaseUrl(true) . '/upload/user/' . $passenger->image;
                } else {
                    $imgPassenger = $passenger->image;
                }
                ?>
                <a href="<?php echo $imgPassenger; ?>"
                   class="fancybox-button" data-rel="fancybox-button">
                    <img src="<?php echo $imgPassenger; ?>" class="img-responsive"
                         alt="<?php echo $imgPassenger; ?>">
                </a>

                <?php if (isset($model->driverId)) {
                    $driver = User::model()->findByPk($model->driverId);
                    $imgDriver = "";
                    if (stripos($driver->image, 'user.') != false) {
                        $imgDriver = Yii::app()->getBaseUrl(true) . '/upload/user/' . $driver->image;
                    } else {
                        $imgDriver = $driver->image;
                    }
                    ?>
                    <p></p>
                    <a href="<?php echo $imgDriver; ?>"
                       class="fancybox-button" data-rel="fancybox-button">
                        <img src="<?php echo $imgDriver; ?>" class="img-responsive"
                             alt="<?php echo $imgDriver ?>">
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
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
                        //'link',
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
