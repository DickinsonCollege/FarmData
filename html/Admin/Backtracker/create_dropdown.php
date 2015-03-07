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
// generation
} else if ($fieldName === "gen") {
   if ($tableName === "transferred_to") {
      $sql = "select distinct gen from gh_seeding order by gen";
   } else if ($tableName === "harvested") {
      $sql = "select distinct gen from (select distinct gen from dir_planted union ".
        "select distinct gen from transferred_to) as tmp order by gen";
   } else {
      $sql = "select 1 union select 2 union select 3 union select 4 union select 5".
          " union select 6 union select 7 union select 8 union select 9 union select 10";
   }
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
