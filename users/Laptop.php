<?php
require_once '../config/database.php';

$success = false;
$laptopList = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $kategori = $_POST['kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $spesifikasi = $_POST['spesifikasi'] ?? '';

    // Save to database (example query, adjust as needed)
    $stmt = $conn->prepare("INSERT INTO laptops (nama, harga, kategori, deskripsi, spesifikasi) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$nama, $harga, $kategori, $deskripsi, $spesifikasi])) {
        $success = true;
    }
}

// Fetch laptop list
try {
    $stmt = $conn->prepare("SELECT * FROM laptops ORDER BY id DESC");
    $stmt->execute();
    $laptopList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Laptop</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <section class="pc-list-section-bs">
        <div class="container py-4 py-md-5">
            <h2 class="section-title-bs text-center">Input Data Laptop</h2>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <form class="admin-card-bs p-4" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Laptop</label>
                            <input name="nama" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input name="harga" type="number" class="form-control" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Low-End">Low-End</option>
                                <option value="Mid-Range">Mid-Range</option>
                                <option value="High-End">High-End</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Spesifikasi (opsional)</label>
                            <textarea name="spesifikasi" class="form-control" rows="3" placeholder="Contoh: CPU: Intel Core i5, RAM: 8GB, SSD: 512GB, ..."></textarea>
                        </div>
                        <button class="login-btn-bs w-100" type="submit">Simpan Data</button>
                        <?php if ($success): ?>
                            <p class="success-bs mt-3">Data laptop berhasil disimpan!</p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-12">
                    <div class="admin-card-bs p-3">
                        <h4 class="mb-3" style="font-family:'Orbitron',sans-serif;color:var(--primary-color);">Daftar Laptop</h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped align-middle table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Laptop</th>
                                        <th>Harga</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Spesifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($laptopList) > 0): ?>
                                        <?php foreach ($laptopList as $idx => $item): ?>
                                            <tr>
                                                <td><?php echo $idx + 1; ?></td>
                                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                                <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                                <td><?php echo htmlspecialchars($item['kategori']); ?></td>
                                                <td><?php echo htmlspecialchars($item['deskripsi']); ?></td>
                                                <td><?php echo htmlspecialchars($item['spesifikasi']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada data laptop.</td>
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
