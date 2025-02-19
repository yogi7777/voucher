<?php
require_once '../config/database.php';

header('Content-Type: application/json');

try {
    if (!isset($_FILES['csv_file'])) {
        throw new Exception('Keine Datei hochgeladen');
    }

    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, 'r');
    
    // Überspringen der Kopfzeile
    fgetcsv($handle);
    
    $stmt = $pdo->prepare('INSERT INTO vouchers (issue_date, serial_number, amount) VALUES (?, ?, ?)');
    
    $pdo->beginTransaction();
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        $date = DateTime::createFromFormat('d.m.Y', $data[0])->format('Y-m-d');
        $stmt->execute([$date, $data[1], $data[2]]);
    }
    
    $pdo->commit();
    fclose($handle);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}