<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$date = $data['date'] ?? '';
$serial = $data['serial'] ?? '';

try {
    $where = ['is_archived = FALSE'];
    $params = [];

    if (!empty($date)) {
        $date = DateTime::createFromFormat('d.m.Y', $date)->format('Y-m-d');
        $where[] = 'issue_date = ?';
        $params[] = $date;
    }

    if (!empty($serial)) {
        $where[] = 'serial_number LIKE ?';
        $params[] = "%$serial%";
    }

    $whereClause = implode(' AND ', $where);
    $sql = "SELECT * FROM vouchers WHERE $whereClause ORDER BY issue_date DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $vouchers = $stmt->fetchAll();

    echo json_encode($vouchers);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler']);
}