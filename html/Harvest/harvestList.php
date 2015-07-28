<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Sales/convert.php';

$currentID=$_GET['currentID'];
$year=$_GET['year'];
$month=$_GET['month'];
$day=$_GET['day'];
$date=$year."-".$month."-".$day;
$detail=$_GET['detail'];
$farm = $_SESSION['db'];
if ($ind = array_search('Loss', $targs)) {
   unset($targs[$ind]);
   $targs = array_values($targs);
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
if($detail==1){

   $th= "<thead> <th>Crop </th> <th>Field</th>";
   for ($i = 0; $i < count($targs); $i++) {
      $th .= '<th>'.$targs[$i].'</th>';
   }
   $th .= "<th>Total</th> <th>Harvested</th> </tr></thead>";
   // echo $th;
}else{
echo "<thead><tr>
        <th>Crop </th>
        <th>Field</th>
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
   $tabArr[$row['crop']][$row['target'].'_units']=$row['units'];
}
$cnt = 0;
foreach ($tabArr as $crp=>$arr) {
   $tot = 0;
   $reqs = array();
   $reqs_unit = array();
   for ($i = 0; $i < count($targs); $i++) {
      if (isset($tabArr[$crp][$targs[$i]])) {
         $reqs[$i] = $tabArr[$crp][$targs[$i]];
         $reqs_unit[$i] = $tabArr[$crp][$targs[$i].'_units'];
      } else {
         $reqs[$i] = 0;
      }
      $tot += $reqs[$i] / $conversion[$crp][$reqs_unit[$i]];
   }
/*
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
*/
   if ($detail == 1 && $cnt % 5 == 0) {
      echo $th;
   }
   $sql = "select sum(yield) as yld, unit from harvested, harvestList where crop='".
      $crp."' and harvested.hardate = harvestList.harDate and harvestList.id=".
      $currentID." group by unit";
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
   echo "<td>".$tabArr[$crp]['fieldID']."</td>";
   if ($detail == 1) {
      for ($i = 0; $i < count($reqs); $i++) {
         echo "<td>".$reqs[$i]." ";
         if ($reqs[$i] > 0) {
            echo $reqs_unit[$i];
         } else {
            echo $default_unit[$crp];
         }
         echo "(S)</td>";
      }
   }
   echo "<td>".number_format((float) $tot, 2, '.', '')." ".
      $default_unit[$crp]."(S)</td>";
   echo "<td>".number_format((float) $itemYield, 2, '.', '').
     " ".$default_unit[$crp]."(S)</td>";
   echo "</tr>";
   $cnt++;
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

