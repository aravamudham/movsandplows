<?php

$this->pageTitle = 'Track online drivers';

$this->breadcrumbs = array(

    'Track Online Drivers',

);

//echo 'oki';

$driversOnlineFree = UserDriver::model()->getUserDriversFreeOnline();

$driversOnlineBusy = UserDriver::model()->getUserDriversBusyOnline();

$driversOnlineNeedhelp = UserDriver::model()->getUserDriversNeedHelp();



$reload_map = Settings::model()->getSettingValueByKey(Globals::AUTO_RELOAD_DATA_MAP);

$time_reload_map = Settings::model()->getSettingValueByKey(Globals::TIME_AUTO_RELOAD_DATA_MAP);



?>

<div class="rơw">

    <div class="col-md-9">

        <div type="button" class="btn btn-default countOnline" style="

    color: #fff;

    background: #333;

" data-toggle="tooltip" data-placement="top" title="All online drivers">

            <?php

            $total = $driversOnlineFree + $driversOnlineBusy;

            echo $total > 1 ? ($total . ' drivers are online') : $total . ' driver is online';

            ?>

        </div>

        <div type="button" class="btn btn-success countFree" style="background-color: #5cb85c;" data-toggle="tooltip"

             data-placement="top" title="Free online drivers">

            <?php

            echo $driversOnlineFree > 1 ? ($driversOnlineFree . ' drivers are free') : $driversOnlineFree . ' driver is free';

            ?>

        </div>

        <div type="button" class="btn btn-warning countBusy" data-toggle="tooltip" data-placement="top"

             title="Busy online drivers">

            <?php

            echo $driversOnlineBusy > 1 ? ($driversOnlineBusy . ' drivers are busy') : $driversOnlineBusy . ' driver is busy';

            ?>

        </div>

        <div type="button" class="btn btn-danger countNeedHelp" data-toggle="tooltip" data-placement="top"

             title="Help online drivers"><?= $driversOnlineNeedhelp ?> driver(s) need help

        </div>

    </div>

    <div class="col-md-3">

        <div>

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

            <div>

                <?php echo $form->dropDownList($model, 'reload_map',

                    array(

                        '0' => 'No reload map',

                        '5' => 'Reload map after 5 seconds',

                        '10' => 'Reload map after 10 seconds',

                        '15' => 'Reload map after 15 seconds'),

                    array('class' => 'form-control', 'onchange' => 'onChangeData()')); ?>

            </div>

            <div class="hide">

                <?php echo FHtml::button('submit', FHtml::BUTTON_SAVE, array(

                    'id' => 'edit-settings-button',

                    'class' => 'btn-primary ',

                ));

                ?>

            </div>

            <?php $this->endWidget(); ?>

        </div>

    </div>

</div>

<div class="rơw">

    <br/>

    <hr/>

</div>

<style>



    /* Always set the map height explicitly to define the size of the div

     * element that contains the map. */

    #map {

        width: 100%;

        min-height: 500px;

        height: 100%;

    }



    /* Optional: Makes the sample page fill the window. */

</style>



<div id="map"></div>





<script>
    function resetCountOnline(free,busy,help){
        $('.countOnline').text((free+busy+help) + ' drivers are online');
        $('.countFree').text((free) + ' drivers are free');
        $('.countBusy').text((busy) + ' drivers is busy');
        $('.countNeedHelp').text((help) + ' driver(s) need help');
    }

    var iconBase = '<?php echo Yii::app()->request->baseUrl . "/images/icon" ?>/';

    var customLabelIcon = {

        free: {

            label: 'F',

            icon: iconBase + 'drivers-online-free.png'

        },

        busy: {

            label: 'B',

            icon: iconBase + 'drivers-online-busy.png'

        },

        help: {

            label: 'H',

            icon: iconBase + 'drivers-online-help.png'

        },

    };



    var map = new google.maps.Map(document.getElementById('map'), {

        zoom: 2,

        center: new google.maps.LatLng(2.8, -187.3)

    });

    var infoWindow = new google.maps.InfoWindow;



    var checkPoint = 0;

    var count = 0;

    var dataOld = [];

    var countStart = 0;

    var countFree = 0;

    var countBusy = 0;

    var countNeedHelp = 0;

    function initMap() {

        countStart = countStart + 1;

        // Change this depending on the name of your PHP or XML file

        downloadUrl('<?php echo Yii::app()->request->baseUrl . "/api/xml" ?>', function (data) {



            if (checkPoint == 0) {

                count = 0;

            }



            var xml = data.responseXML;

            var markers = xml.documentElement.getElementsByTagName('marker');

            if (checkPoint == 1 && dataOld.length > 0) {
                /*for (var i = 0; i < dataOld.length; i++) {

                    dataOld[i].setMap(null);

                    console.log('dataOld[i].setMap(null)' + i);
                    // console.log(dataOld[i]);

                }*/
                /*dataOld.forEach(function(data){
                    console.log(data);
                    data.setMap(null);
                });*/
                console.log('start remove marker');
                
                if ($('img[src="' + iconBase + 'drivers-online-free.png"]').length>0) {
                    $('img[src="' + iconBase + 'drivers-online-free.png"]').each(function(i, data){
                        $(data).parent().remove();
                    });
                };

                
                if ($('img[src="' + iconBase + 'drivers-online-busy.png"]').length>0) {
                    $('img[src="' + iconBase + 'drivers-online-busy.png"]').each(function(i, data){
                        $(data).parent().remove();
                    });
                    /*if ($( "a:contains('Detail')" ).length>0) {
                        $( "a:contains('Detail')" ).parent().parent().parent().parent().parent().remove();
                    };*/
                };

                
                if ($('img[src="' + iconBase + 'drivers-online-help.png"]').length>0) {
                    $('img[src="' + iconBase + 'drivers-online-help.png"]').each(function(i, data){
                        $(data).parent().remove();
                    });
                    /*if ($( "a:contains('Detail')" ).length>0) {
                        $( "a:contains('Detail')" ).parent().parent().parent().parent().parent().remove();
                    };*/
                };


                dataOld = [];

            }

            if (dataOld.length==0) {

                Array.prototype.forEach.call(markers, function (markerElem) {

                    var id = markerElem.getAttribute('id');
                    console.log('id : '+id);

                    var name = markerElem.getAttribute('name');

                    var tripId = markerElem.getAttribute('tripId');

                    var carPlate = markerElem.getAttribute('carPlate');

                    var brand = markerElem.getAttribute('brand');

                    var model = markerElem.getAttribute('model');

                    var year = markerElem.getAttribute('year');

                    var type = markerElem.getAttribute('type');

                    var point = new google.maps.LatLng(

                        parseFloat(markerElem.getAttribute('lat')),

                        parseFloat(markerElem.getAttribute('lng')));

                    var link = '';

                    switch(type){
                        case 'free':
                            countFree = countFree + 1;
                            break;
                        case 'busy':
                            countBusy = countBusy + 1;
                            break;
                        case 'help':
                            countNeedHelp = countNeedHelp + 1;
                            break;
                    }

                    if (tripId != '') {

                        link = "<br/>Trip ID: <strong>" + tripId + "</strong> (<a href='<?php echo Yii::app()->request->baseUrl . '/trip/'; ?>" + tripId + "' target='_blank'>Detail</a>)";

                    }

                    var infowincontent = "<div>"

                        + "Driver: <strong>" + name + "</strong><br/>"

                        + "Car Plate: <strong>" + carPlate + "</strong><br/>"

                        + "Brand: <strong>" + brand + "</strong><br/>"

                        + "Model: <strong>" + model + "</strong><br/>"

                        + "Year: <strong>" + year + "</strong>"

                        + link

                        + "</div>";



                    var text = document.createElement('text');

                    var iconCustom = customLabelIcon[type] || {};

                    var marker = new google.maps.Marker({

                        map: map,

                        position: point,

                        label: '',

                        icon: iconCustom.icon,

                    });

                    if (checkPoint == 0) {

                        dataOld.push(marker);
                    }





                    marker.addListener('click', function () {

                        infoWindow.setContent(infowincontent);

                        infoWindow.open(map, marker);

                    });



                    if (checkPoint == 1) {

                        count = count + 1;

                    }

                });
                
            };


            if (count == 0) {

                checkPoint = 1;

            } else {

                checkPoint = 0;

            }

            resetCountOnline(countFree,countBusy,countNeedHelp);
            countFree = 0;
            countBusy = 0;
            countNeedHelp = 0;
        });



        <?php if ($reload_map > 0){ ?>

        setTimeout(initMap, <?php echo ((int)$reload_map) * 1000 ?>);

        <?php } ?>

    }



    function downloadUrl(url, callback) {

        var request = window.ActiveXObject ?

            new ActiveXObject('Microsoft.XMLHTTP') :

            new XMLHttpRequest;



        request.onreadystatechange = function () {

            if (request.readyState == 4) {

                request.onreadystatechange = doNothing;

                callback(request, request.status);

            }

        };



        request.open('GET', url, true);

        request.send(null);

    }



    initMap();





    function doNothing() {



    }





    function onChangeData() {

        $('#edit-settings-button').click();

    }

</script>

<hr>