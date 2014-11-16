<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$inunit = escapehtml($_GET['unit']);
$harvestDate = escapehtml($_GET['harvestDate']);

$sql = "SELECT sum(yield) as yield, unit FROM harvested WHERE crop='".$crop."' AND hardate='".
   $harvestDate."' group by unit";
$result = mysql_query($sql);
if ($row = mysql_fetch_array($result)) {
   $yield = $row['yield'];
   $unit = $row['unit'];
} else {
   $yield = 0;
   $sql = "select units from plant where crop='".$crop."'";
   $result = mysql_query($sql);
   if (mysql_num_rows($result) == 0) {
      $yield = -1;
      $defunit = null;
   } else {
      $defunit = $row['units'];
   }
}

$yieldUnitArray = array();
if ($yield == -1) {
   $yieldUnitArray[0] = -1;
   $yieldUnitArray[1] = $inunit;
} else if ($yield == 0) {
   $yieldUnitArray[0] = 0;
   $yieldUnitArray[1] = $inunit;
} else {
   $sql = "SELECT conversion FROM units WHERE crop='".$crop."' AND unit='".$inunit."'";
   $result = mysql_query($sql);
   if (mysql_num_rows($result) == 0) {
      $yieldUnitArray[0] = $yield;
      $yieldUnitArray[1] = $unit;
   } else {
      $row = mysql_fetch_array($result);
      $conversion = $row[0];
      $yieldUnitArray[0] = $yield * $conversion;
      $yieldUnitArray[1] = $inunit;
   }
}

echo json_encode($yieldUnitArray);

mysql_close();
?>
