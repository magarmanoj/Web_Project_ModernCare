<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
// --- Step 0: Connect to the database
require 'inc/dbcon.php';
require 'inc/base.php';


if (!$stmt = $conn->prepare("delete from Kamer_Patiënt  where PatiëntID = ?")) {
    die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn->errno) . '","mysqlError":"' . json_encode($conn->error) . '","status":"fail"}');
}

if (!$stmt->bind_param("i", htmlentities($postvars['PatiëntID']))) {
    die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($stmt->errno) . '","mysqlError":"' . json_encode($stmt->error) . '","status":"fail"}');
}

$stmt->execute();

if (!$stmt = $conn->prepare("delete from Patiënten where PatiëntID = ?")) {
    die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn->errno) . '","mysqlError":"' . json_encode($conn->error) . '","status":"fail"}');
}

if (!$stmt->bind_param("i", htmlentities($postvars['PatiëntID']))) {
    die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($stmt->errno) . '","mysqlError":"' . json_encode($stmt->error) . '","status":"fail"}');
}

// Execute the DELETE query
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Deletion was successful
    $stmt->close();
    die('{"data":"ok","message":"Project deleted successfully","status":"ok"}');
}
$stmt->close();
die('{"error":"No rows affected, deletion failed","status":"fail"}');
?>