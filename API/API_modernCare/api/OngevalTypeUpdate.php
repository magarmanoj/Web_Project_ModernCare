<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST'); // gebruik hier het meest toepasselijke HTTP verb. POST of PUT zijn hier ok
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// update ongeval
if(!$stmtproject = $conn->prepare("update Ongeval set Proioriteit = ? WHERE ID =?")){
    die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
}

if(!$stmtproject -> bind_param("si", htmlentities($postvars['Proioriteit']), $postvars['ID'])){
    die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
}
$stmtproject -> execute();

if($conn->affected_rows == 0) {
    $stmtproject -> close();
    die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
}
$stmtproject -> close();
die('{"data":"ok","message":"Record added successfully","status":"ok"}');
?>