<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST'); // gebruik hier het meest toepasselijke HTTP verb. POST of PUT zijn hier ok
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true);
// --- Step 0 : connect to db
require 'inc/dbcon.php';
require 'inc/base.php';

// add medewerkers
if(!$stmt = $conn->prepare("insert into Verpleegsters (Voornaam, Achternaam, Telefoonnummer, Email, Specialiteit) values (?,?,?,?,?)")){
    die('{"error":"Prepared Statement failed on prepare","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
}

if(!$stmt -> bind_param("sssss", htmlentities($postvars['Voornaam']), $postvars['Achternaam'], $postvars['Telefoonnummer'], $postvars['Email'], $postvars['specialisatie'])){
    die('{"error":"Prepared Statement bind failed on bind","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
}
$stmt -> execute();

if($conn->affected_rows == 0) {
    // add failed
    $stmt -> close();
    die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":"' . json_encode($conn -> errno) .'","mysqlError":"' . json_encode($conn -> error) .'","status":"fail"}');
}
$stmt -> close();
die('{"data":"ok","message":"Record added successfully","status":"ok"}');
?>