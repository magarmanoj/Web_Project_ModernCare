<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

$sql = "SELECT * FROM Patiënten";
$result = $conn->query($sql);

if (!$result) {
    $response['code'] = 7;
    $response['status'] = 'fail';
    $response['data'] = $conn->error;
    deliver_JSONresponse($response);
}

$response['data'] = getJsonObjFromResult($result); 
$result->free();
$conn->close();
deliver_JSONresponse($response);
?>
