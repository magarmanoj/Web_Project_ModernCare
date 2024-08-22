<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// Retrieve POST data
$postvars = json_decode(file_get_contents('php://input'), true);

// Validate POST data
if (!isset($postvars['ID']) || !isset($postvars['Status']) || !isset($postvars['OngevalID'])) {
    die(json_encode(['error' => 'Missing parameters', 'status' => 'fail']));
}

// Prepare SQL statement to update status
$updateStmt = $conn->prepare("UPDATE VerzoekNotities SET Status = ? WHERE ID = ?");
if (!$updateStmt) {
    die(json_encode(['error' => 'Prepared Statement failed on prepare (update)', 'errNo' => $conn->errno, 'mysqlError' => $conn->error, 'status' => 'fail']));
}

if (!$updateStmt->bind_param("ii", $postvars['Status'], $postvars['ID'])) {
    die(json_encode(['error' => 'Prepared Statement bind failed on bind (update)', 'errNo' => $updateStmt->errno, 'mysqlError' => $updateStmt->error, 'status' => 'fail']));
}

$updateStmt->execute();

if ($updateStmt->affected_rows > 0) {
    $updateStmt->close();
    
    // Prepare SQL statement to delete from OngevalType
    $deleteStmt = $conn->prepare("DELETE FROM OngevalType WHERE ID = ?");
    if (!$deleteStmt) {
        die(json_encode(['error' => 'Prepared Statement failed on prepare (delete)', 'errNo' => $conn->errno, 'mysqlError' => $conn->error, 'status' => 'fail']));
    }

    if (!$deleteStmt->bind_param("i", $postvars['OngevalID'])) {
        die(json_encode(['error' => 'Prepared Statement bind failed on bind (delete)', 'errNo' => $deleteStmt->errno, 'mysqlError' => $deleteStmt->error, 'status' => 'fail']));
    }

    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        $deleteStmt->close();
        die(json_encode(['data' => 'ok', 'message' => 'Record updated and deleted successfully', 'status' => 'ok']));
    } else {
        $deleteStmt->close();
        die(json_encode(['error' => 'No rows affected in OngevalType deletion', 'status' => 'fail']));
    }
} else {
    $updateStmt->close();
    die(json_encode(['error' => 'No rows affected in VerzoekNotities update', 'status' => 'fail']));
}
?>
