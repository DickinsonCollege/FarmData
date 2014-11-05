<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$tableName = escapehtml($_GET['tableName']);
$farm = $_SESSION['db'];

// tableSize = number of columns in one row of table + 1 for primary key column
$sql = "SELECT count(*)
	FROM information_schema.columns
	WHERE table_name='".$tableName."'";
$result = mysql_query($sql);
$tableSize = mysql_fetch_array($result);
$tableSize = $tableSize[0] + 1;

// numRows = number of rows in the table
$sql = "SELECT count(*) 
	FROM ".$tableName; 
$result = mysql_query($sql);
$numRows = mysql_fetch_array($result);
$numRows = $numRows[0];

// keyString = string of primary key column names
$sql = "SELECT column_name 
	FROM information_schema.columns 
	WHERE table_schema='".$farm."' AND table_name='".$tableName."' AND column_key='pri'";
$result = mysql_query($sql);
$keyString = "";
$j = 1;
while ($row = mysql_fetch_array($result)) {
	$keyString .= $row[0];
	if ($j < mysql_num_rows($result)) {
		$keyString .= ", ";
	}
	$j++;
}

// keyString = the values of the primary keys for each row of the table
$sql = "SELECT ".$keyString.
	" FROM ".$tableName;
$keyResult = mysql_query($sql);

// result = all of the tuples in the table
$sql = "SELECT *
	FROM ".$tableName;
$result = mysql_query($sql);

// Put tuples formatted in tables into array
// First element in each row is the value of the primary key attributes for that corresponding row
$theTable = array($numRows);
$i = 0;
while ($row = mysql_fetch_row($result)) {
	$theRow = array($tableSize);

	// Creates primary key values string, puts into first element of array
	$primaryKeyValues = "";
	$keyRow = mysql_fetch_row($keyResult);
	for ($k = 0; $k < count($keyRow); $k++) {
		$primaryKeyValues .= "'".$keyRow[$k]."'";
		if ($k+1 < count($keyRow)) {
			$primaryKeyValues .= ", ";
		}
	}
	$theRow[0] = $primaryKeyValues;

	// Gets and assigns the rest of the values in the array
	for ($j = 1; $j < $tableSize; $j++) {
		$theRow[$j] = $row[$j - 1];
	}
	$theTable[$i] = $theRow;
	$i++;
}

echo json_encode($theTable);

mysql_close();
?>
