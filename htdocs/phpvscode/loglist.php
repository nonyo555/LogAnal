<html>

<head>
    <title>KMITL Log Analytics - Log List</title>
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <link href="table.css" media="all" rel="Stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>

    <script>/*
        var jsondata;
        async function callAPI() {
            //handshaking and get first set of data
            
            let res = await fetch("http://localhost:4002/api/netdiagRequest/2020/05/01")
            jsondata = await res.json();

            makeTable(jsondata,"");
        }

        function makeTable(jsondata,id) {     
            var cols = []; 
            
            var len = Object.keys(jsondata).length;
            
            for (var i = 0; i < len; i++) { 
                for (var k in jsondata[i]) { 
                    if (cols.indexOf(k) === -1) { 
                        // Push all keys to the array 
                        cols.push(k); 
                    } 
                } 
            } 

            var table = document.createElement("table"); 
            var tr = table.insertRow(-1); 
              
            for (var i = 0; i < cols.length; i++) { 
                var theader = document.createElement("th"); 
                theader.innerHTML = cols[i]; 
                tr.appendChild(theader); 
            } 

            // Adding the data to the table 
            for (var i = 1; i <= len; i++) {                  
                trow = table.insertRow(-1); 
                for (var j = 0; j < cols.length; j++) { 
                    var cell = trow.insertCell(-1); 
                    if(j == 2){ //check username
                        if(id == "" || id == jsondata[i][cols[j]]){
                            cell.innerHTML = jsondata[i][cols[j]]; 
                        }
                        else{
                            table.deleteRow(-1);
                        }
                    }
                    else {
                        cell.innerHTML = jsondata[i][cols[j]]; 
                    }
                } 
            } 
              
            var el = document.getElementById("table"); 
            el.innerHTML = ""; 
            el.appendChild(table); 
        }    
*/
        function validateForm(){
            var startInput = document.forms["inputForm"]["start"].value;
            var stopInput = document.forms["inputForm"]["stop"].value;
            if(startInput != "" && stopInput == ""){
                var today = new Date();
                var year = today.getFullYear();
                var month = today.getMonth() > 9 ? today.getMonth() : '0'+(today.getMonth()+1);
                var date = today.getDate() > 9 ? today.getDate() : '0'+today.getDate();
                var hour = today.getHours() > 9 ? today.getHours() : '0'+today.Hours();
                var minute = today.getMinutes() > 9 ? today.getMinutes() : '0'+today.Minutes();
                var dateTime = year + '-' + month + '-' + date + 'T' + hour + ':' + minute;
                document.getElementById("stop").value = dateTime;
            }
            else if(startInput == "" && stopInput != ""){
                alert("Please input start date");
                return false;
            }
            return true;
        }
    </script>

</head>

<body>
    <div class='head_main'>
        <a href="../phpvscode/index.php"><img src="../img/header.png"></a>
    </div>

    <div class='sub_main'>
        <label class='sub'>Top-problem</label>
    </div>

    <div class='body_main'>
        <h2>Log List Page</h2>
        <form name="inputForm" autocomplete="off" action="" onsubmit="return validateForm()" method="post">
            <div class=time>
                <label class='gtext'>Time</label>
                <input id="start" name="start" class='time_start' placeholder='Start' type='datetime-local' value="<?php echo isset($_POST['start']) ? $_POST['start'] : '' ?>"/>
                <label class='text'> : </label>
                <input id="stop" name="stop" class='time_stop' placeholder='Stop' type='datetime-local' value="<?php echo isset($_POST['stop']) ? $_POST['stop'] : '' ?>" />
            </div>
            <div style="text-align: center;">
                <div class='search' style="display: inline;">
                    <input type ="text" name="userID" id="search-id" type='search-bar' placeholder='Search by userID' value="<?php echo isset($_POST['userID']) ? $_POST['userID'] : '' ?>"/>
                    <button type="submit" class='search-bt'>Search</button>
                </div>
            </div>
        </form>
                <?php 
            function query($userID,$start,$stop){
                //MariaDB
                    $hostname = "localhost";
                    $username = "test";
                    $password = "pogfLUYGtHCVS8Bq";
                    $db = "log_analytics";

                    $mysqli = new mysqli($hostname,$username,$password,$db);
                    if($mysqli->connect_error){
                        die("Database connection failed: " . $mysqli->connect_error);
                    }
                    
                    if($userID == ""){
                        if($start == "" && $stop ==""){
                            //$query = mysqli_query($dbconnect, "SELECT * FROM book2")
                            //or die (mysqli_error($dbconnect));
                            $stmt = $mysqli->prepare("SELECT * FROM book2");
                        }
                        else if($start != "" && $stop !=""){
                            /*list($startdate,$starttime) = explode('T',$start);
                            list($stopdate,$stoptime) = explode('T',$stop);
                            $startdate .= " ".$starttime.':00';
                            $stopdate .= " ".$stoptime. ":00";*/
                            //$query = mysqli_query($dbconnect, "SELECT * FROM book2 WHERE time >= '{$startdate}' AND time <='{$stopdate}'")
                            //or die (mysqli_error($dbconnect));

                            $startdate = date('n/j/Y G:i',strtotime($start));
                            $stopdate = date('n/j/Y G:i',strtotime($stop));

                            $stmt = $mysqli->prepare("SELECT * FROM book2 WHERE time >= ? AND time <= ?");

                            $stmt->bind_param("ss",$startdate,$stopdate);
                        }
                    }
                    else{
                        if($start == "" && $stop == ""){
                            //$query = mysqli_query($dbconnect, "SELECT * FROM book2 WHERE userID = '{$userID}'")
                            //or die (mysqli_error($dbconnect));

                            $stmt = $mysqli->prepare("SELECT * FROM book2 WHERE userID = ?");

                            $stmt->bind_param("s",$userID);
                        }
                        else if($start != "" && $stop != ""){
                            /*list($startdate,$starttime) = explode('T',$start);
                            list($stopdate,$stoptime) = explode('T',$stop);
                            $startdate .= " ".$starttime.':00';
                            $stopdate .= " ".$stoptime. ":00";*/
                            //$query = mysqli_query($dbconnect, "SELECT * FROM book2 WHERE userID = '{$userID}' AND time >= '{$startdate}' AND time <='{$stopdate}'")
                            //or die (mysqli_error($dbconnect));

                            $startdate = date('n/j/Y G:i',strtotime($start));
                            $stopdate = date('n/j/Y G:i',strtotime($stop));

                            $stmt = $mysqli->prepare("SELECT * FROM book2 WHERE userID = ? AND time >= ? AND time <= ?");

                            $stmt->bind_param("sss",$userID,$startdate,$stopdate);
                        }
                    }

                    $stmt->execute();
                    $res = $stmt->get_result();

                    echo "<div class=\"loglists\" style=\"overflow-y: scroll; width: 98%;\" >
                    <span onscroll=\"\">";
                    echo "<table border=\"1\" align=\"center\">
                    <tr>
                      <th>time</th>
                      <th>userID</th>
                      <th>AP</th>
                      <th>Bdcode</th>
                      <th>Bdname</th>
                      <th>Floor</th>
                    </tr>";
                        while ($row = $res->fetch_assoc()){
                        echo
                        "<tr>
                        <td>{$row['time']}</td>
                        <td>{$row['userID']}</td>
                        <td>{$row['AP']}</td>
                        <td>{$row['Bdcode']}</td>
                        <td>{$row['BdName']}</td>
                        <td>{$row['Floor']}</td>
                        </tr>\n";
                    
                    }
                    
                echo "</span>
                </div>";
            }

                if(isset($_POST['userID']) && isset($_POST['start']) && isset($_POST['stop'])){
                    query($_POST['userID'],$_POST['start'],$_POST['stop']);
                }
            ?>
        <form action="">
            <input type="file" id="input" value="ImportFile" multiple>
            <button class='imp_bt' type=submit onclick="importFile()">import</button>
        </form>   
    </div>  

    <div class='tail_main'>
    
    </div>
</body>
<script>

    function readTextFile(file) {
        alert("hello")
        var file = fileList[i].name
        var rawFile = new XMLHttpRequest();
        rawFile.open("GET", file, false);
        rawFile.onreadystatechange = function () {
            if (rawFile.readyState === 4) {
                if (rawFile.status === 200 || rawFile.status == 0) {
                    var allText = rawFile.responseText;
                    alert(allText);
                    rawFile.send(null);
                    return allText
                }
                else {
                    alert('Nope');
                    rawFile.send(null);
                    return
                }
            }
        }
    }
    const inputElement = document.getElementById("input");
    inputElement.addEventListener('change', (event) => {
        const fileList = event.target.files;
        for (i = 0; i < fileList.length; i++) {
            console.log(fileList[i].name)
        }
    });
    function importFile() {
        var input = document.getElementById("input").files;
        for (var i = 0; i < input.length; i++) {
            console.log(input[i].name);
        }
    }

</script>

</html>