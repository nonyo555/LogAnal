<html>

<head>
    <title>KMITL Log Analytics - Map</title>
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChO4HEq1x-dXtFFcfCuOHdog9jl6HITdE"></script>
</head>

<script>
    function initialize() {
        var src = "https://github.com/nonyo555/LogAnal/releases/download/1.0.1/map.kml";
        var mapOptions = {
          center: new google.maps.LatLng(13.728345,100.778075), //Set your latitude, longitude
          zoom: 17,
          mapTypeId: google.maps.MapTypeId.SATELLITE,
          scrollwheel: false
        }

        var map = new google.maps.Map(document.getElementById('google-map'), mapOptions); // get the div by id
        
        var ctaLayer = new google.maps.KmlLayer(src);
        ctaLayer.setMap(map);
    }
</script>

<body onload="initialize()">
    <div class='head_main'>
        <a href="../phpvscode/index.php"><img src="../img/header.png"></a>
    </div>
    <div class='sub_main'>
        <label class='sub'>Top-problem</label>
    </div>
    <div class='body_main'>
        <div id="google-map" class="google-map" style="width: 100%; height: 100%; border-radius: 10px;"></div>
        <div id="capture"></div>
    </div>
    <div class='tail_main'></div>
</body>

</html>