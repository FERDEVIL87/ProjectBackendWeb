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
    $gambar = null;

    // Proses upload gambar ke LONGBLOB
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
    }

    // Save to database (gambar ke LONGBLOB)
    $stmt = $conn->prepare("INSERT INTO pc_part (nama, harga, kategori, deskripsi, spesifikasi, gambar) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$nama, $harga, $kategori, $deskripsi, $spesifikasi, $gambar])) {
        $success = true;
    }
}

// Fetch part list
try {
    $stmt = $conn->prepare("SELECT * FROM pc_part ORDER BY id DESC");
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
    <title>Input Data PC Part</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <section class="pc-list-section-bs">
        <div class="container py-4 py-md-5">
            <h2 class="section-title-bs text-center">Input Data PC Part</h2>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <form class="admin-card-bs p-4" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nama PC Part</label>
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
                                <option value="Processor Intel">Processor Intel</option>
                                <option value="Processor AMD">Processor AMD</option>
                                <option value="Mainboard">Mainboard</option>
                                <option value="Memory">Memory</option>
                                <option value="VGA">VGA</option>
                                <option value="HDD">HDD</option>
                                <option value="SSD">SSD</option>
                                <option value="PSU">PSU</option>
                                <option value="Case">Case</option>
                                <option value="LED Monitor">LED Monitor</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Webcam">Webcam</option>
                                <option value="Cable">Cable</option>
                                <option value="Speaker">Speaker</option>
                                <option value="USB Flashdisk">USB Flashdisk</option>
                                <option value="Printer">Printer</option>
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
                        <div class="mb-3">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*" required>
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
                                        <th>Nama Komponen PC</th>
                                        <th>Harga</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Spesifikasi</th>
                                        <th>Gambar</th>
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
                                                <td>
                                                    <?php if (!empty($item['gambar'])): ?>
                                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['gambar']); ?>" alt="Gambar" style="max-width:80px;max-height:80px;">
                                                    <?php else: ?>
                                                        <span class="text-muted">Tidak ada gambar</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada data PC Part.</td>
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
