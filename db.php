<?php
$conn = mysqli_connect(
    "sql104.infinityfree.com",
    "if0_41418657",
    "BV8zzMKAdHTvsv1",
    "if0_41418657_lab_app"
);

if(!$conn){
    die("Connection failed");
}
?>
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
