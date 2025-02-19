<?php
require_once '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['ids'] ?? [];

try {
    if (empty($ids)) {
        throw new Exception('Keine IDs angegeben');
    }

    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("UPDATE vouchers SET is_archived = TRUE WHERE id IN ($placeholders)");
    $success = $stmt->execute($ids);

    echo json_encode(['success' => $success]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}