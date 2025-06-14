<?php
require_once '../config/database.php';

$success = false;
$pcPartList = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $brand = $_POST['brand'] ?? '';
    $category = $_POST['category'] ?? '';
    $image = $_POST['image'] ?? '';
    $specs = $_POST['specs'] ?? [];
    $stock = $_POST['stock'] ?? 0;

    // Save to database
    $stmt = $conn->prepare("INSERT INTO pc_parts (name, price, brand, category, image, specs, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $price, $brand, $category, $image, json_encode($specs), $stock])) {
        $success = true;
    }
}

// Fetch part list
try {
    $stmt = $conn->prepare("SELECT * FROM pc_parts ORDER BY id DESC");
    $stmt->execute();
    $pcPartList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data PC Part</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <section class="pc-list-section-bs">
        <div class="container py-4 py-md-5">
            <h2 class="section-title-bs text-center">Input Data PC Part</h2>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <form class="admin-card-bs p-4" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama PC Part</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input name="price" type="number" class="form-control" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input name="brand" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input name="category" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar (URL)</label>
                            <input name="image" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Spesifikasi</label>
                            <textarea name="specs" class="form-control" rows="3" placeholder='Contoh: ["24 Cores", "32 Threads", "5.80 GHz Base Clock"]' required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input name="stock" type="number" class="form-control" required min="0">
                        </div>
                        <button class="login-btn-bs w-100" type="submit">Simpan Data</button>
                        <?php if ($success): ?>
                            <p class="success-bs mt-3">Data PC Part berhasil disimpan!</p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-12">
                    <div class="admin-card-bs p-3">
                        <h4 class="mb-3" style="font-family:'Orbitron',sans-serif;color:var(--primary-color);">Daftar PC Part</h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped align-middle table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Brand</th>
                                        <th>Kategori</th>
                                        <th>Gambar</th>
                                        <th>Spesifikasi</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($pcPartList) > 0): ?>
                                        <?php foreach ($pcPartList as $idx => $item): ?>
                                            <tr>
                                                <td><?php echo $idx + 1; ?></td>
                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                                <td><?php echo htmlspecialchars($item['brand']); ?></td>
                                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                                <td>
                                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Gambar" style="max-width:80px;max-height:80px;">
                                                </td>
                                                <td>
                                                    <ul>
                                                        <?php foreach (json_decode($item['specs'], true) as $spec): ?>
                                                            <li><?php echo htmlspecialchars($spec); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </td>
                                                <td><?php echo htmlspecialchars($item['stock']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Belum ada data PC Part.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
