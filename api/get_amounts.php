<?php
require_once '../config/database.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query('SELECT id, CAST(value AS DECIMAL(10,2)) as value, is_active FROM amounts WHERE is_active = TRUE ORDER BY value');
    $amounts = $stmt->fetchAll();
    echo json_encode($amounts);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}