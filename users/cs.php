<?php
require_once '../config/database.php';

$messages = [];
$error = '';

// Ambil semua pesan customer service
try {
    $stmt = $conn->prepare("SELECT * FROM customer_service ORDER BY created_at DESC");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error database: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Customer Service</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container" style="max-width:900px;margin-top:40px;">
        <h2>Daftar Pesan Customer Service</h2>
        <?php if (!empty($error)): ?>
            <div class="errors"><p><?php echo htmlspecialchars($error); ?></p></div>
        <?php endif; ?>
        <?php if (count($messages) > 0): ?>
            <table class="table table-dark table-striped align-middle table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Pesan</th>
                        <th>Waktu Kirim</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $idx => $msg): ?>
                        <tr>
                            <td><?php echo $idx + 1; ?></td>
                            <td><?php echo htmlspecialchars($msg['cs_nama']); ?></td>
                            <td><?php echo htmlspecialchars($msg['cs_email']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($msg['cs_pesan'])); ?></td>
                            <td><?php echo htmlspecialchars($msg['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada pesan customer service.</p>
        <?php endif; ?>
    </div>
</body>
</html>
