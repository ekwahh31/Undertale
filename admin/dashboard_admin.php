
<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();
    include 'includes/auth_admin.php';
    include 'includes/db.php';
?>

