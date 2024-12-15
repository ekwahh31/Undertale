<?php
session_start();  
include('includes/db.php');  // Pastikan file ini mengandung koneksi database

// Periksa apakah session user_id ada
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Session user_id tidak ditemukan!"]);
    exit();
}

$userId = $_SESSION['user_id'];  // Ambil user_id dari session

// Ambil data user dan achievement berdasarkan user_id
$query = $conn->prepare("SELECT username, email, achievement FROM users WHERE id = :user_id");
$query->bindParam(':user_id', $userId);
$query->execute();

// Periksa jika data user ditemukan
if ($query->rowCount() > 0) {
    $user = $query->fetch(PDO::FETCH_ASSOC);  // Ambil data sebagai array
    $username = $user['username'];
    $email = $user['email'];
    $achievement = $user['achievement'];  // Ambil status achievement

    // Pecah achievement menjadi array
    $achievementArray = explode(',', $achievement);

    echo json_encode([
        "status" => "success",
        "username" => $username,
        "email" => $email,
        "achievement" => $achievementArray  // Kirimkan array achievement
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan!"]);
    exit();
}
?>
