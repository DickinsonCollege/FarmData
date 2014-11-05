<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];

// Tables that have a username column
$usernameTables = array("dir_planted", "harvested", "gh_seeding", "transferred_to");

$tableName = escapehtml($_GET['tableName']);
$tableSize = escapehtml($_GET['tableSize']);

$fields_array = json_decode($_GET['fields_array']);
$values_array = json_decode($_GET['values_array']);
$user = $_SESSION['username'];

if (in_array($tableName, $usernameTables)) {
	$columns = "username, ";
	$values = "'".$user."', ";
} else {
	$columns = "";
	$values = "";
}

$fieldInd = -1;
$bedsInd = -1;
for ($i = 1; $i < $tableSize+1; $i++) {
        if ($fields_array[$i] === 'fieldID') {
           $fieldInd = $i - 1;
        }
        if ($fields_array[$i]==='beds' && !$_SESSION['bedft']) {
	   $columns .= 'bedft';
           $bedsInd = $i - 1;
        } else {
	   $columns .= escapehtml($fields_array[$i]);
        }
	if ($i+1 < $tableSize+1) {
		$columns .= ", ";
	}
}

for ($i = 0; $i < $tableSize; $i++) {
        if ($i == $bedsInd && !$_SESSION['bedft']) {
           $fieldID = escapehtml($values_array[$fieldInd]);
           $sql = "select length from field_GH where fieldID = '".$fieldID."'";
           $result = mysql_query($sql);
           echo mysql_error();
           $row = mysql_fetch_array($result);
           $length = $row['length'];
           $beds = escapehtml($values_array[$i]);
	   $values .= "'".($length * $beds)."'";
        } else {
	   $values .= "'".escapehtml($values_array[$i])."'";
        }
	if ($i+1 < $tableSize) {
		$values .= ", ";
	}
}

$sql = "INSERT INTO ".$tableName."
	(".$columns.")
	VALUES (".$values.")";

$result = mysql_query($sql);

echo mysql_error();

mysql_close();
?>
