<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

session_start();
require_once __DIR__ . '/db.php';

$data     = json_decode(file_get_contents('php://input'), true) ?? [];
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(['ok' => false, 'msg' => 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, username, email, password, role FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['ok' => false, 'msg' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
    exit;
}

// Update last_login
$pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);

$_SESSION['user_id']  = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role']     = $user['role'];

echo json_encode([
    'ok'       => true,
    'username' => $user['username'],
    'email'    => $user['email'],
    'role'     => $user['role'],
]);
