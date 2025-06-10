<?php
// config/database.php

global $conn;

$host = "localhost"; // atau alamat IP server database kamu
$db_name = "login_jwr";
$username_db = "root"; // username database kamu
$password_db = ""; // password database kamu (kosongkan jika tidak ada)

try {
    $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $conn = new PDO($dsn, $username_db, $password_db, $options);
    // echo "Koneksi sukses!"; // Hapus atau beri komentar setelah tes
} catch(PDOException $exception) {
    echo "Koneksi error: " . $exception->getMessage();
}
?>