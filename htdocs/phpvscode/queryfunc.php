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
        $firstname = [];
        $number = [];
        $sql = "SELECT * FROM b WHERE DateTime >= '$start'";
        $result = mysqli_query($dbconnect,$sql);
        $firstname = [];
        $number = [];
        $dict = [];
        while ($row = mysqli_fetch_array($result)) {
            $dict[$row[1]] = [$row[2],$row[3]];
        }
        echo json_encode($dict);
    }
?>