<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

$input = json_decode(file_get_contents('php://input'), true);

$response = [];

if (!isset($input['VerpleegsterID'])) {
    $response = [
        'code' => 7,
        'status' => 'error',
        'message' => 'VerpleegsterID is missing.'
    ];
    echo json_encode($response);
    exit;
}

$VerpleegsterID = $input['VerpleegsterID'];
$Voornaam = $input['Voornaam'];
$Achternaam = $input['Achternaam'];
$Specialiteit = $input['Specialiteit'];
$Email = $input['Email'];
$Telefoonnummer = $input['Telefoonnummer'];

$sql = "UPDATE Verpleegsters SET 
        Voornaam = ?, 
        Achternaam = ?, 
        Specialiteit = ?, 
        Email = ?, 
        Telefoonnummer = ?
        WHERE VerpleegsterID = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('sssssi', $Voornaam, $Achternaam, $Specialiteit, $Email, $Telefoonnummer, $VerpleegsterID);

    if ($stmt->execute()) {
        $response = [
            'code' => 1,
            'status' => 'success',
            'message' => 'Verpleegster updated successfully.'
        ];
    } else {
        $response = [
            'code' => 7,
            'status' => 'error',
            'message' => 'Error updating verpleegster: ' . $stmt->error
        ];
    }

    $stmt->close();
} else {
    $response = [
        'code' => 7,
        'status' => 'error',
        'message' => 'Error preparing the SQL statement: ' . $conn->error
    ];
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);