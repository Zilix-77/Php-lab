<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lab_app";

// 👉 change only when deploying
// $host = "sqlXXX.epizy.com";
// $user = "your_user";
// $pass = "your_pass";
// $db   = "your_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("DB Error");
    }
    ?>