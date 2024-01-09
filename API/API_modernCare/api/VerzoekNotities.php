<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST'); // gebruik hier het meest toepasselijke HTTP verb. POST of PUT zijn hier ok
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
// --- Step 0: connect to db
require 'inc/dbcon.php';
require 'inc/base.php';

// add projecten
if (!$stmtproject = $conn->prepare("insert into kamers (VerzoekID, VerpleegsterID, Omschrijvigen, DatumTijdNotitie) values (?,?,?,?)")) {
    die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn->errno) . '","mysqlError":"' . json_encode($conn->error) . '","status":"fail"}');
}

if (!$stmtproject->bind_param("iiss", $postvars['VerzoekID'], $postvars['VerpleegsterID'], $postvars['Omschrijving'], $postvars['DatumTijdNotitie'])) {
    die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($stmtproject->errno) . '","mysqlError":"' . json_encode($stmtproject->error) . '","status":"fail"}');
}
$stmtproject->execute();

if ($conn->affected_rows == 0) {
    // add failed
    $stmtproject->close();
    die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":"' . json_encode($conn->errno) . '","mysqlError":"' . json_encode($conn->error) . '","status":"fail"}');
}
$stmtproject->close();
die('{"data":"ok","message":"Record added successfully","status":"ok"}');
?>