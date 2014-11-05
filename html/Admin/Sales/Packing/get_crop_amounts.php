<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$harvestDate = escapehtml($_GET['harvestDate']);

$sql = "SELECT yield, unit FROM harvested WHERE crop='".$crop."' AND hardate='".$harvestDate."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$yield = $row['yield'];
$unit = $row['unit'];

$yieldUnitArray = array();

$sql = "SELECT conversion FROM units WHERE crop='".$crop."' AND unit='POUND'";
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0) {
   $yieldUnitArray[0] = $yield;
   $yieldUnitArray[1] = $unit;
} else {
   $row = mysql_fetch_array($result);
   $conversion = $row[0];
   $yieldUnitArray[0] = $yield * $conversion;
   $yieldUnitArray[1] = 'POUND';
}

echo json_encode($yieldUnitArray);

mysql_close();
?>
