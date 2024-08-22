<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

$data = json_decode(file_get_contents("php://input"), true);
$verpleegsterID = $data['VerpleegsterID'];



// Start a transaction
$conn->begin_transaction();

// Delete User associated with Verpleegster
$sql = "DELETE FROM Users WHERE VerpleegsterID = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Error preparing statement for User deletion: " . $conn->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error preparing statement for User deletion';
    echo json_encode($response);
    $conn->rollback();
    exit;
}

$stmt->bind_param("i", $verpleegsterID);

if (!$stmt->execute()) {
    error_log("Error executing statement for User deletion: " . $stmt->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error executing statement for User deletion';
    $stmt->close();
    $conn->rollback();
    echo json_encode($response);
    exit;
}

$stmt->close();

// Delete Verpleegster
$sql = "DELETE FROM Verpleegsters WHERE VerpleegsterID = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Error preparing statement for Verpleegster deletion: " . $conn->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error preparing statement for Verpleegster deletion';
    echo json_encode($response);
    $conn->rollback();
    exit;
}

$stmt->bind_param("i", $verpleegsterID);

if (!$stmt->execute()) {
    error_log("Error executing statement for Verpleegster deletion: " . $stmt->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error executing statement for Verpleegster deletion';
    $stmt->close();
    $conn->rollback();
    echo json_encode($response);
    exit;
}

$stmt->close();

// Commit transaction
$conn->commit();
$conn->close();

$response = [
    'status' => 'ok',
    'message' => 'Verpleegster and User deleted successfully'
];

echo json_encode($response);
?>
