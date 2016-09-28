<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$material = escapehtml($_GET['material']);

$sql = "SELECT REI_HRS FROM tSprayMaterials WHERE sprayMaterial='".$material."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);

echo $row['REI_HRS'];

?>
