<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

$id=$_GET['id'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$origFieldID = $_GET['fieldID'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id,year(tilldate) as yr, month(tilldate) as mth, day(tilldate) as dy, tractorName,".
   "tilldate,fieldID, tool, num_passes, minutes, comment, percent_filled FROM tillage where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
//$user = $row['username'];
$field = $row['fieldID'];
$tractor = $row['tractorName'];
$implement = $row['tool'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$num_passes = $row['num_passes'];
$comments = $row['comment'];
$minutes = $row['minutes'];
$percent_filled = $row['percent_filled'];
echo "<form name='form' method='post' action='".$_SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_till:till_report&year=".$origYear.
   "&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&fieldID=".$origFieldID."&id=".$id."'>";
echo "<H3> Edit Tillage Record </H3>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<label>Date:&nbsp</label>';
echo '<div class="styled-select"><select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth < 13; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day < 32; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 3; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</div></select>';
echo '<br clear="all"/>';
echo '<label>Implement:&nbsp</label>';
echo '<div class="styled-select"><select name="tool" id="tool">';
echo '<option value="'.$implement.'" selected>'.$implement.' </option>';
$sql = 'select tool_name as tool from tools';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['tool'].'">'.$row['tool'].' </option>';
}
echo '</div></select>';
echo '<br clear="all"/>';
echo '<label>Tractor:&nbsp</label>';
echo '<div class="styled-select"><select name="tractor" id="tractor">';
echo '<option value="'.$tractor.'" selected>'.$tractor.' </option>';
$sql = 'select tractorName from tractor';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['tractorName'].'">'.$row['tractorName'].' </option>';
}
echo '</div></select>';

echo '<br clear="all"/>';

echo '<label>Field:&nbsp</label>';
echo '<div class="styled-select"><select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = mysql_query($sql) or die("ERROR3");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</div></select>';

echo '<br clear="all"/>';
echo '<label>Number of Passes:&nbsp</label>';
echo '<input type="text" class="textbox2"name="num_passes" id="num_passes" value="'.$num_passes.'">';


echo '<br clear="all"/>';
echo '<label>Percent Tilled:&nbsp</label>';
echo '<input type="text" class="textbox2"name="percent_filled" id="percent_filled" value="'.$percent_filled.'">';

echo '<br clear="all"/>';

echo '<label>Minutes:&nbsp</label>';
echo '<input type="text" class="textbox2" name="minutes" id="minutes" value="'.$minutes.'">';
echo '<br clear="all"/>';


echo '<label>Comments&nbsp</label>';
echo '<br clear="all"/>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\" >";
echo $comments;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'>";
echo "</form>";
if ($_POST['submit']) {
   $comSanitized = escapehtml($_POST['comments']);
   $tractor = escapehtml($_POST['tractor']);
   $implement = escapehtml($_POST['tool']);
   $fld = escapehtml($_POST['fieldID']);
   $num_passes = escapehtml($_POST['num_passes']);
   $minutes = escapehtml($_POST['minutes']);
   if ($minutes == "") {
      $minutess = 0;
   }
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $percent_filled = escapehtml($_POST['percent_filled']);
   $sql = "update tillage set tool='".$implement."', fieldID='".$fld."', tilldate='".$year."-".
     $month."-".$day."', tractorName='".$tractor."',num_passes=".$num_passes.",minutes=".$minutes.",comment='".
     $comSanitized."',percent_filled='".$percent_filled."' where id=".$id;
   $result = mysql_query($sql);
// START - check if old crop can be deleted first!!!
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content=0;URL="tillageTable.php?year='.
        $origYear.'&month='.$origMonth.'&day='.$origDay.
        '&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
        '&fieldID='.$_GET['fieldID'].
        '&tab=soil:soil_fert:soil_till:till_report>';
   }
}
?>
