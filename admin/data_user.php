<?php
include 'includes/db.php';
include 'includes/auth_admin.php';

$sql = "SELECT id, username, email, created_at 
        FROM users 
        WHERE role = 'user'";
$query = $conn->prepare($sql);
$query->execute();
$user = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <?php if (isset($error)) { ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <h2>Daftar User</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID User</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tanggal dibuat</th>
                        <th>Hapus User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($user) > 0) {
                        foreach ($user as $users) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($users['id']); ?></td>
                                <td><?php echo htmlspecialchars($users['username']); ?></td>
                                <td><?php echo htmlspecialchars($users['email']); ?></td>
                                <td><?php echo htmlspecialchars($users['created_at']); ?></td>
                                <td>
                                    <button onclick="deleteUser(<?php echo $users['id']; ?>)">
                                    <img src="assets/delete.png" alt="Delete" style=" width: 30px; height: 30px;">
                                    </button></td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr><td colspan="5">Belum ada User yang terdaftar</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>

        <script>
            function deleteUser(id) {
                if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                    fetch('includes/delete_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User berhasil dihapus.');
                        window.location.reload();
                    } else {
                        alert('Terjadi kesalahan saat menghapus user.');
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