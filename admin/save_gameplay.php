<?php
session_start();
include('includes/db.php');  // Pastikan koneksi db.php sudah benar

// Pastikan ada session user_id
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Session user_id tidak ditemukan!"]);
    exit();
}

$userId = $_SESSION['user_id'];

// Verifikasi apakah user ada dalam database
$checkUser = $conn->prepare("SELECT id FROM users WHERE id = :user_id");
$checkUser->bindParam(':user_id', $userId);
$checkUser->execute();

if ($checkUser->rowCount() === 0) {
    echo json_encode(["status" => "error", "message" => "User ID tidak ditemukan!"]);
    exit();
}

// Proses data jika request method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pilihan1'], $_POST['pilihan2'], $_POST['pilihan3'], $_POST['pilihan4'])) {
        $pilihan1 = $_POST['pilihan1'];
        $pilihan2 = $_POST['pilihan2'];
        $pilihan3 = $_POST['pilihan3'];
        $pilihan4 = $_POST['pilihan4'];

        // Tentukan ending berdasarkan pilihan yang diambil
        $ending = determineEnding([$pilihan1, $pilihan2, $pilihan3, $pilihan4]);

        // Masukkan data gameplay ke database
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
            // Jika berhasil, update achievement dan kirimkan respons
            updateAchievement($userId, $ending);
            echo json_encode(["status" => "success", "message" => "Data berhasil disimpan!", "ending" => $ending]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan data!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Data pilihan tidak lengkap!"]);
    }
}

// Fungsi untuk menyimpan progres game
function saveGameProgress($userId, $pilihan1, $pilihan2, $pilihan3, $pilihan4) {
    global $conn;

    // Tentukan ending berdasarkan pilihan pemain
    $ending = determineEnding([$pilihan1, $pilihan2, $pilihan3, $pilihan4]);

    // Simpan data gameplay ke database
    $sql = "INSERT INTO gameplay (user_id, pilihan1, pilihan2, pilihan3, pilihan4, waktu, ending) 
            VALUES (:user_id, :pilihan1, :pilihan2, :pilihan3, :pilihan4, NOW(), :ending)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':pilihan1', $pilihan1);
    $stmt->bindParam(':pilihan2', $pilihan2);
    $stmt->bindParam(':pilihan3', $pilihan3);
    $stmt->bindParam(':pilihan4', $pilihan4);
    $stmt->bindParam(':ending', $ending);
    $stmt->execute();

    // Update achievement jika diperlukan
    updateAchievement($userId, $ending);

    return $ending;
}

// Fungsi untuk menentukan ending berdasarkan pilihan
function determineEnding($choices) {
    $pacifistChoices = ["Ikuti Toriel dengan sabar", "Tolak tawaran Flowey", "Ikuti jejak kaki", "Bicara dan coba berdamai"];
    $genocideChoices = ["Terima tawaran Flowey", "Serang tanpa ampun"];

    if (count(array_intersect($pacifistChoices, $choices)) === count($pacifistChoices)) {
        return "pacifist";
    } 
    else if (count(array_intersect($genocideChoices, $choices)) > 0) {
        return "genocide";
    } 
    else {
        return "neutral";
    }
}

// Fungsi untuk update achievement yang baru
function updateAchievement($userId, $ending) {
    global $conn;
    
    // Tentukan achievement berdasarkan ending
    $achievement = '';
    if ($ending === 'pacifist') {
        $achievement = 'pacifist';
    } else if ($ending === 'genocide') {
        $achievement = 'genocide';
    } else {
        $achievement = 'neutral';
    }

    // Ambil achievement yang sudah ada di database
    $sql = "SELECT achievement FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Cek jika achievement sudah ada
        $existingAchievements = explode(',', $user['achievement']);
        
        // Tambahkan achievement baru jika belum ada
        if (!in_array($achievement, $existingAchievements)) {
            // Menambahkan achievement baru ke string yang sudah ada
            $existingAchievements[] = $achievement;
            $newAchievements = implode(',', $existingAchievements);
            
            // Update achievement di tabel users
            $updateSql = "UPDATE users SET achievement = :achievement WHERE id = :user_id";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':user_id', $userId);
            $updateStmt->bindParam(':achievement', $newAchievements);
            $updateStmt->execute();
        }
    }
}

?>
