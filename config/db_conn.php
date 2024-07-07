<?php

date_default_timezone_set('Asia/Calcutta');
@define('APP', $_SERVER['DOCUMENT_ROOT']."config/app.php");
@define('ADMIN_APP', $_SERVER['DOCUMENT_ROOT']."admin/config/admin_app.php");

$db_user = "root";
$db_pass = "";
$db_name = "news";

$conn = mysqli_connect("localhost", $db_user, $db_pass, $db_name);

if(!$conn){
    echo "Connection Failed";
}