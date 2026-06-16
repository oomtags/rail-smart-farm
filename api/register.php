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
$email    = trim($data['email']    ?? '');
$password = $data['password']       ?? '';
$confirm  = $data['confirm']        ?? '';

// ── Validation ──
if (!$username || !$email || !$password || !$confirm) {
    echo json_encode(['ok' => false, 'msg' => 'กรุณากรอกข้อมูลให้ครบทุกช่อง']);
    exit;
}
if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
    echo json_encode(['ok' => false, 'msg' => 'ชื่อผู้ใช้ต้องใช้ตัวอักษร a-z, 0-9, _ (3-30 ตัว) ไม่มีช่องว่าง']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'msg' => 'รูปแบบ Email ไม่ถูกต้อง']);
    exit;
}
if (strlen($password) < 6) {
    echo json_encode(['ok' => false, 'msg' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร']);
    exit;
}
if ($password !== $confirm) {
    echo json_encode(['ok' => false, 'msg' => 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน']);
    exit;
}

// ── Check duplicate ──
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    echo json_encode(['ok' => false, 'msg' => 'ชื่อผู้ใช้หรือ Email นี้ถูกใช้งานแล้ว']);
    exit;
}

// ── Insert ──
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, "user")');
$stmt->execute([$username, $email, $hash]);

echo json_encode(['ok' => true, 'msg' => 'สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ']);
