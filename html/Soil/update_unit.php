<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "Select BRateUnits as unit from tSprayMaterials where sprayMaterial = '".
   escapehtml($_GET['material'])."' ";
$result = mysql_query($sql);
$row1 = mysql_fetch_array($result);
echo $row1["unit"]."(S)";
mysql_close();
?>

