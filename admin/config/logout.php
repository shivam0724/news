<?php

include $_SERVER['DOCUMENT_ROOT'] . "/config/db_conn.php";

session_start();

logout_time($conn);

unset($_SESSION['admin']);

session_destroy();

header("Location: /");
function logout_time($conn){
    $logoutUpdate = $conn->prepare("UPDATE admin SET last_logout_time=CURRENT_TIMESTAMP WHERE admin_username=?");
    $logoutUpdate->bind_param("s", $_SESSION['admin']['admin_username']);
    $logoutUpdate->execute();
}