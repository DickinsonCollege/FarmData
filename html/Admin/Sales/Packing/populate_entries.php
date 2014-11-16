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

$harvestListArray = array();
//$i = 0;

while ($row = mysql_fetch_array($result)) {
   $totalSQL = "SELECT sum(yield) as yield, unit FROM harvested WHERE crop='".$row['crop']."' AND hardate='".$date."' group by unit";
   $totalResult = mysql_query($totalSQL);
   $totalRow = mysql_fetch_array($totalResult);
   $yield = $totalRow['yield'];
   $unit = $totalRow['unit'];
   $convert = false;
   $conversion = 1;
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
   $sql = "select * from harvestListItem where crop='".$row['crop']."' AND id=".$lastHarvest;
   $resultc = mysql_query($sql);
   echo mysql_error();
   $tot = 0;
   while ($rowc = mysql_fetch_array($resultc)) {
      $targ = $rowc['target'];
      $rowArray[$targ] = $rowc['amt'];
      $hunit = $rowc['units'];
      if ($unit != $hunit) {
        $conversion = 1;
        $defsql = "select units from plant where crop = '".$row['crop']."'";
        $defres = mysql_query($defsql);
        $defrow = mysql_fetch_array($defres);
        $defunit = $defrow['units']; 
        if ($unit == $defunit) {
           // convert to default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$hunit."'";
           $convres1 = mysql_query($convsql1);
           $convrow1 = mysql_fetch_array($convres1);
           $conversion = 1 / $convrow1['conversion'];
        } else if ($hunit == $defunit) {
           // convert from default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$unit."'";
           $convres1 = mysql_query($convsql1);
           $convrow1 = mysql_fetch_array($convres1);
           $conversion = $convrow1['conversion'];
        } else {
           // convert to default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$hunit."'";
           $convres1 = mysql_query($convsql1);
           $convrow1 = mysql_fetch_array($convres1);
           $conversion1 = 1 / $convrow1['conversion'];
           // convert from default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$unit."'";
           $convres1 = mysql_query($convsql1);
           $convrow1 = mysql_fetch_array($convres1);
           $conversion2 = $convrow1['conversion'];
           $conversion = $conversion1 * $conversion2;
        }
        $rowArray[$targ] = $rowArray[$targ] * $conversion;
      }
      $tot += $rowArray[$targ];
   }
   
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
