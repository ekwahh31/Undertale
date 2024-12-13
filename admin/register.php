<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Cek apakah username sudah terdaftar
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<script>alert('Username sudah terdaftar.'); window.location.href = '../register.php';</script>";
        } else {
            // Cek apakah email sudah terdaftar
            $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo "<script>alert('Email sudah terdaftar.'); window.location.href = '../register.php';</script>";
            } else {
                // Masukkan data user baru ke database
                $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                if ($stmt->execute([$name, $email, $password])) {
                    echo "<script>alert('Pendaftaran berhasil. Silakan login.'); window.location.href = '../login.php';</script>";
                } else {
                    echo "<script>alert('Terjadi kesalahan saat mendaftar.'); window.location.href = '../register.php';</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Semua field harus diisi.'); window.location.href = '../register.html';</script>";
    }
}
$conn = null;
?>
