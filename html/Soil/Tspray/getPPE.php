<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$material = escapehtml($_GET['material']);

$sql = "SELECT PPE FROM tSprayMaterials WHERE sprayMaterial='".$material."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
echo $row['PPE'];

?>
