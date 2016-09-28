<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$unit = escapehtml($_GET['unit']);

$sql = "SELECT default_unit FROM units WHERE crop='".$crop."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_NUM);
$defaultUnit = $row[0];

$sql = "SELECT conversion FROM units WHERE crop='".$crop."' AND unit='".$unit."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_NUM);
$conversion = $row[0];

$array = array($defaultUnit, $conversion);
echo json_encode($array);

?>
