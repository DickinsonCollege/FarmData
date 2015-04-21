<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origPileID = $_GET['pileID'];
$origFieldID = $_GET['fieldID'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, year(tmpDate) as yr, month(tmpDate) as mth, day(tmpDate) as dy, tmpDate, pileID,
    temperature, numReadings, comments FROM compost_temperature where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$tmpDate = $row['tmpDate'];
$pileID = $row['pileID'];
$temperature= $row['temperature'];
$numReadings = $row['numReadings'];
$id = $row['id'];
$comments = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
?>

<?php
echo "<form name='form' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_compost:compost_report&year=".$origYear.
   "&month=".$origMonth.  "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&id=".$id."&pileID=".encodeURIComponent($origPileID).
   "&fieldID=".encodeURIComponent($origFieldID)."\">";

echo "<H3> Edit Compost Temperature Record </H3>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<label>Date:&nbsp</label>';
echo '<div class="styled-select"><select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo "</select>";
echo "</div>";
echo "<br clear='all'>";

echo "<label>Compost Pile ID:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='pileID' id='pileID'>";
echo "<option value=\"".$pileID."\" selected>".$pileID."</selected>";
$sql = "select pileID from compost_pile";
$result = mysql_query($sql) or die();
while ($row = mysql_fetch_array($result)) {
   echo "<option value=\"".$row['pileID']."\">".$row['pileID']."</option>";
}
echo "</select></div>";
echo "<br clear='all'/>";

echo "<label>Temperature:&nbsp</label>";
echo "<input type='text' class='textbox25' name='temperature' id='temperature' value=".
  number_format((float) $temperature, 2, '.', '').">";
echo "<br clear='all'>";

echo "<label>Number of Readings:&nbsp</label>";
echo "<input type='text' class='textbox25' name='numReadings' id='numReadings' value=".$numReadings.">";
echo "<br clear='all'/>";

echo '<label>Comments:&nbsp</label>';
echo '<br clear="all"/>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';


echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'>";
echo "</form>";
if ($_POST['submit']) {
   $comments = escapehtml($_POST['comments']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $pileID = escapehtml($_POST['pileID']);
   $temperature = escapehtml($_POST['temperature']);
   $numReadings = escapehtml($_POST['numReadings']);

   echo $sql = "update compost_temperature set tmpDate='".$year."-".$month."-".$day.
      "', temperature='".$temperature."', numReadings='".$numReadings."', pileId='".$pileID.
      "', comments='".$comments."' WHERE id=".$id;
   $result = mysql_query($sql);
   
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=compostTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
        "&pileID=".encodeURIComponent($origPileID).
        "&fieldID=".encodeURIComponent($origFieldID).
        "&tab=soil:soil_fert:soil_compost:compost_report\">";
   }
}
?>
