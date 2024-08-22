<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define('INDEX', true);
require 'inc/dbcon.php';
require 'inc/base.php';

// SQL query to get data only from OngevalType
$sql = "SELECT 
    k.KamerID, 
    k.KamerNummer, 
    o.Prioriteit,
    o.Time,
    o.ID as OngevalID,
    p.Voornaam AS PatientVoornaam, 
    p.Achternaam AS PatientAchternaam, 
    p.Leeftijd, 
    p.Geslacht, 
    p.OpnameDatum, 
    p.OntslagDatum,
    IFNULL(v.Status, 0) AS Status,
    v.Beschrijving AS Beschrijving,
    v.ID as VerzoekID,
    u.username AS CurrentUser,
    vg.VerpleegsterID,
    vg.Voornaam AS VerpleegsterVoornaam,
    vg.Achternaam AS VerpleegsterAchternaam,
    vg.Specialiteit
FROM OngevalType o 
LEFT JOIN Patiënten p ON o.PatiëntID = p.PatiëntID  
LEFT JOIN Kamers k ON p.KamerID = k.KamerID
LEFT JOIN VerzoekNotities v ON o.ID = v.OngevalID
LEFT JOIN Users u ON u.VerpleegsterID = v.VerpleegsterID
LEFT JOIN Verpleegsters vg ON v.VerpleegsterID = vg.VerpleegsterID
ORDER BY 
    CASE o.Prioriteit 
        WHEN 'hoog' THEN 1 
        WHEN 'middel' THEN 2 
        WHEN 'laag' THEN 3 
        ELSE 4 
    END";

$result = $conn->query($sql);

if (!$result) {
    $response['code'] = 7;
    $response['status'] = 'fail';
    $response['data'] = $conn->error;
    deliver_JSONresponse($response);
}

$response['data'] = getJsonObjFromResult($result); // -> fetch_all(MYSQLI_ASSOC)
$result->free();
$conn->close();
deliver_JSONresponse($response);
?>
