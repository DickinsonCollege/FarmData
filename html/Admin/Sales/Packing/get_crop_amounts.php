<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$inunit = escapehtml($_GET['unit']);
$harvestDate = escapehtml($_GET['harvestDate']);

$sql = "SELECT sum(yield) as yield, unit FROM harvested WHERE crop='".$crop."' AND hardate='".
   $harvestDate."' group by unit";
$result = $dbcon->query($sql);
if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $yield = $row['yield'];
   $unit = $row['unit'];
} else {
   $yield = 0;
   $sql = "select units from plant where crop='".$crop."'";
   $result = $dbcon->query($sql);
   if (!$result->fetch(PDO::FETCH_ASSOC)) {
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
   $result = $dbcon->query($sql);
   if (!$row = $result->fetch(PDO::FETCH_NUM)) {
      $yieldUnitArray[0] = $yield;
      $yieldUnitArray[1] = $unit;
   } else {
      $conversion = $row[0];
      $yieldUnitArray[0] = $yield * $conversion;
      $yieldUnitArray[1] = $inunit;
   }
}

echo json_encode($yieldUnitArray);

?>
