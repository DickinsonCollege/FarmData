<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$currentID=$_GET['currentID'];
$year=$_GET['year'];
$month=$_GET['month'];
$day=$_GET['day'];
$date=$year."-".$month."-".$day;
$detail=$_GET['detail'];
$farm = $_SESSION['db'];
$sql = "select * from targets where active = 1 order by targetName";
$targs = array();
$result = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($result)) {
   $targs[] = $row['targetName'];
}
?>

<style type="text/css">
table.primary tr.other{
	background-color:#ADD8E6;
}

</style>
<?php
echo '<table class="primary">';
echo '<caption> Harvest List for '.$date.'</caption>';
if($detail==1){

echo "<tr class=\"other\">
        <th>Crop </th>
        <th>Field</th>
        <th>Units </th>";
   for ($i = 0; $i < count($targs); $i++) {
      echo '<th>'.$targs[$i].'</th>';
   }
echo "<th>Total</th> <th>Harvested</th> </tr>";
}else{
echo "<tr>
        <th>Crop </th>
        <th>Field</th>
        <th>Units </th>
        <th>Total</th>
        <th>Harvested</th>
</tr>";
}

$sql = "select * from harvestListItem where id=".$currentID;
$result = mysql_query($sql);
echo mysql_error();
$tabArr = array();
while($row=mysql_fetch_array($result)){
   $tabArr[$row['crop']][$row['target']]=$row['amt'];
   $tabArr[$row['crop']]['fieldID']=$row['fieldID'];
   $tabArr[$row['crop']]['units']=$row['units'];
}
foreach ($tabArr as $crp=>$arr) {
   $tot = 0;
   $reqs = array();
   for ($i = 0; $i < count($targs); $i++) {
      if (isset($tabArr[$crp][$targs[$i]])) {
         $reqs[$i] = $tabArr[$crp][$targs[$i]];
      } else {
         $reqs[$i] = 0;
      }
      $tot += $reqs[$i];
   }
   $sql = "select sum(yield) * (Select conversion from units where crop= '".$crp."' and unit = '".
      $tabArr[$crp]['units']."') as yld, unit from harvested, harvestList where harvested.crop='".
     $crp."' and harvested.hardate=harvestList.harDate and harvestList.id=".$currentID.
     " group by unit";
   $itemYield=0;
   $result = mysql_query($sql);
   echo mysql_error();
   while ($row=mysql_fetch_array($result)) {
      $itemYield=$row['yld'];
   }
   if($itemYield>=$tot){
      echo "<tr class=\"other\">";
   } else {
      echo "<tr>";
   }
   echo "<td> <a class=\"gx2\" href=\"harvest.php?tab=harvest:harvestInput&crop=".encodeURIComponent($crp)."&date=".$date."&year=".$year."&month=".$month."&day=".$day."&currentID=".$currentID."\">".$crp." </a> </td>";
   echo "<td>".$tabArr[$crp]['fieldID']."</td><td>";
   echo $tabArr[$crp]['units']."</td>";
   if ($detail == 1) {
      for ($i = 0; $i < count($reqs); $i++) {
         echo "<td>".$reqs[$i]."</td>";
      }
   }
   echo "<td>".$tot."</td>";
   echo "<td>".$itemYield."</td>";
   echo "</tr>";
}


/*

# Put back when conversions are in place
#	$sqlBig="SELECT harvestListEntry.crop,harvestListEntry.fieldID,harvestListEntry.units,harvestListEntry.CSA,harvestListEntry.dining,harvestListEntry.market,harvestListEntry.other,harvestListEntry.Total,(SELECT COALESCE(SUM(harvested.yield/ (SELECT 1 as conversion FROM units where crop=harvestListEntry.crop and default_unit=harvestListEntry.units UNION select conversion from units where crop=harvestListEntry.crop and unit=harvestListEntry.units)),0) FROM harvested where harvested.crop=harvestListEntry.crop and harvested.hardate=harvestList.harDate) as itemYield FROM harvestListEntry,harvestList WHERE harvestListEntry.id=".$currentID." and harvestList.id=".$currentID." order by (itemYield-Total)";
//	$sqlBig="SELECT harvestListEntry.crop,harvestListEntry.fieldID,harvestListEntry.units,harvestListEntry.CSA,harvestListEntry.dining,harvestListEntry.market,harvestListEntry.other,harvestListEntry.Total,(SELECT COALESCE(SUM(harvested.yield),0) FROM harvested where harvested.crop=harvestListEntry.crop and harvested.hardate=harvestList.harDate) as itemYield FROM harvestListEntry,harvestList WHERE harvestListEntry.id=".$currentID." and harvestList.id=".$currentID." order by (itemYield-Total)";
	$sqlBig="SELECT harvestListEntry.crop,harvestListEntry.fieldID,harvestListEntry.units,harvestListEntry.CSA,harvestListEntry.dining,harvestListEntry.market,harvestListEntry.other,harvestListEntry.Total,(SELECT COALESCE(SUM(harvested.yield),0) FROM harvested where harvested.crop=harvestListEntry.crop and harvested.hardate=harvestList.harDate) as itemYield FROM harvestListEntry,harvestList WHERE harvestListEntry.id=".$currentID." and harvestList.id=".$currentID." order by harvestListEntry.crop";
	$resultBig=mysql_query($sqlBig);
echo mysql_error();
while($row=mysql_fetch_array($resultBig)){ 
	$itemCrop=$row['crop'];
	$itemField=$row['fieldID'];
	$itemUnits=$row['units'];
	$itemCSA=$row['CSA'];
	$itemD=$row['dining'];
	$itemM=$row['market'];
	$itemO=$row['other'];
	$itemT=$row['Total'];
	$itemYield=$row['itemYield'];


	if($itemYield>=$itemT){
	echo "<tr class=\"other\">";
	}else{
	echo "<tr>";
	}
	
	echo "<td> <a class=\"gx2\" href=\"harvest.php?tab=harvest:harvestInput&crop=".encodeURIComponent($itemCrop)."&date=".$date."&year=".$year."&month=".$month."&day=".$day."&currentID=".$currentID."\">".$itemCrop." </a> </td>";
        echo "  <td> $itemField </td>
                <td> $itemUnits</td>";
if($detail ==1){
	echo "	<td> $itemCSA</td>
		<td> $itemD</td>
		<td> $itemM</td>
		<td>$itemO</td>
		<td>$itemT</td>
		<td>".number_format((float) $itemYield, 2, '.', '')."</td>
		</tr>";
}else{
        echo "	<td>$itemT</td>
		<td>".number_format((float) $itemYield, 2, '.', '')."</td>
                </tr>";

}
}
*/
echo '</table>';
?>

<br clear="all"/>
<div class="comments"> 
<?php
if($_SESSION['mobile'] && !ae_detect_ie()&& $detail==1) {
echo '<textarea class="comments2">';
} else if($_SESSION['mobile'] && ae_detect_ie() && $detail==1){
echo '<textarea style="width:1255px" class="comments">';
}else {
if(!$_SESSION['mobile'] && $detail != 1){
echo '<textarea class="comments">';
} else if ($_SESSION['mobile'] && ae_detect_ie()) {
echo '<textarea style="width: 780px;" class="comments">';
} else {
echo '<textarea  class="comments">';
     }	
}
?>

<?php
$sql="SELECT comment from harvestList where id=".$currentID;
$row= mysql_fetch_array( mysql_query($sql));
echo  $row['comment'];

echo "</textarea>";
echo "</div>";

echo "</form>";
echo '<br clear="all"/>';
if($detail==1){
echo '<form method="post" action = "harvestList.php?tab=harvest:harvestList&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=0"><input type="submit" class="submitbutton mobile-submit" value = "Harvest List Summary"></form>';
}else{
echo '<form method="post" action = "harvestList.php?tab=harvest:harvestList&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=1"><input type="submit" class="submitbutton" value = "Detailed Harvest List"></form>';
}
?>

<?php
echo '<form method="post" action = "addComment.php?tab=harvest:harvestList&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=0"><input type="submit" class="submitbutton" value = "Add Note"></form>';
echo '<br clear="all"/>';
echo ' <meta http-equiv="refresh" content=60;URL="harvestList.php?tab=harvest:harvestList&year='.
   $year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.
   '&detail='.$detail.' ">';
?>
</html>

