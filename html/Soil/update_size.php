<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
echo $sql = "SELECT length from field_GH where fieldID='".escapehtml($_GET['fieldID'])."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
(double)$num= $row['length'];
echo $num;
mysql_close();
?>

