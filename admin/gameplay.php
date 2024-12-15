<?php
include 'includes/db.php';
include 'includes/auth_admin.php';

$sql = "SELECT g.*, u.username
        FROM gameplay g
        JOIN users u ON g.user_id = u.id
        ORDER BY g.waktu DESC";
$query = $conn->prepare($sql);
$query->execute();
$gameplay = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/dashboard.css" rel="stylesheet"/>
    <title>Admin Panel - Gameplay</title>
</head>
<body>
<div class="content">
        <div class="header">ADMIN PANEL</div>
        <div class="container">
            <div class="sidebar">
                <a href="dashboard_admin.php">Admin</a>
                <a href="data_user.php">User</a>
                <a href="gameplay.php">Gameplay</a>
                <a href="logout.php">log Out</a>
            </div>
        <div class="main">
            <?php if (isset($error)) { ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <h2>Daftar Gameplay</h2>
            <table class="gameplay-table">
                <thead>
                    <tr>
                        <th>ID Gameplay</th>
                        <th>Username</th>
                        <th>Pilihan 1</th>
                        <th>Pilihan 2</th>
                        <th>Pilihan 3</th>
                        <th>Pilihan 4</th>
                        <th>Waktu</th>
                        <th>Ending</th>
                        <th>Hapus Gameplay</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($gameplay) > 0) {
                        foreach ($gameplay as $game) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($game['id']); ?></td>
                                <td><?php echo htmlspecialchars($game['username']); ?></td>
                                <td><?php echo htmlspecialchars($game['pilihan1']); ?></td>
                                <td><?php echo htmlspecialchars($game['pilihan2']); ?></td>
                                <td><?php echo htmlspecialchars($game['pilihan3']); ?></td>
                                <td><?php echo htmlspecialchars($game['pilihan4']); ?></td>
                                <td><?php echo htmlspecialchars($game['waktu']); ?></td>
                                <td><?php echo htmlspecialchars($game['ending']); ?></td>
                                <td>
                                    <button onclick="deleteGameplay(<?php echo $game['id']; ?>)">
                                        <img src="assets/delete.png" alt="Delete" style=" width: 30px; height: 30px;">
                                    </button>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr><td colspan="9">Belum ada gameplay yang terdaftar</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>

    <script>
        function deleteGameplay(id) {
            if (confirm('Apakah Anda yakin ingin menghapus gameplay ini?')) {
                fetch('includes/delete_gameplay.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                            window.location.reload();
                    } else {
                        alert('Terjadi kesalahan saat menghapus gameplay.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan.');
                });
            }
        }
    </script>
</body>
</html>