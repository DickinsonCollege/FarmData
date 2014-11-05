<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "Select distinct BRateUnits as unit from tSprayMaterials where sprayMaterial = '".$_GET['material']."' ";
$result = mysql_query($sql);
$row1 = mysql_fetch_array($result);
echo "&nbsp;".$row1["unit"];
mysql_close();
?>

