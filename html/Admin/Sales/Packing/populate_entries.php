<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$lastHarvest = $_GET['id'];

do {
   $sql = "SELECT count(*) as num FROM harvestListItem WHERE id=".$lastHarvest;
   $result = $dbcon->query($sql);
   $row = $result->fetch(PDO::FETCH_ASSOC);
   $num = $row['num'];
   if ($num == 0) {
      $lastHarvest--;
   }
} while ($num == 0);


$dateSQL = "SELECT harDate FROM harvestList WHERE id=".$lastHarvest;
$dateResult = $dbcon->query($dateSQL);
$dateRow = $dateResult->fetch(PDO::FETCH_ASSOC);
$date = $dateRow['harDate'];

$harvestListArray = array();
//$i = 0;

$sql = "SELECT distinct crop, units FROM harvestListItem WHERE id=".$lastHarvest;
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $totalSQL = "SELECT sum(yield) as yield, unit FROM harvested WHERE crop='".$row['crop'].
      "' AND hardate='".$date."' group by unit";
   $totalResult = $dbcon->query($totalSQL);
   $totalRow = $totalResult->fetch(PDO::FETCH_ASSOC);
   $yield = $totalRow['yield'];
   $unit = $totalRow['unit'];
   $convert = false;
   $conversion = 1;
   if ($yield == null) {
      $yield = 0;
      $unit = $row['units'];
   }
   $convsql = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='POUND'";
   $convresult = $dbcon->query($convsql);
   if ($convrow = $convresult->fetch(PDO::FETCH_NUM)) {
      $convert = true;
      $conversion = $convrow[0];
      $yield = $yield * $conversion;
      $unit='POUND';
   }
   
   $rowArray = array();
   $sql = "select * from harvestListItem where crop='".$row['crop']."' AND id=".$lastHarvest;
   $resultc = $dbcon->query($sql);
   $tot = 0;
   while ($rowc = $resultc->fetch(PDO::FETCH_ASSOC)) {
      $targ = $rowc['target'];
      $rowArray[$targ] = $rowc['amt'];
      $hunit = $rowc['units'];
      if ($unit != $hunit) {
        $conversion = 1;
        $defsql = "select units from plant where crop = '".$row['crop']."'";
        $defres = $dbcon->query($defsql);
        $defrow = $defres->fetch(PDO::FETCH_ASSOC);
        $defunit = $defrow['units']; 
        if ($unit == $defunit) {
           // convert to default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$hunit."'";
           $convres1 = $dbcon->query($convsql1);
           $convrow1 = $convres1->fetch(PDO::FETCH_ASSOC);
           $conversion = 1 / $convrow1['conversion'];
        } else if ($hunit == $defunit) {
           // convert from default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$unit."'";
           $convres1 = $dbcon->query($convsql1);
           $convrow1 = $convres1->fetch(PDO::FETCH_ASSOC);
           $conversion = $convrow1['conversion'];
        } else {
           // convert to default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$hunit."'";
           $convres1 = $dbcon->query($convsql1);
           $convrow1 = $convres1->fetch(PDO::FETCH_ASSOC);
           $conversion1 = 1 / $convrow1['conversion'];
           // convert from default unit
           $convsql1 = "SELECT conversion FROM units WHERE crop='".$row['crop']."' AND unit='".$unit."'";
           $convres1 = $dbcon->query($convsql1);
           $convrow1 = $convres1->fetch(PDO::FETCH_ASSOC);
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

?>
