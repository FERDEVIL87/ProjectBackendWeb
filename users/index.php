<?php
require_once 'auth_check.php'; // Wajib login
require_once '../config/database.php';

// Ambil semua data user, diurutkan berdasarkan tanggal dibuat
try {
    $stmt = $conn->prepare("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $page_error = "Error mengambil data user: " . $e->getMessage();
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Backend Toko Komputer</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header-nav">
            <h2>Manajemen Backend Toko Komputer</h2>
            <div>
                <span>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>) | </span>
                <a href="../index.php">Halaman Utama</a> |
                <a href="../auth/logout.php">Logout</a>
            </div>
        </div>

        <nav class="menu-nav">
            <ul>
                <li><a href="../users/pc_rakitan.php">Paket Rakitan PC</a></li>
                <li><a href="../users/laptop.php">Laptop</a></li>
                <li><a href="../users/console_n_handheld.php">Console & Handheld PC</a></li>
                <li><a href="../users/pc_part.php">PC Parts</a></li>
                <li><a href="../users/cs.php">Customer Service</a></li>
                <li><a href="../users/checkout.php">Checkout</a></li>
            </ul>
        </nav>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success'; ?>">
                <p><?php echo htmlspecialchars($_SESSION['message']); ?></p>
            </div>
            <?php
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>
        
        <?php if (isset($page_error)): ?>
            <div class="errors"><p><?php echo htmlspecialchars($page_error); ?></p></div>
        <?php endif; ?>

        <p><a href="create.php" class="btn">Tambah User Baru</a></p>

        <?php if (count($users) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                            <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($user['created_at']))); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                                <?php if ($_SESSION['user_id'] != $user['id']): ?>
                                <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada user terdaftar.</p>
        <?php endif; ?>
    </div>
</body>
</html>