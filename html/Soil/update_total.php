<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$seedDate = escapehtml($_GET['seeddate']);
$fieldID = escapehtml($_GET['fieldID']);


$sql = "SELECT size FROM field_GH where fieldID='".$fieldID."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
$size = $row['size'];

$sql = "SELECT area_seeded FROM coverSeed_master WHERE fieldID='".$fieldID."' AND seedDate='".$seedDate."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
$area_seeded = $row['area_seeded'];

echo $size * $area_seeded / 100;
?>

