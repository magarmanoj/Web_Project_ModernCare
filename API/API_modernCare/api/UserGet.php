<?php
header('Content-Type: application/json'); // Ensure the content type is set to JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php'; // Make sure your database connection is correct.
require 'inc/base.php'; // This file should not output anything.

// Retrieve POST data
$username = $_POST['username'] ?? null;
$wachtwoord = $_POST['wachtwoord'] ?? null;


$stmt = $conn->prepare("SELECT * FROM Users WHERE username = ? AND wachtwoord = ?");

// Check if prepare was successful
if (!$stmt) {
    echo json_encode(['error' => 'Database prepare failed.', 'status' => 'fail']);
    exit;
}

// Bind parameters and execute
if (!$stmt->bind_param("ss", $username, $wachtwoord )) {
    echo json_encode(['error' => 'Database bind failed.', 'status' => 'fail']);
    exit;
}

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Database execute failed.', 'status' => 'fail']);
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['error' => 'No user found with the provided credentials.', 'status' => 'fail']);
} else {
    // Fetch the user data
    $user = $result->fetch_assoc();
    // Here you would normally verify the password, e.g.:
    // if (password_verify($wachtwoord, $user['wachtwoord'])) {
    if ($wachtwoord === $user['wachtwoord']) { // Use this line only if you are not hashing passwords
        echo json_encode(['data' => 'ok', 'message' => 'Login successful', 'status' => 'ok']);
    } else {
        echo json_encode(['error' => 'Invalid password.', 'status' => 'fail']);
    }
}

$stmt->close();
?>