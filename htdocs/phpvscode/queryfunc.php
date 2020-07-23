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
?>