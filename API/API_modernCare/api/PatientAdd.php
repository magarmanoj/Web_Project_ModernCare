<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

$data = json_decode(file_get_contents("php://input"), true);

$voornaam = $data['Voornaam'];
$achternaam = $data['Achternaam'];
$leeftijd = $data['Leeftijd'];
$geslacht = $data['Geslacht'];
$opnameDatum = $data['OpnameDatum'];
$ontslagDatum = $data['OntslagDatum'];
$kamerID = $data['KamerID'];

$sql = "insert into Patiënten (Voornaam, Achternaam, Leeftijd, Geslacht, OpnameDatum, OntslagDatum, KamerID) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisssi", $voornaam, $achternaam, $leeftijd, $geslacht, $opnameDatum, $ontslagDatum, $kamerID);

if ($stmt->execute()) {
    $response['status'] = 'ok';
    $response['data'] = 'Patient added successfully';
} else {
    $response['status'] = 'fail';
    $response['data'] = $conn->error;
}

$stmt->close();
$conn->close();
deliver_JSONresponse($response);
?>
