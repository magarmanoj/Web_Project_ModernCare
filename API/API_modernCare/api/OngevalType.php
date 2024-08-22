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
if (!isset($postvars['PatiëntID']) || !isset($postvars['Prioriteit'])) {
    die(json_encode(['error' => 'Missing parameters', 'status' => 'fail']));
}

// Check if the patient already has an entry
$checkStmt = $conn->prepare("SELECT Prioriteit FROM OngevalType WHERE PatiëntID = ?");
if (!$checkStmt) {
    die(json_encode(['error' => 'Prepared Statement failed on prepare (check)', 'errNo' => $conn->errno, 'mysqlError' => $conn->error, 'status' => 'fail']));
}

if (!$checkStmt->bind_param("i", $postvars['PatiëntID'])) {
    die(json_encode(['error' => 'Prepared Statement bind failed on bind (check)', 'errNo' => $checkStmt->errno, 'mysqlError' => $checkStmt->error, 'status' => 'fail']));
}

$checkStmt->execute();
$result = $checkStmt->get_result();
$existingPriority = $result->fetch_assoc()['Prioriteit'] ?? null;
$checkStmt->close();

// Compare priorities and update or insert as needed
if ($existingPriority) {
    // Priority levels in increasing importance
    $priorityLevels = ['Laag', 'Middel', 'Hoog'];
    $existingPriorityIndex = array_search($existingPriority, $priorityLevels);
    $newPriorityIndex = array_search($postvars['Prioriteit'], $priorityLevels);
    
    if ($newPriorityIndex > $existingPriorityIndex) {
        // Update priority
        $updateStmt = $conn->prepare("UPDATE OngevalType SET Prioriteit = ?, Time = NOW() WHERE PatiëntID = ?");
        if (!$updateStmt) {
            die(json_encode(['error' => 'Prepared Statement failed on prepare (update)', 'errNo' => $conn->errno, 'mysqlError' => $conn->error, 'status' => 'fail']));
        }

        if (!$updateStmt->bind_param("si", $postvars['Prioriteit'], $postvars['PatiëntID'])) {
            die(json_encode(['error' => 'Prepared Statement bind failed on bind (update)', 'errNo' => $updateStmt->errno, 'mysqlError' => $updateStmt->error, 'status' => 'fail']));
        }

        $updateStmt->execute();
        $updateStmt->close();

        die(json_encode(['data' => 'ok', 'message' => 'Record updated successfully', 'status' => 'ok']));
    } else {
        die(json_encode(['error' => 'Existing priority is higher or equal', 'status' => 'fail']));
    }
} else {
    // Insert new priority
    $insertStmt = $conn->prepare("INSERT INTO OngevalType (PatiëntID, Prioriteit, Time) VALUES (?, ?, NOW())");
    if (!$insertStmt) {
        die(json_encode(['error' => 'Prepared Statement failed on prepare (insert)', 'errNo' => $conn->errno, 'mysqlError' => $conn->error, 'status' => 'fail']));
    }

    if (!$insertStmt->bind_param("is", $postvars['PatiëntID'], $postvars['Prioriteit'])) {
        die(json_encode(['error' => 'Prepared Statement bind failed on bind (insert)', 'errNo' => $insertStmt->errno, 'mysqlError' => $insertStmt->error, 'status' => 'fail']));
    }

    $insertStmt->execute();
    $insertStmt->close();

    die(json_encode(['data' => 'ok', 'message' => 'Record added successfully', 'status' => 'ok']));
}
?>
