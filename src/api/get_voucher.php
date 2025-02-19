<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Keine ID angegeben']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM vouchers WHERE id = ?');
    $stmt->execute([$id]);
    $voucher = $stmt->fetch();
    
    if (!$voucher) {
        http_response_code(404);
        echo json_encode(['error' => 'Gutschein nicht gefunden']);
        exit;
    }
    
    echo json_encode($voucher);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}