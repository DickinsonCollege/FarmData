<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$seedDate = escapehtml($_GET['seeddate']);
$fieldID = escapehtml($_GET['fieldID']);

//$sql = "SELECT size,area_seeded from field_GH,coverSeed 
//	WHERE seedDate ='".$_GET['seeddate']."'  and field_GH.fieldID ='".$fieldID."'";

$sql = "SELECT size FROM field_GH where fieldID='".$fieldID."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$size = $row[0];

$sql = "SELECT area_seeded FROM coverSeed_master WHERE fieldID='".$fieldID."' AND seedDate='".$seedDate."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$area_seeded = $row[0];

echo $size * $area_seeded / 100;

mysql_close();
?>

