<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 600px !important;
        width: 1000px !important;
    }
</style>
<div id="map"></div>
<script>
    function initMap() {
        var myLatLng = {lat: -37.8192657, lng: 145.1220364};
        //var myLatLng = {lat: 21.042849887162447, lng: 105.80238335765898};

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 18,
            center: myLatLng
        });

        <?php
        $list = LogLatLong::model()->findAll('user_id=20 AND id > 920 AND id < 1148');
        /** @var LogLatLong $item */
        foreach ($list as $item) {
        ?>
        var marker = new google.maps.Marker({
            position: {lat: <?php echo $item->lat ?>, lng: <?php echo $item->long ?>},
            map: map,
            label: '<?php echo $item->id ?>'
        });
        <?php
        }
        ?>
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCSavIkKAnJJ8qxcKLdGUa2g0Rk16pCUwc&callback=initMap">
</script>