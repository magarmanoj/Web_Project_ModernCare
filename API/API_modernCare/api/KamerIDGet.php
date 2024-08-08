<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

$data = json_decode(file_get_contents("php://input"), true);

$blokNaam = $data['BlokNaam'];
$kamerNummer = $data['KamerNummer'];
$verdieping = $data['Verdieping'];

$sql = "SELECT KamerID FROM Kamers WHERE BlokNaam = ? AND KamerNummer = ? AND Verdieping = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $blokNaam, $kamerNummer, $verdieping);

if ($stmt->execute()) {
    $stmt->bind_result($kamerID);
    $stmt->fetch();
    if ($kamerID) {
        $response['status'] = 'ok';
        $response['KamerID'] = $kamerID;
    } else {
        $response['status'] = 'fail';
        $response['data'] = 'Kamer not found';
    }
} else {
    $response['status'] = 'fail';
    $response['data'] = $conn->error;
}

$stmt->close();
$conn->close();
deliver_JSONresponse($response);
?>
