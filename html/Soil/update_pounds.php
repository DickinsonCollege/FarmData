<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "SELECT length from field_GH where fieldID='".escapehtml($_GET['fieldID'])."'";
$result = mysql_query($sql);
$row1 = mysql_fetch_array($result);
echo (float)$row1['length'];
mysql_close();
?>

