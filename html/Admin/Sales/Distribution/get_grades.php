<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$cropProd = escapehtml($_GET['cropProd']);

$sql = "SELECT distinct grade FROM inventory WHERE crop_product='".$cropProd."'";
$result = mysql_query($sql);

$grades = array();
$count = 0;

while ($row = mysql_fetch_array($result)) {
	$grades[$count] = $row[0];
	$count++;
}

echo json_encode($grades);

mysql_close()
?>
