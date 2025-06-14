<?php
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = "localhost"; // atau alamat IP server database kamu
$db = "Prak9";
$user = "root"; // username database kamu
$pass = ""; // password database kamu (kosongkan jika tidak ada)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, name, price, brand, category, image, specs, stock FROM pc_parts");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Decode JSON specs field
    foreach ($data as &$item) {
        $item['specs'] = json_decode($item['specs'], true);
    }

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>