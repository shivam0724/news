<?php

$db_user = "root";
$db_pass = "";
$db_name = "news";

$conn = mysqli_connect("localhost", $db_user, $db_pass, $db_name);

if(!$conn){
    echo "Connection Failed";
}
