<html>

<head>
    <title>KMITL Log Analytics</title>
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>

    <script>
        function createChart() {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange', 'Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: 'Example Graph',
                        data: [12, 19, 3, 5, 2, 3, 15, 4, 7, 21, 30, 1],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    responsive: false
                }
            });
        }

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
        }
    </script>
</head>

<body onload="callAPI(); createChart();">
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
                    <h2>Graph name 1</h2>
                    <canvas id="myChart" width="900" height="700" style="position: absolute; z-index: 1000;"></canvas>
                </td>
            </tr>
            <tr width="100%"></tr>
        </table>
    </div>
    <div class='tail_main'></div>

</body>

</html>