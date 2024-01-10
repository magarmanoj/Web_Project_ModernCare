<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// Retrieve POST data
$username = $_POST['username'] ?? null;
$wachtwoord = $_POST['wachtwoord'] ?? null;
$username = $_POST['username'] ?? null;
echo json_encode(['data' => $username, 'message' => 'Received username', 'status' => 'ok']);



$stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
if (!$stmt) {
    echo json_encode(['error' => 'Database prepare failed.', 'status' => 'fail']);
    exit;
}

if (!$stmt->bind_param("s", $username)) {
    echo json_encode(['error' => 'Database bind failed.', 'status' => 'fail']);
    exit;
}

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Database execute failed.', 'status' => 'fail']);
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['error' => 'No user found with the provided username.', 'status' => 'fail']);
} else {
    $user = $result->fetch_assoc();
    // If you are hashing passwords, use password_verify
    // if (password_verify($wachtwoord, $user['wachtwoord'])) {
    if ($wachtwoord === $user['wachtwoord']) { // Use this line only if you are not hashing passwords
        echo json_encode(['data' => 'ok', 'message' => 'Login successful', 'status' => 'ok']);
    } else {
        echo json_encode(['error' => 'Invalid password.', 'status' => 'fail']);
    }
}

$stmt->close();
?>
