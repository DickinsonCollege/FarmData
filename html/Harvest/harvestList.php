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
   $targ = $row['targetName'];
   if ($targ != 'Loss') {
      $targs[] = $row['targetName'];
   }
}
?>

<style type="text/css">
table.primary tr.other{
	background-color:#ADD8E6;
}

</style>
<?php
echo "<center>";
echo '<h2> Harvest List for '.$date.'</h2>';
echo "</center>";
echo '<table class="primary pure-table pure-table-bordered">';
//echo '<caption> Harvest List for '.$date.'</caption>';
if($detail==1){

// <tr class=\"other\">
echo "<thead>
        <th>Crop </th>
        <th>Field</th>
        <th>Units </th>";
   for ($i = 0; $i < count($targs); $i++) {
      echo '<th>'.$targs[$i].'</th>';
   }
echo "<th>Total</th> <th>Harvested</th> </tr></thead>";
}else{
echo "<thead><tr>
        <th>Crop </th>
        <th>Field</th>
        <th>Units </th>
        <th>Total</th>
        <th>Harvested</th>
</tr></thead>";
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
echo '</table>';
?>

<br clear="all"/>

<?php
if ($_SESSION['mobile']) {
   echo "<label>&nbsp;</label>";
   echo '<br clear="all"/>';
}
?>
<h3>Comments</h3>
<!--
<br clear="both"/>
-->
<?php
$sql="SELECT comment from harvestList where id=".$currentID;
$row= mysql_fetch_array( mysql_query($sql));
if ($_SESSION['mobile']) {
  echo "<label class='label_comments'>";
} else {
   echo '<div class="comments_box">';
}

echo  str_replace("\n", "<br>", $row['comment']);
if ($_SESSION['mobile']) {
  echo "</label>";
} else {
   echo "</div>";
}

echo '<br clear="all"/>';
/*
if($detail==1){
echo '<form method="post" action = "harvestList.php?tab=harvest:harvestList&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=0"><input type="submit" class="submitbutton mobile-submit" value = "Harvest List Summary"></form>';
}else{
echo '<form method="post" action = "harvestList.php?tab=harvest:harvestList&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=1"><input type="submit" class="submitbutton" value = "Detailed Harvest List"></form>';
}
*/
$det = "";
if ($detail == 0) {
  $det = 'Detailed ';
}
echo "<div class='pure-g'>";
echo "<div class='pure-u-1-2'>";
echo '<form method="post" action = "harvestList.php?tab=harvest:harvestList&year='.$year.
   '&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail='.!$detail.
   '"><input type="submit" class="submitbutton pure-button wide" value = "'.$det.
   ' Harvest List"></form>';
?>
</div>

<?php
echo "<div class='pure-u-1-2'>";
echo '<form method="post" action = "addComment.php?tab=harvest:harvestList&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=0"><input type="submit" class="submitbutton pure-button wide" value = "Add Comment"></form>';
echo '<br clear="all"/>';
echo ' <meta http-equiv="refresh" content=60;URL="harvestList.php?tab=harvest:harvestList&year='.
   $year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.
   '&detail='.$detail.' ">';
?>
</div>
</div>
</html>

