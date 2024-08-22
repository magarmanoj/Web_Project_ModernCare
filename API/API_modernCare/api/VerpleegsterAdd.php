<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';


$data = json_decode(file_get_contents("php://input"), true);

// Add Verpleegster
$voornaam = $data['Voornaam'];
$achternaam = $data['Achternaam'];
$telefoonnummer = $data['Telefoonnummer'];
$email = $data['Email'];
$specialiteit = $data['Specialiteit'];

$sql = "INSERT INTO Verpleegsters (Voornaam, Achternaam, Telefoonnummer, Email, Specialiteit) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Error preparing statement for Verpleegster: " . $conn->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error preparing statement for Verpleegster';
    echo json_encode($response);
    exit;
}

$stmt->bind_param("sssss", $voornaam, $achternaam, $telefoonnummer, $email, $specialiteit);

if (!$stmt->execute()) {
    error_log("Error executing statement for Verpleegster: " . $stmt->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error executing statement for Verpleegster';
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

$verpleegsterID = $stmt->insert_id; 
$stmt->close();

// Add User
$username = $data['username'];
$wachtwoord = $data['wachtwoord']; ; 

$sql = "INSERT INTO Users (username, wachtwoord, VerpleegsterID) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Error preparing statement for User: " . $conn->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error preparing statement for User';
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ssi", $username, $wachtwoord, $verpleegsterID);

if (!$stmt->execute()) {
    error_log("Error executing statement for User: " . $stmt->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error executing statement for User';
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

$userID = $stmt->insert_id; 
$stmt->close();

$sql = "UPDATE Verpleegsters SET UserID = ? WHERE VerpleegsterID = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Error preparing statement for updating Verpleegster: " . $conn->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error preparing statement for updating Verpleegster';
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ii", $userID, $verpleegsterID);

if (!$stmt->execute()) {
    error_log("Error executing statement for updating Verpleegster: " . $stmt->error);
    $response['status'] = 'fail';
    $response['data'] = 'Error executing statement for updating Verpleegster';
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

$stmt->close();
$conn->close();

$response = [
    'status' => 'ok',
    'message' => 'Verpleegster and User added successfully',
    'VerpleegsterID' => $verpleegsterID,
    'UserID' => $userID
];

echo json_encode($response);
?>
