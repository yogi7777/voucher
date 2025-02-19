<?php
require_once '../../config/database.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query('SELECT * FROM vouchers WHERE is_archived = TRUE ORDER BY issue_date DESC');
    $vouchers = $stmt->fetchAll();
    echo json_encode($vouchers);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}