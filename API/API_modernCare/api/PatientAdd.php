<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// Decode the input data
$data = json_decode(file_get_contents("php://input"), true);

$voornaam = $data['Voornaam'];
$achternaam = $data['Achternaam'];
$leeftijd = $data['Leeftijd'];
$geslacht = $data['Geslacht'];
$opnameDatum = $data['OpnameDatum'];
$ontslagDatum = $data['OntslagDatum'];
$kamerID = $data['KamerID'];

// Insert patient into Patiënten table
$sql = "INSERT INTO Patiënten (Voornaam, Achternaam, Leeftijd, Geslacht, OpnameDatum, OntslagDatum, KamerID) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisssi", $voornaam, $achternaam, $leeftijd, $geslacht, $opnameDatum, $ontslagDatum, $kamerID);

if ($stmt->execute()) {
    // Get the last inserted patient ID
    $PatiëntID = $stmt->insert_id;
    
    // Now assign the patient to the room
    $stmt->close();

    $sql = "INSERT INTO Kamer_Patiënt (KamerID, PatiëntID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $kamerID, $PatiëntID);

    if ($stmt->execute()) {
        $response = [
            'status' => 'ok',
            'data' => 'Patient added and assigned to room successfully'
        ];
    } else {
        $response = [
            'status' => 'fail',
            'data' => $stmt->error
        ];
    }
} else {
    $response = [
        'status' => 'fail',
        'data' => $stmt->error
    ];
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>