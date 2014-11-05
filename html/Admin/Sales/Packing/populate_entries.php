<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$lastHarvest = $_GET['id'];

do {
   $sql = "SELECT distinct crop, units FROM harvestListItem WHERE id=".$lastHarvest;
   $result = mysql_query($sql);
   echo mysql_error();
   if (mysql_num_rows($result) == 0) {
      $lastHarvest--;
   }
} while (mysql_num_rows($result) == 0);

$dateSQL = "SELECT harDate FROM harvestList WHERE id=".$lastHarvest;
$dateResult = mysql_query($dateSQL);
$dateRow = mysql_fetch_array($dateResult);
$date = $dateRow['harDate'];

/*
$sql = "select * from targets order by targetName";
$targs = array();
$resultt = mysql_query($sql);
echo mysql_error();
while ($rowt = mysql_fetch_array($resultt)) {
   $targs[] = $rowt['targetName'];
}
*/

$harvestListArray = array();
//$i = 0;

while ($row = mysql_fetch_array($result)) {
   $totalSQL = "SELECT sum(yield) as yield, unit FROM harvested WHERE crop='".$row['crop']."' AND hardate='".$date."' group by unit";
   $totalResult = mysql_query($totalSQL);
   $totalRow = mysql_fetch_array($totalResult);
   $yield = $totalRow['yield'];
   $unit = $totalRow['unit'];
   $convert = false;
   if ($yield == null) {
      $yield = 0;
      $unit = $row['units'];
   }
   $convsql = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='POUND'";
   $convresult = mysql_query($convsql);
   if (mysql_num_rows($convresult) > 0) {
      $convert = true;
      $convrow = mysql_fetch_array($convresult);
      $conversion = $convrow[0];
      $yield = $yield * $conversion;
      $unit='POUND';
   }
   
   $rowArray = array();
/*
   for ($i = 0; $i < count($targs); $i++) {
      $rowArray[$targs[$i]] = 0;
   }
*/
   $sql = "select * from harvestListItem where crop='".$row['crop']."' AND id=".$lastHarvest;
   $resultc = mysql_query($sql);
   echo mysql_error();
   $tot = 0;
   while ($rowc = mysql_fetch_array($resultc)) {
      $targ = $rowc['target'];
      $rowArray[$targ] = $rowc['amt'];
      if ($convert) {
        $rowArray[$targ] = $rowArray[$targ] * $conversion;
      }
      $tot += $rowArray[$targ];
   }
   
/*
   if ($convert) {
      $rowArray[0] = $row['CSA'] * $conversion;
      $rowArray[1] = $row['dining'] * $conversion;
      $rowArray[2] = $row['market'] * $conversion;
      $rowArray[3] = $row['other'] * $conversion;
   } else {
      $rowArray[0] = $row['CSA'];
      $rowArray[1] = $row['dining'];
      $rowArray[2] = $row['market'];
      $rowArray[3] = $row['other'];
   }

   $rowArray[4] = $unit;
   $rowArray[5] = $yield;

//   $rowArray[4] = $row['Total'];
   $rowArray[6] = $row['crop'];
   // $rowArray[7] = $row['units'];
   $rowArray[7] = $unit;
   $rowArray[8] = $date;
*/
   $rowArray['FARMDATA_unit'] = $unit;
   $rowArray['FARMDATA_total'] = $tot;
   $rowArray['FARMDATA_date'] = $date;
   $rowArray['FARMDATA_yield'] = $yield;

   $harvestListArray[$row['crop']] = $rowArray;
   $i++;
}

echo json_encode($harvestListArray);

mysql_close();
?>
