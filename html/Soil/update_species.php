<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$fieldID = escapehtml($_GET['fieldID']);
$seedDate = escapehtml($_GET['seedDate']);

$sql = "SELECT crop
	FROM coverSeed natural join coverSeed_master
	WHERE fieldID='".$fieldID."' AND seedDate ='".$seedDate."'";

$result = $dbcon->query($sql);

$CropNames = array();
$count = 0;
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	$cropNames[$count] = $row['crop'];
	$count++;
}

echo json_encode($cropNames);

?>
