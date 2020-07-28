<html>

<head>
    <title>KMITL Log Analytics - Log List</title>
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <link href="table.css" media="all" rel="Stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
        <form name="inputForm" id="inputForm" autocomplete="off" method="post">
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
        <div id="data_table"></div>
        <form name="fileInputForm" id="fileInputForm" action = "" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file" value="ImportFile">
            <button name="submit_btn" class='imp_bt' type=submit accept=".csv">import</button>
        </form>
        <script type="text/javascript">
        $(document).ready(function() {
            $('#inputForm').submit(function(e) {
                var validated = validateForm();
                if(validated){
                    var userID = document.forms["inputForm"]["userID"].value;
                    var start = document.forms["inputForm"]["start"].value;
                    var stop = document.forms["inputForm"]["stop"].value;
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: 'queryfunc.php',
                        data: {functionname: 'loglistquery',arguement: [userID,start,stop]},
                        success: function(table)
                        {
                            $("#data_table").html(table);
                        }
                    });
                }
            });
            $('#fileInputForm').submit(function () {
                var fileType = ".csv";
                var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
                if (!regex.test($("#file").val().toLowerCase())) {
                    alert("Invalid File. Upload : " + fileType + " File.");
                    return false;
                }
                return true;
        });
    });
        </script>
        <?php          
            $hostname = "localhost";
            $username = "test";
            $password = "pogfLUYGtHCVS8Bq";
            $db = "log_analytics";
    
            $mysqli = new mysqli($hostname,$username,$password,$db);
            if($mysqli->connect_error){
                die("Database connection failed: " . $mysqli->connect_error);
            }

            if(isset($_POST["submit_btn"])){
                $filename = $_FILES["file"]["tmp_name"];
                if ($_FILES["file"]["size"] > 0) {
                    $file = fopen("$filename","r");

                    $flag = true;
                    while(($column = fgetcsv($file,30000,",")) !== FALSE){
                        if($flag){/*
                            $sql = "SHOW COLUMNS FROM book2";
                            $res = $mysqli->query($sql);

                            while($row = $res->fetch_assoc()){
                                $columns[] = $row['Field'];
                            }*/
                            
                            $column[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $column[0]);
                
                            if($column[0]=="userID"
                            &&$column[1]=="AP"
                            &&$column[2]=="time"
                            &&$column[3]=="Bdcode"
                            &&$column[4]=="BdName"
                            &&$column[5]=="Floor"){
                                $flag = false; 
                                continue;
                            } else {
                                echo '<script type="text/javascript">';
                                echo 'alert("Error : table columns does not match with the database")';
                                echo '</script>';
                                break;
                            }
                        }
                        else{
                            $userID = "";
                            $AP = "";
                            $time = "";
                            $Bdcode = "";
                            $BdName = "";
                            $Floor = "";

                            if (isset($column[0])) {
                                $userID = mysqli_real_escape_string($mysqli, $column[0]);
                            }
                            if (isset($column[1])) {
                                $AP = mysqli_real_escape_string($mysqli, $column[1]);
                            }
                            if (isset($column[2])) {
                                $time = mysqli_real_escape_string($mysqli, $column[2]);
                            }
                            if (isset($column[3])) {
                                $Bdcode = mysqli_real_escape_string($mysqli, $column[3]);
                            }
                            if (isset($column[4])) {
                                $BdName = mysqli_real_escape_string($mysqli, $column[4]);
                            }
                            if (isset($column[5])) {
                                $Floor = mysqli_real_escape_string($mysqli, $column[5]);
                            }
                            
                            $stmt = $mysqli->prepare("INSERT INTO book2 (userID,AP,time,Bdcode,BdName,Floor) VALUES (?,?,?,?,?,?)");
            
                            $stmt->bind_param("ssssss",$userID,$AP,$time,$Bdcode,$BdName,$Floor);
            
                            $stmt->execute(); 
                        }
                    }
                    echo '<script type="text/javascript">';
                    echo 'alert("Insert data Successfully!!")';
                    echo '</script>';
                }
            }
            
        ?>
    </div>  

    <div class='tail_main'>
    
    </div>
</body>
<script>
/*
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
*/
</script>

</html>