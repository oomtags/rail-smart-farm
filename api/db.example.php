<?php
// คัดลอกไฟล์นี้เป็น db.php แล้วใส่ค่า credentials จริง
define('DB_HOST', 'localhost');        // เช่น sql206.infinityfree.com
define('DB_USER', 'root');             // MySQL username
define('DB_PASS', '');                 // MySQL password
define('DB_NAME', 'smart_farm_db');    // ชื่อ database

$pdo = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
    DB_USER,
    DB_PASS,
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);
