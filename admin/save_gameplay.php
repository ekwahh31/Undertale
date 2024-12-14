<?php
session_start();  
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    echo "Session user_id tidak ditemukan!";
    exit();
}

$userId = $_SESSION['user_id'];

$checkUser = $conn->prepare("SELECT id FROM users WHERE id = :user_id");
$checkUser->bindParam(':user_id', $userId);
$checkUser->execute();

if ($checkUser->rowCount() === 0) {
    echo "User ID tidak ditemukan!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pilihan1 = $_POST['pilihan1'];
    $pilihan2 = $_POST['pilihan2'];
    $pilihan3 = $_POST['pilihan3'];
    $pilihan4 = $_POST['pilihan4'];

    $ending = determineEnding([$pilihan1, $pilihan2, $pilihan3, $pilihan4]);

    $sql = "INSERT INTO gameplay(user_id, pilihan1, pilihan2, pilihan3, pilihan4, waktu, ending) 
            VALUES (:user_id, :pilihan1, :pilihan2, :pilihan3, :pilihan4, NOW(), :ending)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':pilihan1', $pilihan1);
    $stmt->bindParam(':pilihan2', $pilihan2);
    $stmt->bindParam(':pilihan3', $pilihan3);
    $stmt->bindParam(':pilihan4', $pilihan4);
    $stmt->bindParam(':ending', $ending);

    if ($stmt->execute()) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Gagal menyimpan data!";
    }
}

function determineEnding($choices) {
    $pacifistChoices = ["Ikuti Toriel dengan sabar", "Tolak tawaran Flowey", "Ikuti jejak kaki", "Bicara dan coba berdamai"];
    $genocideChoices = ["Terima tawaran Flowey", "Serang tanpa ampun"];

    if (count(array_intersect($pacifistChoices, $choices)) === count($pacifistChoices)) {
        return "pacifist";
    } else if (count(array_intersect($genocideChoices, $choices)) > 0) {
        return "genocide";
    } else {
        return "neutral";
    }
}
?>
