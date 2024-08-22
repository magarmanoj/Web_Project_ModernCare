<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');  // Added OPTIONS to support preflight
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 1000');
header('Content-Type: application/json'); // Ensure the output is in JSON format

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// Enable error reporting for debugging (Disable in production environment)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle preflight requests for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0); // Exit with a 200 response for preflight requests
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => 'Invalid request method', 'status' => 'fail']));
}

// Retrieve POST data
$postvars = json_decode(file_get_contents('php://input'), true);
if (!$postvars) {
    die(json_encode(['error' => 'Invalid input', 'status' => 'fail']));
}

$kamerID = isset($postvars['KamerID']) ? intval($postvars['KamerID']) : null;
$status = isset($postvars['Status']) ? intval($postvars['Status']) : null;
$ongevalID = isset($postvars['OngevalID']) ? intval($postvars['OngevalID']) : null;
$verpleegsterID = isset($postvars['VerpleegsterID']) ? intval($postvars['VerpleegsterID']) : null;

// Validate input
if ($kamerID === null || $status === null || $ongevalID === null || $verpleegsterID === null) {
    die(json_encode(['error' => 'Missing parameters', 'status' => 'fail']));
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO VerzoekNotities (KamerID, Status, OngevalID, VerpleegsterID) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die(json_encode(['error' => 'Prepared Statement failed on prepare', 'errNo' => $conn->errno, 'mysqlError' => $conn->error, 'status' => 'fail']));
}

// Bind parameters
if (!$stmt->bind_param("iiii", $kamerID, $status, $ongevalID, $verpleegsterID)) {
    die(json_encode(['error' => 'Prepared Statement bind failed', 'errNo' => $stmt->errno, 'mysqlError' => $stmt->error, 'status' => 'fail']));
}

// Execute statement
if (!$stmt->execute()) {
    die(json_encode(['error' => 'Prepared Statement failed on execute', 'errNo' => $stmt->errno, 'mysqlError' => $stmt->error, 'status' => 'fail']));
}

$stmt->close();
$conn->close();
echo json_encode(['data' => 'ok', 'message' => 'Record added successfully', 'status' => 'ok']);
?>
