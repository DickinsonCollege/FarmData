<?php
     session_start();
     include $_SERVER['DOCUMENT_ROOT'].'/design.php';
     include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
     include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
     include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
     $farm = $_SESSION['db'];
?>
<head>
     <link rel="stylesheet" href="/pure-release-0.5.0/pure-min.css">
     <!--<meta name="viewport" content="width=device-width, initial-scale=.3, minimum-scale=.3, maximum-scale=1">-->
</head>
<?php 
/*if ($_SESSION['pump']) {
   echo "<h3>Pump Log Input </h3>";
} else {
   echo "<h3>Irrigation Input </h3>";
}*/

$dYear           = null;
$dMonth          = null;
$dDay               = null;
$valvefill     = '';
$drivefill     = '';
$outletfill     = '';
$pump                = '';
$solar          = '';
$sql = 'select valve_open from pump_master where id = (select max(id) from '.
   'pump_master)';
$data= mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_array($data);
if ($row['valve_open']) {
   $valvefill=trim($row['valve_open']);
}
$sql = 'select year(pumpDate) as year, month(pumpDate) as month, day(pumpDate) as day, valve_open, '.
   'driveHZ, outlet_psi, pump_kwh, solar_kwh, comment, ('.time().
   ' - start_time)/60 as runtime  from pump_log_temp';
$data= mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_array($data);
if ($row['year']) {
     $dYear           = $row['year'];
     $dMonth       = $row['month'];
     $dDay             = $row['day'];
     $valvefill  = $row['valve_open'];
     $drivefill  = $row['driveHZ'];
     $outletfill = $row['outlet_psi'];
     $pump       = $row['pump_kwh'];
     $solar      = $row['solar_kwh'];
     $comment      = $row['comment'];
     $runtime      = number_format((float) $row['runtime'], 1, '.', '');
}
?>
<form class='pure-form pure-form-aligned' method='POST' action='pumpInfo.php'>
     <fieldset>
         <?php 
				if ($_SESSION['pump']) {
   				echo "<h3>Pump Log Input </h3>";
				} else {
   				echo "<h3>Irrigation Input </h3>";
				}
				echo "<br clear='all'/>";
			?> 
			<div class='pure-control-group'>
               <label for='date'>Date:&nbsp;</label>
               <?php include $_SERVER['DOCUMENT_ROOT'].'/date.php'?>
          </div>
          <br clear='all'>
          <div class='pure-control-group'>
               <label for='valve'>Valves Open Last Session:&nbsp;</label>
<?php
   if ($_SESSION['mobile']) {
      echo "<input type='text' readonly id='valve' width=50 name='valve' class='textbox25 mobile-input' value='".
         $valvefill."'/>";
   } else {
      echo "<textarea id='valve' name='valve' readonly style='margin-top:10px;'>".$valvefill."</textarea>";
   }
   echo "    </div>
          <br clear='all'>

          <div class='pure-control-group'>
         <label for='run'>Pump Run Time (minutes):&nbsp;</label>
         <input type='text' id='run' name='run' class='textbox25 mobile-input' readonly value='".$runtime."'>";
    echo "</div>
          <br clear='all'>";

   if ($_SESSION['pump']) {
          echo "<div class='pure-control-group'>
         <label for='drive'>Drive Hz:&nbsp;</label>
         <input type='text' id='drive' name='drive' class='textbox25 mobile-input' value='";
     echo $drivefill."'>";
     echo "</div>
          <br clear='all'>
          
          <div class='pure-control-group'>
               <label for='outlet'>Outlet PSI:&nbsp;</label>
               <input type='text' id='outlet' name='outlet' class='textbox25 mobile-input' value='";
     echo $outletfill."'>";
     echo "    </div>
          <br clear='all'>

          <div class='pure-control-group'>
               <label for='pumpKWH'>Pump KWH:&nbsp;</label>
               <input type='text' id='pump_kwh' name='pump_kwh' class='textbox25 mobile-input' value='";
      echo $pump."'>";
      echo"    </div>
          <br clear='all'>";

      if ($farm == "dfarm") {
      echo "
          <div class='pure-control-group'>
               <label for='solarKWH'>Solar KWH:&nbsp;</label>
         <input type='text' id='solar_KWH' name='solar_KWH' class='textbox25 mobile-input' value='". $solar."'>
          </div>
          <br clear='all'>";
      }
   }
?>
          
          <div class='pure-control-group'>
               <label for='comments'>Comments:&nbsp;</label>
               <textarea id='comment' name='comment' 
               style='margin-top:10px;'><?php echo $comment;?></textarea>
          </div>     
          <div class='pure-control-group'>
          <label >Seconds to Page Refresh:&nbsp;</label>
          <input type="text" id="timer" name="timer" class="textbox25 mobile-input">
      </div>
          <br clear='all'>
          <div class='pure-controls'>
               <input type='button' class='submitbutton' 
                value='<?php if ($_SESSION['pump']) echo 'Update Pump Log';
                     else echo 'Update Comments';?>' name='update' id='updatePump'
                onclick='updatePumpLog(false);'>
      </div>
          
         </fieldset> 
          <br clear='all'>
<script type="text/javascript">
// set the date we're counting down to
// var target_date = new Date("Aug 15, 2019").getTime();
    var current_date = new Date().getTime();
    var target_date = current_date + 120 * 1000;
 
// variables for time units
var days, hours, minutes, seconds;
 
// get tag element
var countdown = document.getElementById("timer");
 
// update the tag with id "countdown" every 1 second
setInterval(function () {
 
    // find the amount of "seconds" between now and target
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;
 
    // do some time calculations
/*
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;
     
    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;
     
    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);
*/
     
    // format countdown string + set tag value
    //countdown.value = days + "d, " + hours + "h, "
    //+ minutes + "m, " + seconds + "s";  
    countdown.value = parseInt(seconds_left);
  //  countdown.innerHtml = '<input type="text" id="timer" name="timer" ' +
   //   'class="textbox25" value = "' + parseInt(seconds_left) + '">';
}, 1000);
</script>


<h4>Field Irrigation</h4>
<br clear='all'/>
          <div class='pure-control-group'>
<!--
               <label for='solarKWH'>Fields:&nbsp;</label>
-->
               <table class='pure-table pure-table-bordered' id='fieldTable' style='margin-top:5px;'>
                   <tr>
                      <th>Field</th>
                      <th>Irrigation Device</th>
                      <th>Valve</th>
                      <th>Run Time (minutes)</th>
                   </tr>
<?php
      $sql          = "select fieldID, elapsed_time, irr_device, start_time, (".
     "case when start_time is null then elapsed_time else elapsed_time + (".time().
     "- start_time)/60 end) as cur_time from field_irrigation";
     $sqldata     = mysql_query($sql);
     echo mysql_error();
     $numRows     = 0;
     while($row = mysql_fetch_array($sqldata)){
          $numRows++;
          /*$result = mysql_query("Select fieldID from field_GH where fieldID not in (select fieldID from field_irrigation)");
          $option = '';
      while ($rowM =  mysql_fetch_array($result)){
               $option = $option. "<option value = \"".escapehtml($rowM[fieldID])."\">".$rowM[fieldID]."</option>";
          }
          $option_device = '';
          $result=mysql_query("Select irrigation_device from irrigation_device");
          while ($row1 =  mysql_fetch_array($result)){
               $option_device .=  "<option value = \"".escapehtml($row1[irrigation_device])."\">".escapehtml($row1[irrigation_device])."</option>";
          }*/
          $irrigation_device = '<option value = 0 selected disabled> Device</option>';
          if($row[irr_device] != null) {
               $irrigation_device = '<option value='.$row[irr_device].'>'.$row[irr_device].'</option>';
          }
          echo      '<tr><td><div class="styled-select" id="fieldDiv'.$numRows.'"> <select name ="fieldID'.$numRows.
      '" id="fieldID'.$numRows.'" class="mobile-select" disabled>'.
      '<option value="'.$row[fieldID].'">'.$row[fieldID].'</option>'.
// $option.
           '</select></div></td>';
          echo     '<td><div class="styled-select" id="irrigationDiv'.$numRows.'"> <select name ="irrigation'.$numRows.
           '" id="irrigation'.$numRows.'" class="mobile-select" disabled>'.
          $irrigation_device. //$option_device.
          '</select></div></td>';
          $ischecked = "";
          if ($row[start_time]){
               $ischecked = 'checked';
          }
          echo      '<td><div id="checkBox'.$numRows.'" class="switch"><input type="checkbox" id="check'.$numRows.
           '" name="check'.$numRows.'" class="toggle" value="checked'.$numRows.'" '.$ischecked.
           ' onchange="setElapsedTime('.$numRows.');"><label for="check'.$numRows.
           '" style="margin-top: 8px; margin-right:0;"></label><input type="hidden" name="checked_first'.$numRows.
           '" id="checked_first'.$numRows.'" value=true> </div></td><td>';
           echo number_format((float) $row['cur_time'], 1, '.', '');
           echo '</td></tr>';
     }
?>
</table>
<?php 
     echo '<input type="hidden" name="numRows" id="numRows" value='.$numRows.'>';
?>

 </div>
 <div class='pure-control-group'>
      <label>&nbsp;</label>
      <input type="button" id="addField" name="addField" class="genericbutton" onClick="addRows();" value="Add Field">
    <input type="button" id="removeField" name="removeField" class="genericbutton" onClick="removeRow();" value="Remove Field">               
 </div>
 <div class='pure-controls'>               
      <input type='submit' class='submitbutton' value='Submit' name='submit' onClick="return show_confirm();"> &nbsp;&nbsp;&nbsp;&nbsp;
      <input type='submit' class='submitbutton' value='Cancel' name='cancel' onClick="return cancelIt();">
 </div>
</fieldset>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/irrigationFunctions.php';
?>
</form>
<?php
if (!empty($_POST['submit'])){
     $numRows          = $_POST['numRows'];
     $date            = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
     // $valve           = escapehtml($_POST['valve']);
     if ($_SESSION['pump']) {
        $drive           = escapehtml($_POST['drive']);
        $outlet          = escapehtml($_POST['outlet']);
        $pump_kwh      = escapehtml($_POST['pump_kwh']);
        if ($farm == 'dfarm') {
           $solar_kwh     = escapehtml($_POST['solar_KWH']);
        } else {
           $solar_kwh = 0;
        }
     } else {
        $drive = 0;
        $outlet = 0;
        $pump_kwh = 0;
        $solar_kwh = 0;
     }
     $comment          = escapehtml($_POST['comment']);

     $queryValves = "select group_concat(fieldID SEPARATOR ', ') as valves from field_irrigation where start_time is not null";
     $result = mysql_query($queryValves);
     echo mysql_error();
     $row = mysql_fetch_array($result);
     $valve = mysql_real_escape_string($row['valves']);
     $queryTime = "select start_time from pump_log_temp";
     $result = mysql_query($queryTime);
     echo mysql_error();
     $row = mysql_fetch_array($result);
     $start_time = $row['start_time'];
     $runtime = (time() - $start_time)/60;
     $queryMaster= 'INSERT into pump_master (pumpDate,valve_open,driveHZ,outlet_psi,pump_kwh,solar_kwh,comment,run_time) values(\''.
        $date.'\', \''.$valve.'\', '.$drive.', '.$outlet.', '.$pump_kwh.', '.
        $solar_kwh.', \''.$comment.'\', '.$runtime.')';
     mysql_query($queryMaster) or die(mysql_error());
     echo mysql_error();
     $id                = mysql_insert_id();
     // delete from pump_log_temp table
     $sqlDelete  = 'delete from pump_log_temp';
     mysql_query($sqlDelete) or die(mysql_error());
     echo mysql_error();
     // insert to pump_field table with data from field_irrigation table
     $insertQuery= 'Insert into pump_field (fieldID, irr_device, elapsed_time, id) (select fieldID, irr_device, case when start_time is null then elapsed_time else elapsed_time + ('.time().'- start_time)/60 end, '.$id.' from field_irrigation)';
     mysql_query($insertQuery) or die(mysql_error());
     echo mysql_error();
     // delete data in the field_irrigation(temp) table
     $deleteQuery= 'delete from field_irrigation';
     mysql_query($deleteQuery) or die(mysql_error());
     echo mysql_error();
     $_POST['submit'] = 0;
//     echo '<script type="text/javascript">alert("Data Entered Successfully!");</script>';
     echo ' <meta http-equiv="refresh" content=0;URL="pumpInfo.php?tab=soil:soil_irrigation:irrigation_input">';
     //echo "<script>location.reload(true);</script>";
} else if (!empty($_POST['cancel'])){
     mysql_query("delete from field_irrigation");
     echo mysql_error();
     mysql_query("delete from pump_log_temp");
     echo mysql_error();
     echo ' <meta http-equiv="refresh" content=0;URL="pumpInfo.php?tab=soil:soil_irrigation:irrigation_input">';
}
?>

<meta http-equiv="refresh" content=120;URL="pumpInfo.php?tab=soil:soil_irrigation:irrigation_input">
