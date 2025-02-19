<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare('UPDATE vouchers SET remarks = ? WHERE id = ?');
    $success = $stmt->execute([
        $data['remarks'],
        $data['id']
    ]);

    echo json_encode(['success' => $success]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}