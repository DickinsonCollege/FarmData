<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "SELECT length from field_GH where fieldID='".escapehtml($_GET['fieldID'])."'";
$result = $dbcon->query($sql);
$row1 = $result->fetch(PDO::FETCH_ASSOC);
echo (float)$row1['length'];
?>

