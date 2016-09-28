<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "Select BRateUnits as unit from tSprayMaterials where sprayMaterial = '".
   escapehtml($_GET['material'])."' ";
$result = $dbcon->query($sql);
$row1 = $result->fetch(PDO::FETCH_ASSOC);
echo $row1["unit"]."(S)";
?>

