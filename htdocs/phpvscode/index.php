<html>

<head>
    <title>KMITL Log Analytics</title>
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>

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
                    console.log(data);
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
                                    steps: 10,
                                    stepValue: 6,
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
        <label class='sub'>Top-problem</label>
    </div>
    <div class='body_main'>
        <table width='100%' height="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20%" height="80%" valign="top">
                    <br>
                    <br>
                    <span class='button'><a href="../phpvscode/map.php"
                            style="text-decoration:none; color: #333333;"><label class="text">Map</label></a></span>
                    <br>
                    <span class='button'><a href="../phpvscode/graph.php"
                            style="text-decoration:none; color: #333333;"><label class="text">Graph</label></a></span>
                    <br>
                    <span class='button'><a href="../phpvscode/loglist.php"
                            style="text-decoration:none; color: #333333;"><label class="text">Log
                                List</label></a></span>
                    <br>
                    <span class='button'> <a href="../phpvscode/buildinglist.php"
                            style="text-decoration:none; color: #333333;"><label class="text">Building
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

</html>