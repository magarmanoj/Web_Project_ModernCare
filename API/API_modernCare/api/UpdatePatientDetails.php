<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST'); 
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// Fetch input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if all required data is present
if (!isset($input['PatiëntID']) || !isset($input['Voornaam']) || !isset($input['Achternaam']) || 
    !isset($input['Geslacht']) || !isset($input['BlokNaam']) || 
    !isset($input['KamerNummer']) || !isset($input['Verdieping'])) {
    $response['code'] = 6; // Custom error code for missing parameters
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = 'Missing required parameters';
    deliver_JSONresponse($response);
    exit;
}

// Assign input data to variables
$patiëntID = $input['PatiëntID'];
$voornaam = $input['Voornaam'];
$achternaam = $input['Achternaam'];
$geslacht = $input['Geslacht'];
$blokNaam = $input['BlokNaam'];
$kamerNummer = $input['KamerNummer'];
$verdieping = $input['Verdieping'];

// Begin transaction
$conn->begin_transaction();

try {
    // Update patient details
    $sql = "UPDATE Patiënten 
            SET Voornaam = ?, Achternaam = ?, Geslacht = ? 
            WHERE PatiëntID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $voornaam, $achternaam, $geslacht, $patiëntID);
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    // Find the KamerID based on BlokNaam, KamerNummer, and Verdieping
    $sql = "SELECT KamerID FROM Kamers WHERE BlokNaam = ? AND KamerNummer = ? AND Verdieping = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $blokNaam, $kamerNummer, $verdieping);
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $stmt->store_result();
    
    if ($stmt->num_rows == 0) {
        throw new Exception("Kamer not found with given details.");
    }
    
    $stmt->bind_result($kamerID);
    $stmt->fetch();

    // Update the Kamer_Patiënt association
    $sql = "UPDATE Kamer_Patiënt SET KamerID = ? WHERE PatiëntID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $kamerID, $patiëntID);
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    // Commit transaction
    $conn->commit();

    // Send success response
    $response['code'] = 1; // Success code
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = 'Patient details updated successfully';
    deliver_JSONresponse($response);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $response['code'] = 7; // General error code
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = $e->getMessage();
    deliver_JSONresponse($response);
}

// Close the connection
$stmt->close();
$conn->close();
?>
