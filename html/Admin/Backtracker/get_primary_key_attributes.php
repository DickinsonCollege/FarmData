<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$tableName = escapehtml($_GET['tableName']);
$farm = $_SESSION['db'];

// Get # of primary keys
$sql = "SELECT count(column_name) as num FROM information_schema.columns WHERE table_schema='".$farm.
           "' AND table_name='".$tableName."' AND column_key='pri'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
$numRows = $row['num'];

// Get column of primary keys
$sql = "SELECT column_name FROM information_schema.columns WHERE table_schema='".$farm.
           "' AND table_name='".$tableName."' AND column_key='pri'";
$result = $dbcon->query($sql);

// Stringify the array 
$i = 0;
$keyString = "";

while ($row = $result->fetch(PDO::FETCH_NUM)) {
	$keyString .= $row[0];
	if ($i+1 < $numRows) {
		$keyString .= ", ";
	}
	$i++;
}

echo json_encode($keyString);

?>
