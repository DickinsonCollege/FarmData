<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$target = escapehtml($_GET['target']);

$sql = "SELECT * from targets where targetName = '".$target."'";

$array = array(3);
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$array[0] = $row['targetName'];
$array[1] = $row['prefix'];
$array[2] = $row['active'];

echo json_encode($array);

mysql_close();
?>
