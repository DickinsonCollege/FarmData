<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$fieldName = escapehtml($_GET['fieldName']);
$tableName = escapehtml($_GET['tableName']);

$sql = "";
// Crop
if ($fieldName === "crop") {
	$sql = "SELECT crop FROM plant WHERE active=1";
// FieldID
} else if ($fieldName === "fieldID") {
	$sql = "SELECT fieldID FROM field_GH WHERE active=1";
// Cell
} else if ($fieldName === "cellsFlat") {
	$sql = "SELECT cells FROM flat"; 
}

if ($fieldName === "rowsBed") {
	$array = array(1, 2, 3, 4, 5, 7);
} else if 
	(($fieldName === "seedDate" && $tableName === "transferred_to") ||
	($fieldName === "unit" && $tableName === "harvested") ||
	($fieldName === "fieldID" && $tableName === "harvested")) {
		$array = array();
} else {
	$result = mysql_query($sql);
	$array = array(mysql_num_rows($result));
	$i = 0;
	while ($row = mysql_fetch_array($result)) {
		$array[$i] = $row[0];
		$i++;
	}
}

echo json_encode($array);

mysql_close();
?>
