<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (empty($_SESSION['user_id'])) {
    echo json_encode(['ok' => false]);
    exit;
}

echo json_encode([
    'ok'       => true,
    'username' => $_SESSION['username'],
    'role'     => $_SESSION['role'],
]);
