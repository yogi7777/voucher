<?php
require_once '../../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare('INSERT INTO amounts (value) VALUES (?)');
    $success = $stmt->execute([$data['value']]);
    
    echo json_encode(['success' => $success]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}