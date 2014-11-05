<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$tableName = escapehtml($_GET['tableName']);
$farm = $_SESSION['db'];

// Get column of primary keys
$sql = "SELECT column_name
	FROM information_schema.columns
	WHERE table_schema='".$farm."' AND table_name='".$tableName."' AND column_key='pri'";
$result = mysql_query($sql);

// Stringify the array 
$numRows = mysql_num_rows($result);
$i = 0;
$keyString = "";

while ($row = mysql_fetch_row($result)) {
	$keyString .= $row[0];
	if ($i+1 < $numRows) {
		$keyString .= ", ";
	}
	$i++;
}

echo json_encode($keyString);

mysql_close();
?>
