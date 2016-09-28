<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$spraymaterial = escapehtml($_GET['spraymaterial']);

$sql = "SELECT * from tSprayMaterials WHERE sprayMaterial='".$spraymaterial."'";

$array = array(12);

$result =$dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);

$array[0] = $row['sprayMaterial'];
$array[1] = $row['TRateUnits'];
$array[2] = $row['TRateMin'];
$array[3] = $row['TRateMax'];
$array[4] = $row['TRateDefault'];
$array[5] = $row['BRateUnits'];
$array[6] = $row['BRateMin'];
$array[7] = $row['BRateMax'];
$array[8] = $row['BRateDefault'];
$array[9] = $row['REI_HRS'];
$array[10] = $row['PPE'];
$array[11] = $row['active'];

echo json_encode($array);

?>
