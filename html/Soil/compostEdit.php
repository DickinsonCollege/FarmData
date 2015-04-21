<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origFieldID = $_GET['fieldID'];
$origPileID = $_GET['pileID'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$origCrop = $_GET['crop'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, year(util_date) as yr, month(util_date) as mth, day(util_date) as dy, util_date, fieldID, incorpTool,".
   "pileID, tperacre, incorpTiming, fieldSpread, comments FROM utilized_on where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$util_date = $row['util_date'];
$fieldID = $row['fieldID'];
$incorpTool = $row['incorpTool'];
$pileID = $row['pileID'];
$tperacre = $row['tperacre'];
$incorpTiming = $row['incorpTiming'];
$id = $row['id'];
$fieldSpread = $row['fieldSpread'];
$comments = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
?>

<?php
echo "<form name='form' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_compost:compost_report&year=".$origYear."&month=".$origMonth.
   "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&id=".$id.
   "&fieldID=".encodeURIComponent($origFieldID).
   "&pileID=".encodeURIComponent($origPileID)."\">";

echo "<H3> Edit Compost Record </H3>";
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

echo '<label>Field ID:&nbsp</label>';
echo '<div class="styled-select"><select name="fieldID" id="fieldID">';
echo '<option value="'.$fieldID.'" selected>'.$fieldID.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = mysql_query($sql) or die();
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</select></div>';
echo '<br clear="all"/>';

echo "<label>Acreage Spread:&nbsp</label>";
echo "<input type='text' class='textbox25' name='fieldSpread' id='fieldSpread' value='".$fieldSpread."'>";
echo "<br clear='all'>";

echo "<label>Tons per acre:&nbsp</label>";
echo "<input type='text' class='textbox25' name='tperacre' id='tperacre' value='".$tperacre."'>";
echo "<br clear='all'/>";

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

echo "<label>Incorporation Tool:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='incorpTool' id='incorpTool'>";
echo "<option value='".$incorpTool."' selected>".$incorpTool."</selected>";
$sql = "Select tool_name from tools";
$result = mysql_query($sql) or die();
while ($row = mysql_fetch_array($result)) {
	echo "<option value='".$row['tool_name']."'>".$row['tool_name']."</option>";
}
echo "</select></div>";
echo "<br clear='all'>";

echo "<label>Incorporation Timing:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='incorpTiming' id='incorpTiming'>";
echo "<option value='".$incorpTiming."' selected>".$incorpTiming."</selected>";
echo "<option value='Immediate'> Immediate </option>";
echo "<option value='Same Day'> Same Day </option>";
echo "<option value='Next Day'> Next Day </option>";
echo "<option value='Not Incorporated'> Not Incorporated </option>";
echo "</select></div>";
echo "<br clear='all'>";

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
	$fieldID = escapehtml($_POST['fieldID']);
	$incorpTool = escapehtml($_POST['incorpTool']);
	$pileID = escapehtml($_POST['pileID']);
	$tperacre = escapehtml($_POST['tperacre']);
	$incorpTiming = escapehtml($_POST['incorpTiming']);
	$fieldSpread = escapehtml($_POST['fieldSpread']);

	$sql = "update utilized_on set util_date='".$year."-".$month."-".$day."', fieldID='".$fieldID."', 
		incorpTool='".$incorpTool."', pileId='".$pileID."', tperacre=".$tperacre.", 
		incorpTiming='".$incorpTiming."', fieldSpread=".$fieldSpread.", comments='".$comments."'
		WHERE id=".$id;
   $result = mysql_query($sql);
   
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=compostTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
        "&fieldID=".encodeURIComponent($origFieldID).
        "&pileID=".encodeURIComponent($origPileID).
        "&tab=soil:soil_fert:soil_compost:compost_report\">";
   }
}
?>
