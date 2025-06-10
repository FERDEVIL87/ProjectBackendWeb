<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// Ambil data checkout
if ($is_admin) {
    $stmt = $conn->prepare("SELECT c.*, u.username FROM checkouts c LEFT JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC");
    $stmt->execute();
    $checkoutList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->prepare("SELECT c.*, u.username FROM checkouts c LEFT JOIN users u ON c.user_id = u.id WHERE c.user_id = ? ORDER BY c.created_at DESC");
    $stmt->execute([$user_id]);
    $checkoutList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ambil item untuk setiap checkout
$checkoutItems = [];
if (!empty($checkoutList)) {
    $ids = array_column($checkoutList, 'id');
    $in = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $conn->prepare("SELECT * FROM checkout_items WHERE checkout_id IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
        $checkoutItems[$item['checkout_id']][] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Checkout</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .checkout-items-table th, .checkout-items-table td {padding:4px 8px;}
        .order-box {border:1px solid #ccc;padding:12px;margin-bottom:18px;}
    </style>
</head>
<body>
    <div class="container" style="max-width:900px;margin-top:40px;">
        <h2>Daftar Checkout</h2>
        <?php if (count($checkoutList) > 0): ?>
            <?php foreach ($checkoutList as $order): ?>
                <div class="order-box">
                    <?php if ($is_admin): ?>
                        <strong>User:</strong> <?php echo htmlspecialchars($order['username'] ?? ''); ?><br>
                    <?php endif; ?>
                    <strong>Nama:</strong> <?php echo htmlspecialchars($order['nama']); ?><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?><br>
                    <strong>Alamat:</strong> <?php echo htmlspecialchars($order['alamat']); ?><br>
                    <strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($order['status'])); ?><br>
                    <strong>Waktu Order:</strong> <?php echo htmlspecialchars($order['created_at']); ?><br>
                    <strong>Item:</strong>
                    <table class="checkout-items-table" border="1" style="margin-top:8px;">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                        </tr>
                        <?php if (!empty($checkoutItems[$order['id']])): ?>
                            <?php foreach ($checkoutItems[$order['id']] as $idx => $item): ?>
                                <tr>
                                    <td><?php echo $idx + 1; ?></td>
                                    <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                                    <td><?php echo htmlspecialchars($item['qty']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3">Tidak ada item.</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada order checkout.</p>
        <?php endif; ?>
    </div>
</body>
</html>
