<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<?php
   $year    = $_GET['year'];
   $month   = $_GET['month'];
   $day       = $_GET['day'];
   $tyear    = $_GET['tyear'];
   $tmonth   = $_GET['tmonth'];
   $tday    = $_GET['tday'];
   $starttime    = $year.'-'.$month.'-'.$day;
   $endtime       = $tyear.'-'.$tmonth.'-'.$tday;
   function getDatesFromRange($start, $end){
       $dates = array($start);
       while(end($dates) < $end){
         $dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
      }
      return $dates;
   }
   // find all the fields that were irrigated by Drip
   $sql = 'select distinct fieldID from pump_field natural join pump_master where irr_device <> \'Drip\' and pumpDate between \''.$starttime.'\' and \''.$endtime.'\' order by fieldID';
   $data= mysql_query($sql);
   $arrayOverHead = array();
   $count = 0;
   while ($row = mysql_fetch_array($data)){
      $arrayOverHead[$count] = $row['fieldID'];
      $count++;
   }
   $numOvh = count($arrayOverHead);   
   // find all the fields that were irrigated by OverHead
   $sql = 'select distinct fieldID from pump_field natural join pump_master where irr_device = \'Drip\' and pumpDate between \''.$starttime.'\' and \''.$endtime.'\' order by fieldID';
   $data= mysql_query($sql);
   $arrayDrip = array();
   $count = 0;
   while ($row = mysql_fetch_array($data)){
      $arrayDrip[$count] = $row['fieldID'];
      $count++;
   }
   $numD = count($arrayDrip);
   // find all the date between date range
   $dateArray =  getDatesFromRange(date('Y-m-d',strtotime($starttime)
         - 7*60*60*24),date('Y-m-d',strtotime($endtime)));
   $numDate = count($dateArray);
   // find all the comments
   $commentArray = array();
   $commentQuery = 'select pumpDate, group_concat(comment) from pump_master where pumpDate between SUBDATE(\''.$starttime.'\', 7) and \''.$endtime.'\'  group by pumpDate;';
   $data    = mysql_query($commentQuery);
   while($row = mysql_fetch_array($data)){
      $commentArray[$row['pumpDate']] = $row['group_concat(comment)'];
   }
   // find all the rain values
   $rainArray = array();
   $rainQuery = "select pumpDate, rain from pump_master where pumpDate between SUBDATE('".$starttime."', 7) and '".$endtime."' group by pumpDate";
   $data        = mysql_query($rainQuery);
   while($row = mysql_fetch_array($data)){
      $rainArray[$row[pumpDate]] = $row[rain];
   }
   // Return color value of a field if it is not irrigated and has no rain
   function noWater($thisdate, $field, $dataArray, $dateArray, $rainArray, $drip){
      // find current date index
      $index = array_search($thisdate, $dateArray);
      // go back and find if there is no rain and irrigation in that date.
      $count = 0;
      $thresh = 0.75;
      if ($drip == 1) {
          $thresh = 3;
      }
      while($index != 0 && 
        ($dataArray[$thisdate][$field][$drip] == '' ||
         $dataArray[$thisdate][$field][$drip] < $thresh)
     && ($rainArray[$thisdate] == '' || $rainArray[$thisdate] < 0.5)){
         $count++;
         $index--;
         $thisdate = $dateArray[$index];
      }
      if ($count > 7 || $index == 0) {$count = 7;}
      return $count;
   }
   // create the 3d array for irrigation data
   $dataArray = array();
        $sql = 'select fieldID,pumpDate, comment, (case when irr_device ='.
      '\'DRIP\' then 1 else 0 end) as drip, sum(elapsed_time)/60 as eth '.
      'from pump_master natural join pump_field where pumpDate between SUBDATE(\''.
      $starttime.'\', 7) and \''.$endtime.'\' group by fieldID, pumpDate, '.
      'case when irr_device ='. 
      '\'DRIP\' then 1 else 0 end'; 
   $data    = mysql_query($sql);
   while($row = mysql_fetch_array($data)){
      $dataArray[$row['pumpDate']][$row['fieldID']][$row['drip']] = number_format((float) $row['eth'], 2, '.', '');
   }
?>
<head>
<link rel='stylesheet' href='/pure-release-0.5.0/pure-min.css'>
</head>
<?php
$colorArray = array('#FFE5CC','#FFCC99', '#FFB266', '#FF9933', '#FF8000', '#CC6600', '#994C00');
?>
<center>
<h2>Irrigation Report from <?php echo $starttime;?> to <?php echo $endtime;?></h2>
<div id="tbl-container">
<!--
<table id='tbl' class='pure-table'>
<table id='tbl'>
-->
<table id='tbl' class='pure-table pure-table-bordered'>
<thead>
<tr>
   <th rowspan='2'>Date</th>
   <th colspan='<?php echo $numOvh;?>'>Overhead (Hours)</th>
   <th colspan='<?php echo $numD;?>'>Drip (Hours)</th>
   <th rowspan='2'>Date</th>
   <th rowspan='2'>Comment</th>
   <th rowspan='2'>Rain (inches)</th>
   <th rowspan='2'>Update</th>
</tr>
<tr class='pure-table-odd'>
   <?php
      if ($numOvh == 0) {
         echo '<th style="border-left:1px solid #CBCBCB">&nbsp;</th>';
      }
      for ($i = 0; $i < $numOvh; $i++){
         echo '<th style="border-left:1px solid #CBCBCB">'.$arrayOverHead[$i].'</th>';
      }
      if ($numD == 0) {
         echo '<th style="border-left:1px solid #CBCBCB">&nbsp;</th>';
      }
      for ($i = 0; $i < $numD; $i++){
         echo '<th style="border-left:1px solid #CBCBCB">'.$arrayDrip[$i].'</th>';
      }
   ?>
</tr>
</thead>
<tbody>
<?php
   for ($i = 7; $i < $numDate; $i++){
      if ($i > 7 && $i % 10 == 7) {
         echo '<tr><td>Fields:</td>';
         if ($numOvh == 0) {
            echo '<td style="border-left:1px solid #CBCBCB">&nbsp;</td>';
         }
         for ($k = 0; $k < $numOvh; $k++){
            echo '<td style="border-left:1px solid #CBCBCB">'.$arrayOverHead[$k].'</td>';
         }
         if ($numD == 0) {
            echo '<td style="border-left:1px solid #CBCBCB">&nbsp;</td>';
         }
         for ($k = 0; $k < $numD; $k++){
            echo '<td style="border-left:1px solid #CBCBCB">'.$arrayDrip[$k].'</td>';
         }
         echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
      }
      $class='';
      if ($i %2 == 1) {$class= 'class="pure-table-odd"';}
      echo '<tr '.$class.'>';
      echo '<td>';
         echo $dateArray[$i];
      echo '</td>';   
      if ($numOvh == 0) {
         echo '<td></td>';
      }
      for ($j = 0; $j < $numOvh; $j++){
         $color = $colorArray[noWater($dateArray[$i], $arrayOverHead[$j],
             $dataArray, $dateArray, $rainArray, 0)-1];
         // put color in that field
         echo '<td style="background-color:'.$color.'">';
         //echo '<td style="background-color:red">';
         $cell = $dataArray[$dateArray[$i]][$arrayOverHead[$j]][0];
         echo $cell;
         echo '</td>';
      }
      if ($numD == 0) {
         echo '<td></td>';
      }
      for ($j = 0; $j < $numD; $j++){
         $color = $colorArray[noWater($dateArray[$i], $arrayDrip[$j],$dataArray, $dateArray, $rainArray, 1)-1];
         echo '<td style="background-color:'.$color.'">';
         $cell = $dataArray[$dateArray[$i]][$arrayDrip[$j]][1];
         echo $cell;
         echo '</td>';
      }
      echo '<td>';
         echo $dateArray[$i];
      echo '</td>';   
      echo '<td>'.$commentArray[$dateArray[$i]].'</td>';
      echo '<form method="POST" action="rainUpdate.php?tab=soil:soil_irrigation:irrigation_report&curDate='.$dateArray[$i].'&day='.$day.'&month='.$month.'&year='.$year.'&tday='.$tday.'&tmonth='.$tmonth.'&tyear='.$tyear.'"><td><input type="text" class="textbox4" id="rain" name="rain" size="5" value='.$rainArray[$dateArray[$i]].'></td>';
      echo '<td><input type="submit" class="genericbutton pure-button wide" value="update" ></td></form>';
      echo '</tr>';
   }
?>
</tbody>
</table>
</div>
</center>
<br clear="all"/>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<form name='form' method='POST' action='/down.php'>
<input type="hidden" name="query" value="<?php
echo "select pumpDate, fieldID, irr_device, elapsed_time/60 as elapsed_time, comment from pump_field, ".
   "pump_master where pump_field.id = pump_master.id and pumpDate between '".$starttime."' and '".$endtime.
   "' order by pumpDate"; ?>">
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Report">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "irrigation.php?tab=soil:soil_irrigation:irrigationreport"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>
</div>
</div>
</form>
