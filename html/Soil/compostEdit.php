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

$sqlget = "SELECT id, year(util_date) as yr, month(util_date) as mth, day(util_date) as dy, util_date, ".
   "fieldID, incorpTool,".
   "pileID, tperacre, incorpTiming, fieldSpread, comments FROM utilized_on where id = ".$id;
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);

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
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_compost:compost_report&year=".$origYear."&month=".$origMonth.
   "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&id=".$id.
   "&fieldID=".encodeURIComponent($origFieldID).
   "&pileID=".encodeURIComponent($origPileID)."\">";

echo "<center>";
echo "<H2> Edit Compost Record </H2>";
echo "</center>";
echo "<div class='pure-control-group'>";
echo '<label>Date:</label> ';
echo '<select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</select>';
echo '<select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</select>';
echo '<select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo "</select>";
echo "</div>";

echo "<div class='pure-control-group'>";
echo '<label>Name of Field:</label> ';
echo '<select name="fieldID" id="fieldID">';
echo '<option value="'.$fieldID.'" selected>'.$fieldID.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</select></div>';

echo "<div class='pure-control-group'>";
echo "<label>Acreage Spread:</label> ";
echo "<input type='text' class='textbox25' name='fieldSpread' id='fieldSpread' value='".$fieldSpread."'>";
echo "</div>";

echo "<div class='pure-control-group'>";
echo "<label>Tons per acre:</label> ";
echo "<input type='text' class='textbox25' name='tperacre' id='tperacre' value='".$tperacre."'>";
echo "</div>";

echo "<div class='pure-control-group'>";
echo "<label>Compost Pile ID:</label> ";
echo "<select name='pileID' id='pileID'>";
echo "<option value=\"".$pileID."\" selected>".$pileID."</selected>";
$sql = "select pileID from compost_pile";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['pileID']."\">".$row['pileID']."</option>";
}
echo "</select></div>";

echo "<div class='pure-control-group'>";
echo "<label>Incorporation Tool:</label> ";
echo "<select name='incorpTool' id='incorpTool'>";
echo "<option value='".$incorpTool."' selected>".$incorpTool."</selected>";
$sql = "Select tool_name from tools";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value='".$row['tool_name']."'>".$row['tool_name']."</option>";
}
echo "</select></div>";

echo "<div class='pure-control-group'>";
echo "<label>Incorporation Timing:</label> ";
echo "<select name='incorpTiming' id='incorpTiming'>";
echo "<option value='".$incorpTiming."' selected>".$incorpTiming."</selected>";
echo "<option value='Immediate'> Immediate </option>";
echo "<option value='Same Day'> Same Day </option>";
echo "<option value='Next Day'> Next Day </option>";
echo "<option value='Not Incorporated'> Not Incorporated </option>";
echo "</select></div>";

echo "<div class='pure-control-group'>";
echo '<label>Comments:</label> ';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo "</div>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';


echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
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

   $sql = "update utilized_on set util_date='".$year."-".$month."-".$day."', fieldID='".$fieldID.
               "', incorpTool='".$incorpTool."', pileId='".$pileID."', tperacre=".$tperacre.
               ", incorpTiming='".$incorpTiming."', fieldSpread=".$fieldSpread.", comments='".$comments.
               "' WHERE id=".$id;
    try {
       $stmt = $dbcon->prepare($sql);
       $stmt->execute();
    } catch (PDOException $p) {
       phpAlert('', $p);
       die();
    }
   
    echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
    echo '<meta http-equiv="refresh" content="0;URL=compostTable.php?year='.$origYear.'&month='.$origMonth.
      '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
      "&fieldID=".encodeURIComponent($origFieldID).
      "&pileID=".encodeURIComponent($origPileID).
      "&tab=soil:soil_fert:soil_compost:compost_report\">";
}
?>
