<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$tableName = escapehtml($_GET['tableName']);
$primaryKey = escapehtml($_GET['primaryKey']);
$primaryKeyValue = escapehtml($_GET['primaryKeyValue']);

$sql = "DELETE FROM ".$tableName." 
	WHERE (".$primaryKey.")=(".$primaryKeyValue.")"; 

$result = mysql_query($sql) or die(mysql_error.$tableName);

if (!$result) {
	echo "<script>alert('Unable to delete row from table ".$tableName."!')</script>";
} else {
	echo "<script>alert('Successfully deleted row from table ".$tableName."!')</script>";
}

mysql_close();
?>
