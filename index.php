
<?php
session_start();

// Mengecek apakah pengguna adalah admin atau user
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        echo "Anda adalah admin.";
    } else if ($_SESSION['role'] === 'user') {
        echo "Anda adalah user.";
    } else {
        echo "Role tidak diketahui.";
    }
} else {
    echo "Session role tidak ditemukan.";
}
?>

