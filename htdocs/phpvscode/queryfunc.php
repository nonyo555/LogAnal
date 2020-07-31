<?php
    if($_POST['functionname'] == 'mapquerycn'){
        $start = $_POST['arguement'][0];
        $hostname = "localhost";
        $username = "root";
        $password = "1439";
        $db = "phpserver";
        $dbconnect=mysqli_connect($hostname,$username,$password,$db);
        if ($dbconnect->connect_error) {
        die("Database connection failed: " . $dbconnect->connect_error);
        }
        $sql = "SELECT * FROM count WHERE BeginTime = '$start' ";
        $result = mysqli_query($dbconnect,$sql);
        $dict = [];
        while ($row = mysqli_fetch_array($result)) {
            $dict[$row[2]] = [$row[3]];
        }
        echo json_encode($dict);
    }
    else if($_POST['functionname'] == 'mapquerybw'){
        $start = $_POST['arguement'][0];
        $building = $_POST['arguement'][1];
        $hostname = "localhost";
        $username = "root";
        $password = "1439";
        $db = "phpserver";
        $dbconnect=mysqli_connect($hostname,$username,$password,$db);
        if ($dbconnect->connect_error) {
        die("Database connection failed: " . $dbconnect->connect_error);
        }
        $sql = "SELECT * FROM Bandwidth WHERE  BdName LIKE '%$building%' and time >= '$start' limit 1";
        $result = mysqli_query($dbconnect,$sql);
        $band_val = 0 ;
        while ($row = mysqli_fetch_array($result)) {
           $band_val = $row[2];
        }
        echo json_encode($band_val);
    }
    else if ($_POST['functionname'] == 'scatterquerybw'){
        $start = $_POST['arguement'][0];
        $stop = $_POST['arguement'][1];
        $building = $_POST['arguement'][2];
        $hostname = "localhost";
        $username = "root";
        $password = "1439";
        $db = "phpserver";
        $dbconnect=mysqli_connect($hostname,$username,$password,$db);
        if ($dbconnect->connect_error) {
        die("Database connection failed: " . $dbconnect->connect_error);
        }
        $dataset = [];
        $sql = "SELECT * FROM Bandwidth where BdName LIKE '%$building%'  and time >= '$start' and time <= '$stop'";
        $result = mysqli_query($dbconnect,$sql);
        //เก็บ เวลา จำนวนคนที่ใช้ ค่าBandwidth
        while ($row = mysqli_fetch_array($result)) {
            array_push($dataset,[$row[0],$row[2]]);
        }
       // array_push($dataset,[$sql]);
        # time, BdName, speed
        echo json_encode($dataset);
    }
    else if ($_POST['functionname'] == 'scatterquerycn'){
        $start = $_POST['arguement'][0];
        $stop = $_POST['arguement'][1];
        $building = $_POST['arguement'][2];
        $hostname = "localhost";
        $username = "root";
        $password = "1439";
        $db = "phpserver";
        $dbconnect=mysqli_connect($hostname,$username,$password,$db);
        if ($dbconnect->connect_error) {
        die("Database connection failed: " . $dbconnect->connect_error);
        }
        $dataset = [];
        $sql = "SELECT * FROM count where BdName LIKE '$building'  and BeginTime >= '$start' and Begintime < '$stop'";
        $result = mysqli_query($dbconnect,$sql);
        //เก็บ เวลา จำนวนคนที่ใช้ ค่าBandwidth
        while ($row = mysqli_fetch_array($result)) {
            array_push($dataset,[$row[0],$row[3]]);
        }
       // array_push($dataset,[$sql]);
        # time, BdName, speed
        echo json_encode($dataset);
    }
    else if ($_POST['functionname'] == 'indexbarquery'){
        $hostname = "localhost";
        $username = "test";
        $password = "pogfLUYGtHCVS8Bq";
        $db = "log_analytics";

        $bdName = "อาคาร ECC";
        date_default_timezone_set('Asia/Bangkok');
        $stopdate = date('Y-m-d',time()) . " 00:00:00";
        $startdate = date_sub(date_create($stopdate),date_interval_create_from_date_string("7 days"));
        $startdate = date_format($startdate,"Y-m-d") . " 00:00:00";
        $dbconnect=mysqli_connect($hostname,$username,$password,$db);
        if ($dbconnect->connect_error) {
        die("Database connection failed: " . $dbconnect->connect_error);
        }

        $dataset = [];
        $sql = "SELECT BeginTime, SUM(users) FROM bdinterval WHERE BdName = '$bdName' AND BeginTime >= '$startdate' AND BeginTime < '$stopdate' GROUP BY CAST(BeginTime AS DATE)";
        $result = mysqli_query($dbconnect,$sql);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($dbconnect));
            exit();
        }
        while ($row = mysqli_fetch_array($result)) {
            array_push($dataset,[$row[0],$row[1]]);
        }
        echo json_encode($dataset);
    }
    else if ($_POST['functionname'] == 'topproblem'){
        $start = $_POST['arguement'][0];
        $stop = $_POST['arguement'][1];
        $hostname = "localhost";
        $username = "root";
        $password = "1439";
        $db = "phpserver";
        $dbconnect=mysqli_connect($hostname,$username,$password,$db);
        if ($dbconnect->connect_error) {
        die("Database connection failed: " . $dbconnect->connect_error);
        }
        $sql = "SELECT * FROM Bandwidth WHERE time >= '$start' and time <= '$stop' ";
        $result = mysqli_query($dbconnect,$sql);
        $dict = [];
        while ($row = mysqli_fetch_array($result)) {
            $dict[$row[1]] = [$row[2]];
        }
        echo json_encode($dict);
    }
    else if ($_POST['functionname'] == 'loglistquery'){
        $userID = $_POST['arguement'][0];
        $start = $_POST['arguement'][1];
        $stop = $_POST['arguement'][2];
        
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

    else if($_POST['functionname'] == 'buildinglistquery'){
        $buildingname = $_POST['arguement'];

        $hostname = "localhost";
        $username = "test";
        $password = "pogfLUYGtHCVS8Bq";
        $db = "log_analytics";

        $mysqli = new mysqli($hostname,$username,$password,$db);
        if($mysqli->connect_error){
            die("Database connection failed: " . $mysqli->connect_error);
        }

        echo "<h2>" . $buildingname . "</h2>"; 

        $stmt = $mysqli->prepare("SELECT * FROM buildingcsv WHERE buildingName = ?");
        $stmt->bind_param("s",$buildingname);

        $stmt->execute();
        $res = $stmt->get_result();

        echo "<div class=\"loglists\" style=\"overflow-y: scroll; width: 90%;\" >
        <span onscroll=\"\">";
                        
                    
        echo "<table border=\"1\" align=\"center\">
        <tr>
            <th>BuildingCode</th>
            <th>BuildingName</th>
            <th>IPClient</th>
            </tr>";
        while ($row = $res->fetch_assoc()){
            $bdCode = '%' . $row['BuildingCode'] .'%';
            $bdCode = str_replace('-', '', $bdCode);
            echo
            "<tr>
            <td>{$row['BuildingCode']}</td>
            <td>{$row['BuildingName']}</td>
            <td>{$row['IPClient']}</td>
            </tr>\n";

        }

        $stmt = $mysqli->prepare("SELECT * FROM apmac WHERE Name LIKE ?");
        $stmt->bind_param("s",$bdCode);

        $stmt->execute();
        $res = $stmt->get_result();

        echo "<table border=\"1\" align=\"center\">
        <tr>
            <th>AP</th>
            <th>MAC</th>
        </tr>";
        while ($row = $res->fetch_assoc()){
        echo
            "<tr>
            <td>{$row['Name']}</td>
            <td>{$row['MAC']}</td>
            </tr>\n";
        }

        echo "</span>
        </div>";
    }
?>