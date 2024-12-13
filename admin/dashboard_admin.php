
<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();
    include 'includes/auth_admin.php';
    include 'includes/db.php';
?>

<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link href="assets/dashboard.css" rel="stylesheet"/>
  <title>Admin Panel</title>
  
 </head>
 <body>
    <div class="content">
        <div class="header">ADMIN PANEL</div>
        <div class="container">
            <div class="sidebar">
                <a href="dashboard_admin.php">Admin</a>
                <a href="data_user.php">User</a>
                <a href="gameplay.php">Gameplay</a>
                <a href="logout.php">Log Out</a>
            </div>
        <div class="main">
            <h1>SELAMAT DATANG ADMIN</h1>
            <img src="assets/logo.jpg" alt="UNDERTALE">
        </div>
        </div>
    </div>
 </body>
</html>
