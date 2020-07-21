<?php
    if($_POST['functionname'] == 'mapquery'){
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
        $sql = "SELECT * FROM b WHERE DateTime >= '$start' and DateTime < '$stop'";
        $result = mysqli_query($dbconnect,$sql);
        $dict = [];
        while ($row = mysqli_fetch_array($result)) {
            $dict[$row[1]] = [$row[2],$row[3]];
        }
        echo json_encode($dict);
    }
    else if ($_POST['functionname'] == 'scatterquery'){
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
        $sql = "SELECT * FROM b where  Building= '$building'  and DateTime >= '$start' and DateTime < '$stop'";
        $result = mysqli_query($dbconnect,$sql);
        //เก็บ เวลา จำนวนคนที่ใช้ ค่าBandwidth
        while ($row = mysqli_fetch_array($result)) {
            array_push($dataset,[$row[0],$row[2],$row[3]]);
        }
        echo json_encode($dataset);
    }
?>