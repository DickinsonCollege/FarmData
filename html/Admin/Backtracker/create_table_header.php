<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$tableName = escapehtml($_GET['tableName']);
$farm = $_SESSION['db'];

// tableSize = number of fields in one row of table
$sql = "SELECT count(*) 
	FROM information_schema.columns 
	WHERE table_schema='".$farm."' 
	AND table_name='".$tableName."'";
$result = mysql_query($sql);
$tableSize = mysql_fetch_array($result);
$tableSize = $tableSize[0];

// numRows = number of rows in the table
$sql = "SELECT column_name
	FROM information_schema.columns 
	WHERE table_schema = '".$farm."' AND table_name = '".$tableName."'";
$result = mysql_query($sql);

// put field names into array, first column in array is for the primary key values
$array = array($tableSize);
$array[0] = "primaryKeyColumn";
$i = 1;
while ($row = mysql_fetch_array($result)) {
	if ($row[0] === "id" || $row[0] === "username") {
		//Nothing; don't create id column or username column
	} else if ($row[0] === 'bedft' && !$_SESSION['bedft']) {
		$array[$i] = 'beds';
		$i++;
        } else if ($row[0] == "hours" && !$_SESSION['labor']) {
             // nothing: not tracking labor
        } else {
		$array[$i] = $row[0];
		$i++;
	}
}

echo json_encode($array);

mysql_close();
?>
