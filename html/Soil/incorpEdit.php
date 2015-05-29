<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origFieldID = $_GET['fieldID'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, year(killDate) as yr, month(killDate) as mth, day(killDate) as dy, 
   incorpTool, totalBiomass, fieldID, seedDate, killDate, comments
   FROM coverKill_master 
   WHERE id=".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$coverKillID = $row['id'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$incorpTool = $row['incorpTool'];
$totalBiomass = $row['totalBiomass'];
$fieldID = $row['fieldID'];
$seedDate = $row['seedDate'];
$killDate = $row['killDate'];
$comments = $row['comments'];


?>

<script type="text/javascript">

function selectDates() {
   var xmlhttp = new XMLHttpRequest();
   var a = document.getElementById('fieldID');
   var fieldID = a.options[a.selectedIndex].text;

   xmlhttp.open("GET", "update_date.php?fieldID=" + fieldID, false);
   xmlhttp.send();

   var seeddateDiv = document.getElementById('seeddateDiv');
   seeddateDiv.innerHTML = '<div class="pure-control-group" id="seeddateDiv">' +
       "<label for='seeddatelabel'>Seed Date:</label> " +
       "<select name='seeddate' id='seeddate' class='mobile-select' onchange='selectSpecies();'>" + 
       xmlhttp.responseText + "</select></div>";
}

function selectSpecies() {
   var xmlhttp = new XMLHttpRequest();
   var a = document.getElementById('fieldID');
   var fieldID = a.options[a.selectedIndex].text;
   var b = document.getElementById('seeddate');
   var seedDate = b.options[b.selectedIndex].text;

   xmlhttp.open("GET", "update_species.php?fieldID=" + fieldID + "&seedDate=" + seedDate, false);
   xmlhttp.send();

   var speciesNames = eval(xmlhttp.responseText);
   var sDiv = document.getElementById("speciesList");
   var content = '<div class="pure-control-group" id="speciesList"><label>Species:</label> ' +
      '<textarea readonly id="listArea" name="listArea">';
   for (i = 0; i < speciesNames.length; i++) {
      content += speciesNames[i];
      if (i < speciesNames.length - 1) {
         content += "\n";
      }
   }
   content += '</textarea> </div>';
   sDiv.innerHTML = content;
}

</script>


<?php
echo "<form name='form' class='pure-form pure-form-aligned' method='POST' action=\"".$SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report".
   "&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&id=".$id."&fieldID=".encodeURIComponent($origFieldID)."\">";

echo "<center>";
echo "<H2> Edit Cover Crop Incorporation Record </H2>";
echo "</center>";
echo '<div class="pure-control-group">';
echo '<label>Date:</label> ';
echo '<select class="mobile-month-select" name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</select>';
echo '<select class="mobile-day-select" name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</select>';
echo '<select class="mobile-year-select" name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo "<label>Name of Field:</label> ";
echo '<select onchange="selectDates(); selectSpecies();" class="mobile-select" name="fieldID" id="fieldID">';
echo "<option value='".$fieldID."' selected>".$fieldID."</option>";
$sql = "select distinct fieldID from coverSeed_master";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row['fieldID']."'>".$row['fieldID']."</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group" id="seeddateDiv">';
echo "<label for='seeddatelabel'>Seed Date:</label> ";
// echo "<div class='styled-select' id='seeddateDiv'>";
echo "<select onchange='selectSpecies();' class='mobile-select' name='seeddate' id='seeddate'>";
echo "<option value='".$seedDate."'>".$seedDate."</select></div>";

/*
echo "<br clear='all'>";
echo "<table name='coverCropTable' id='coverCropTable'>";
echo "<tr><th>Species</th></tr>";
$sql = "SELECT coverCrop FROM coverKill WHERE id=".$coverKillID;
$result = mysql_query($sql);
$numRows = 0;
while ($row = mysql_fetch_array($result)) {
   $numRows++;
   echo "<tr><td>";
   echo "<input readonly type='text' style='width:100%;' class='textbox25 mobile-input inside_table' 
      name='crop".$numRows."' id='crop".$numRows."' value='".$row['coverCrop']."'>";
   echo "</select></td></tr>";
}
*/
   
echo '<div class="pure-control-group" id="speciesList">';
echo '<label>Species:</label> ';
echo '<textarea readonly id="listArea" name="listArea"></textarea>';
echo '</div>';
echo '<script type="text/javascript">selectSpecies();</script>';


echo "<input type='hidden' value='".$numRows."' name='numRows' id='numRows'>";
echo "</table>";
echo "<input type='hidden' id='numCrops' name='numCrops' value=".$numRows.">";

echo '<div class="pure-control-group">';
echo "<label>Total Biomass Pounds:</label> ";
echo "<input type='text' class='textbox25 mobile-input' name='totalBiomass' id='totalBiomass' value='".$totalBiomass."'>";
echo "</div>";

echo '<div class="pure-control-group">';
echo "<label>Incorporation Tool:</label> ";
echo "<select class='mobile-select' name='incorpTool' id='incorpTool'>";
echo "<option value='".$incorpTool."' selected>".$incorpTool."</option>";
$sql = "select tool_name from tools where type='INCORPORATION'";
$result = mysql_query($sql) or die();
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row['tool_name']."'>".$row['tool_name']."</option>";
}
echo "</select></div>";

echo '<div class="pure-control-group">';
echo '<label>Comments:</label> ';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '</div>';
echo '<br clear="all"/>';
echo '<br clear="all"/>';

echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "</form>";
if ($_POST['submit']) {
   $comments = escapehtml($_POST['comments']);
   $seedDate = escapehtml($_POST['seeddate']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $fieldID = escapehtml($_POST['fieldID']);
   $incorpTool = escapehtml($_POST['incorpTool']);
   $totalBiomass = escapehtml($_POST['totalBiomass']);

/*
   // Delete from coverKill table based on coverKill_master id
   $sql = "DELETE FROM coverKill WHERE id=".$coverKillID;
   $result = mysql_query($sql);

   if (!$result) {
      echo "<script> alert('Could not update data: Please try again! \n".mysql_error()."');</script>\n";
   }

   // Insert new crops into coverKill table with id of coverKill_master
   $count = 1;
   $numCrops = $_POST['numCrops'];
   while ($count <= $numCrops) {
      $crop = mysql_escape_string($_POST['crop'.$count]);
echo      $sql = "INSERT INTO coverKill(coverCrop, id)
         VALUES ('".$crop."', ".$coverKillID.");";
      $result = mysql_query($sql);
      echo mysql_error();
      $count++;
   }

   if (!$result) {
      echo "<script> alert('Could not update data: Please try again! \n".mysql_error()."');</script>\n";
   }
*/

   // Update coverKill_master
   echo $sql = "UPDATE coverKill_master
      SET seedDate='".$seedDate."',  
      killDate='".$year."-".$month."-".$day."', 
      fieldID='".$fieldID."', totalBiomass=".$totalBiomass.", incorpTool='".$incorpTool."', comments='".$comments."' 
      WHERE id=".$coverKillID;
   $result = mysql_query($sql);
  
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=incorpTable.php?year='.$origYear.'&month='.$origMonth.
         '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
         "&fieldID=".encodeURIComponent($origFieldID).
         "&tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report\">";
   }
}
?>
