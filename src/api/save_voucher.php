<?php
require_once '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $date = DateTime::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d');
    
    $stmt = $pdo->prepare('INSERT INTO vouchers (serial_number, amount, issue_date) VALUES (?, ?, ?)');
    $success = $stmt->execute([
        $data['serial'],
        $data['amount'],
        $date
    ]);

    echo json_encode(['success' => $success]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}