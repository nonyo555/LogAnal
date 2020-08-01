<html>
<script></script>
<head>
    <title>KMITL Log Analytics - Map</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChO4HEq1x-dXtFFcfCuOHdog9jl6HITdE"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
 <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>
    <script type = 'text/javascript' src= 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js'></script>
    
</head>
<body onload =''>
    <div class='head_main'>
        <a href="../phpvscode/index.php"><img src="../img/header.png"></a>
    </div>
        <div class = time  style="padding-top:30px; height:120px">
            <table align="center">
                <tr valign="top">
                    <td> <label class = 'gtext'>Time</label></td>
                    <td> <input name = 'time_start'   id ='time_start'  class = 'time_start'  type = 'date'/> </td>
                    <td> <label class = 'text'> : </label> </td> 
                    <td>
                        <div style="width:200px;padding-left:20px;padding-right:20px;height: 120px">
                            <select id= 'hour_sel' class="form-control" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                <option value = '00:00'>00:00</option>
                                <option value = '01:00'>01:00</option>
                                <option value = '02:00'>02:00</option>
                                <option value = '03:00'>03:00</option>
                                <option value = '04:00'>04:00</option>
                                <option value = '05:00'>05:00</option>
                                <option value = '06:00'>06:00</option>
                                <option value = '07:00'>07:00</option>
                                <option value = '08:00'>08:00</option>
                                <option value = '09:00'>09:00</option>
                                <option value = '10:00'>10:00</option>
                                <option value = '11:00'>11:00</option>
                                <option value = '12:00'>12:00</option>
                                <option value = '13:00'>13:00</option>
                                <option value = '14:00'>14:00</option>
                                <option value = '15:00'>15:00</option>
                                <option value = '16:00'>16:00</option>
                                <option value = '17:00'>17:00</option>
                                <option value = '18:00'>18:00</option>
                                <option value = '19:00'>19:00</option>
                                <option value = '20:00'>20:00</option>
                                <option value = '21:00'>21:00</option>
                                <option value = '22:00'>22:00</option>
                                <option value = '23:00'>23:00</option>
                            </select>
                        </div>
                </td>
                <td> <button class = 'search-bt' id = cf_button onclick = 'changedate()'>view</button></td> 
            </tr>
        </table>  
    </div>

    <div class='body_main'>
        <div id="mapid"  style=" height: 100%;"></div>
        <!-- <div id="capture"></div> -->
    </div>
    <div class='tail_main'></div>
</body>

</html>

<script>
    var today = new Date();
    var yesterday = new Date();
    today.setUTCHours(18)
    yesterday.setUTCHours(-6)
    var dateTime = today.toISOString().slice(0,10);
    document.getElementById("time_start").defaultValue = dateTime;
    document.getElementById("time_start").max = dateTime;   
    var  mymap = L.map('mapid').setView([13.728345,100.778075], 17);
        L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(mymap);

      var a =0;
      var  dict =  {};
      file = 'map.geojson'
      var rawFile = new XMLHttpRequest();
      rawFile.open("GET", file, false);
      rawFile.onreadystatechange = async function ()
      {
          if(rawFile.readyState === 4)
          {
              if(rawFile.status === 200 || rawFile.status == 0)
              {
                  a= await rawFile.responseText;
                  a = JSON.parse(a)
                 // console.log(a)
                  changedate();
              }
          }
      }
    rawFile.send(null);
   
async function changedate(){
    mymap.eachLayer(function (layer) {
    mymap.removeLayer(layer);
    });
    L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(mymap);
    var start = document.getElementById("time_start").value+" "+document.getElementById("hour_sel").value +":00"
    //console.log(start)
    // call php query function
    await jQuery.ajax({
    type: "POST",
    url: 'queryfunc.php',
    dataType: 'json',
    data: {functionname: 'mapquerycn',arguement:[start]},
    success: function (obj) {
        dict = obj;
        console.log(dict);
            },
    error: function(){
        alert("Db is Error")
    }
    });
    L.geoJSON(a, {
        style: setStyle,
        onEachFeature: onEachFeature
    }).addTo(mymap);
    }


function setStyle(feature) {
    if (feature.properties['name'] in dict){
        numb= parseInt(dict[feature.properties['name']])
        if(numb > 100){
        return {
        "color": "red"
        }
        }
        else if (numb > 50){
        return {
        "color": "orange"
        }
        }
        else if (numb > 25)
        return {
        "color": "yellow"
        }
        else{
            return {
            "color": "pink"    }
        }
    }
    else{
        return {
            "color":  "#30D6F0"
        }
    }

    
}
async function onEachFeature(feature, layer) {
    if (feature.properties['name']  != undefined){
    var bw = 0;
    var start = document.getElementById("time_start").value+" "+document.getElementById("hour_sel").value +":00"
    await jQuery.ajax({
    type: "POST",
    url: 'queryfunc.php',
    dataType: 'json',
    data: {functionname: 'mapquerybw',arguement:[start,feature.properties['name']]},
    success: function (obj) {
        bw = obj;
       // console.log(bw);
        },
    error: function(){
        alert(feature.properties['name'])
    }
    });
    if (feature.properties['name'] in dict){
        layer.bindPopup(feature.properties['name']+": "+ dict[feature.properties['name']]+' คน' +bw +'Mbs');
    }
    else{
        layer.bindPopup(feature.properties['name']+":"+' 0 คน '+bw +' Mbs');
    }
    }
}

</script>