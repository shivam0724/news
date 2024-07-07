<?php

use PSpell\Config;

session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/config/db_conn.php";

if (isset($_POST['admin_login'])) {
  $admin_login = $conn->prepare("SELECT * FROM admin WHERE admin_username=?");
  $admin_login->bind_param("s", $_POST['admin_username']);
  if ($admin_login->execute()) {
    $result = $admin_login->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if ($row['admin_password'] == $_POST['admin_password']) {
        $_SESSION['admin']['admin_username'] = $row['admin_username'];

        login_time($conn);

        header("Location: /admin/");
      }
    }
    else{
      echo '<script>alert("Invalid Admin Credentials.")</script>';
    }
  }
}

function login_time($conn)
{
  $loginUpdate = $conn->prepare("UPDATE admin SET last_login_time=CURRENT_TIMESTAMP WHERE admin_username=?");
  $loginUpdate->bind_param("s", $_POST['admin_username']);
  $loginUpdate->execute();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="/admin/assets/css/style.css">
</head>

<body>
  <div class="container flex">
    <div class="login flex">
      <h2>Admin Login</h2>
      <form action="" method="post" class="login-form flex">
        <label for="admin_username">Enter Your Username</label>
        <input type="text" name="admin_username" id="admin_username" placeholder="username" required>
        <label for="admin_password">Enter Your Password</label>
        <input type="text" name="admin_password" id="admin_password" placeholder="password" required>
        <div class="login-btn flex">
          <button type="submit" name="admin_login" id="admin_login">Login</button>
        </div>
      </form>
    </div>
  </div>
</body>

<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>

</html>