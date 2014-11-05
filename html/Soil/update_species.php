<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$fieldID = escapehtml($_GET['fieldID']);
$seedDate = escapehtml($_GET['seedDate']);

$sql = "SELECT crop
	FROM coverSeed natural join coverSeed_master
	WHERE fieldID='".$fieldID."' AND seedDate ='".$seedDate."'";

$result = mysql_query($sql) or die(mysql_error());

$CropNames = array();
$count = 0;
while ($row = mysql_fetch_array($result)) {
	$cropNames[$count] = $row['crop'];
	$count++;
}

echo json_encode($cropNames);

mysql_close();
?>
