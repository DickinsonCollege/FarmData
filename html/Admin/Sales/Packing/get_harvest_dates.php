<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);

$sql = "SELECT AUTO_INCREMENT 
	FROM INFORMATION_SCHEMA.TABLES 
	WHERE TABLE_SCHEMA=DATABASE() 
	AND TABLE_NAME='harvested'";

$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$lastHarvest = $row['AUTO_INCREMENT'];

$datesArray = array();
$count = 0;

do {
	$sql = "SELECT * FROM harvested WHERE id=".$lastHarvest." AND crop='".$crop."'";
	$result = mysql_query($sql);

	if (mysql_num_rows($result) > 0) {
		$dateSQL = "SELECT hardate FROM harvested WHERE id=".$lastHarvest;
		$dateResult = mysql_query($dateSQL);
		$dateRow = mysql_fetch_array($dateResult);
		if ($dateRow[0] != $datesArray[$count - 1]) {
			$datesArray[$count] = $dateRow[0];
			$count++;
		}
	}

	$lastHarvest--;
} while ($count < 3 && $lastHarvest > 0);

echo json_encode($datesArray);

mysql_close();
?>
