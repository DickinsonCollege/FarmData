<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$material = escapehtml($_GET['material']);

$sql = "SELECT REI_HRS FROM tSprayMaterials WHERE sprayMaterial='".$material."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

echo $row['REI_HRS'];

?>
