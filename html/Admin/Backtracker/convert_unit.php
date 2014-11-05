<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$unit = escapehtml($_GET['unit']);

$sql = "SELECT default_unit FROM units 
	WHERE crop='".$crop."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$defaultUnit = $row[0];

$sql = "SELECT conversion FROM units 
	WHERE crop='".$crop."' 
	AND unit='".$unit."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$conversion = $row[0];

$array = array($defaultUnit, $conversion);
echo json_encode($array);

mysql_close();
?>
