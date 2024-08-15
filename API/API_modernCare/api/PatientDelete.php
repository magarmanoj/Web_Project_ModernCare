<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

$data = json_decode(file_get_contents("php://input"), true);
$patientID = $data['PatiëntID'];

if (!isset($patientID)) {
    $response = [
        'status' => 'fail',
        'data' => 'Invalid input'
    ];
    deliver_JSONresponse($response);
    exit;
}

$sql = "delete from Patiënten where PatiëntID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientID);

if ($stmt->execute()) {
    $response['status'] = 'ok';
    $response['data'] = 'Patient deleted successfully';
} else {
    $response['status'] = 'fail';
    $response['data'] = $stmt->error;
}

$stmt->close();
$conn->close();
deliver_JSONresponse($response);
?>
