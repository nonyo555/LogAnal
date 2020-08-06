<html>

<head>
    <title>KMITL Log Analytics</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            createChart();
        })

        async function createChart() {
            await jQuery.ajax({
                type: "POST",
                url: 'queryfunc.php',
                dataType: 'json',
                data: {functionname: 'indexbarquery'},
                success: function (data) {
                    //console.log(data);
                    var date = [];
                    var usage = [];

                    for(var i in data){
                        date.push(data[i][0].split(' ')[0]);
                        usage.push(data[i][1]);
                    }

                    var chartdata = {
                        labels: date,
                        datasets: [
                            {
                                label: 'Users',
                                backgroundColor: '#49e2ff',
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: usage
                            }
                        ]
                    }

                    var graphTarget = $("#myChart");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 500 ,
                                }
                            }]
                        }
                    }
                    });
                },
                error: function(){
                    alert("Error")
                }
            })
        }
/*
        function callAPI() {
            //handshaking and get first set of data
            var jsondata;
            fetch("http://localhost:4002/api/netdiagRequest/realtime")
                .then((res) => {
                    return res.json();
                }).then((json) => {
                    jsondata = json;
                    createConnection();
                }).catch((err) => {
                    console.log(err);
                })
        }

        function createConnection() {
            //create long live full-duplex communication(via Web Socket)
            var socket = io('http://localhost:4002');
            socket.on('netdiagDataUpdate', data => {
                jsondata = data;
                console.log("updated data");
            })
        }*/
    </script>
</head>

<body onload="">
    <div class='head_main'>
        <a href="../phpvscode/index.php"><img src="../img/header.png"></a>
    </div>
    <div class='sub_main'>
        <label id = 'sub' class='sub'>Top-problem</label>
    </div>
    <div class='body_main'>
        <table width='100%' height="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20%" height="80%" valign="top">
                    <br>
                    <br>
                    <span class='button'><a href="../phpvscode/map.php"
                            style="text-decoration:none; color: #333333;"><label class="text" style="cursor:pointer;">Map</label></a></span>
                    <br>
                    <span class='button'><a href="../phpvscode/graph.php"
                            style= "text-decoration:none; color: #333333;"><label class="text" style= "cursor:pointer;">Graph</label></a></span>
                    <br>
                    <span class='button'><a href="../phpvscode/loglist.php"
                            style="text-decoration:none; color: #333333;"><label class="text" style= "cursor:pointer;">Log
                                List</label></a></span>
                    <br>
                    <span class='button'> <a href="../phpvscode/buildinglist.php"
                            style="text-decoration:none; color: #333333;"><label class="text" style="cursor:pointer;">Building
                                List</label></a></span>
                </td>
                <td width="80%" height="100%" valign="top">
                    <!--span class='graph'> .......</span-->
                    <br><br>
                    <h2>จำนวนผู้ใช้งานระบบในตึก ECC</h2>
                    <div class="chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                    
                </td>
            </tr>
            <tr width="100%"></tr>
        </table>
    </div>
    <div class='tail_main'></div>

</body>
<script>
topproblem();
async function topproblem(){
    var dict = [];
    var today = new Date();
    var yesterday = new Date();
    today.setUTCHours(18)
    yesterday.setUTCHours(-6)
    var dateTime = today.toISOString().slice(0,16);
    var yesTime  = yesterday.toISOString().slice(0,16);
    await jQuery.ajax({
    type: "POST",
    url: 'queryfunc.php',
    dataType: 'json',
    data: {functionname: 'topproblem',arguement:[yesTime,dateTime]},
    success: function (obj) {
        dict = obj;
        //console.log(dict);
            },
    error: function(){
        alert("Db is Error")
    }
    });
    lessbw = 'No';
    for(var key in dict){
        if(!('unKnownNetwork' == key.slice(0,14))){
        if(lessbw == 'No'){
            lessbw = [key,dict[key]];
        }
        else{
            if(parseInt(lessbw[1]) > parseInt(dict[key])){
                lessbw = [key,dict[key]];
            }

        }
        }
    }
    if (lessbw[1] < 5 ){
        document.getElementById("sub").innerHTML =  "ณ "+lessbw[0]+' เวลา '+moment(yesterday).format("dddd, MMMM Do YYYY HH:MM:SS")+" - "+ moment(today).format("dddd, MMMM Do YYYY HH:MM:SS") + 'มีค่า Bandwidth อยู่ที่ ' +lessbw[1]+" Mbs"
    }
    else {
        document.getElementById("sub").innerHTML = 'ไม่มีค่า Bandwidth ที่ตํ่าเกินไปในเวลา ' +moment(yesterday).format("dddd, MMMM Do YYYY HH:MM:SS")+" - "+ moment(today).format("dddd, MMMM Do YYYY HH:MM:SS")
    }
}

</script>
</html>
