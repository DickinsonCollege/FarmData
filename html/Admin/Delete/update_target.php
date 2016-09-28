<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$target = escapehtml($_GET['target']);

$sql = "SELECT * from targets where targetName = '".$target."'";

$array = array(3);
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);

$array[0] = $row['targetName'];
$array[1] = $row['prefix'];
$array[2] = $row['active'];

echo json_encode($array);

?>
