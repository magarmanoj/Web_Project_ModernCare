<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST'); 
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

$sql = "SELECT p.Voornaam AS PatientVoornaam, p.Achternaam AS PatientAchternaam,
               k.BlokNaam, k.KamerNummer, k.Verdieping
        FROM Patiënten p
        JOIN Kamer_Patiënt kp ON p.PatiëntID = kp.PatiëntID
        JOIN Kamers k ON kp.KamerID = k.KamerID";

$result = $conn->query($sql);

if (!$result) {
    $response['code'] = 7;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = $conn->error;
    deliver_response($response);
}

$response['data'] = getJsonObjFromResult($result);
$result->free();
$conn->close();
deliver_JSONresponse($response);
?>
