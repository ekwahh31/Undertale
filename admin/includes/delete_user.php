<?php
include 'db.php';  

$data = json_decode(file_get_contents("php://input"));


if (isset($data->id)) {
    $userId = $data->id;
    $sql = "DELETE FROM users WHERE id = :id";
    $query = $conn->prepare($sql);
    $query->bindParam(':id', $userId, PDO::PARAM_INT);

    try {
        $query->execute();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID tidak ditemukan']);
}
?>
