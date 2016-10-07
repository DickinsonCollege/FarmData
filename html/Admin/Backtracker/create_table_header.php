<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$tableName = escapehtml($_GET['tableName']);
$farm = $_SESSION['db'];

// tableSize = number of fields in one row of table
$sql = "SELECT count(*) FROM information_schema.columns WHERE table_schema='".$farm.
          "' AND table_name='".$tableName."'";
$result = $dbcon->query($sql);
$tableSize = $result->fetch(PDO::FETCH_NUM);
$tableSize = $tableSize[0];

// numRows = number of rows in the table
$sql = "SELECT column_name FROM information_schema.columns WHERE table_schema = '".$farm.
          "' AND table_name = '".$tableName."'";
$result = $dbcon->query($sql);

// put field names into array, first column in array is for the primary key values
$array = array($tableSize);
$array[0] = "primaryKeyColumn";
$i = 1;
while ($row = $result->fetch(PDO::FETCH_NUM)) {
	if ($row[0] === "id" || $row[0] === "username" || $row[0] === "annual" || $row[0] === "lastHarvest") {
		//Nothing; don't create columns for these attributes
	} else if ($row[0] === 'bedft' && !$_SESSION['bedft']) {
		$array[$i] = 'beds';
		$i++;
        } else if ($row[0] == "hours" && !$_SESSION['labor']) {
             // nothing: not tracking labor
/*
        } else if ($row[0] == "flats") {
		$array[$i] = 'trays';
		$i++;
        } else if ($row[0] == "cellsFlat") {
		$array[$i] = 'cells/tray';
		$i++;
*/
        } else {
		$array[$i] = $row[0];
		$i++;
	}
}

echo json_encode($array);

?>
