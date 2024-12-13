<?php
if (!isset($_SESSION)) {
    session_start(); 
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}
?>
