<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = $_GET['fieldID'];
$sql 		= "delete from field_irrigation where fieldID='".$fieldID."'";
mysql_query($sql) or die(mysql_error());
echo mysql_error();
?>
