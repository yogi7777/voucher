<?php
require_once '../../config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query('SELECT * FROM amounts ORDER BY value');
    $amounts = $stmt->fetchAll();
    echo json_encode($amounts);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}