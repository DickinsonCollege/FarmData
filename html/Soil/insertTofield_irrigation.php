<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$field	= escapehtml($_GET['fieldID']);
$irrigation_device = escapehtml($_GET['irr_dev']);
$sql = "insert into field_irrigation values('".$field."', 0, '".$irrigation_device."', null)";
echo $sql;
mysql_query($sql) or die(mysql_error());
echo mysql_error();
?>
