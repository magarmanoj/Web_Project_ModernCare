<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// Retrieve POST data
$username = trim($_POST['username'] ?? null);
$wachtwoord = trim($_POST['wachtwoord'] ?? null);

// Debugging output
echo json_encode(['data' => $username, 'entered_password' => $wachtwoord, 'stored_password' => $user['wachtwoord'], 'message' => 'Received username', 'status' => 'ok']);

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

    // Debugging output
    echo json_encode(['data' => $username, 'entered_password' => $wachtwoord, 'stored_password' => $user['wachtwoord'], 'message' => 'Received username', 'status' => 'ok']);

    if ($wachtwoord === $user['wachtwoord']) {
        echo json_encode(['data' => 'ok', 'message' => 'Login successful', 'status' => 'ok']);
    } else {
        echo json_encode(['error' => 'Invalid password.', 'status' => 'fail']);
    }
}

$stmt->close();
?>
