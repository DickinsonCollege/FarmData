<?php session_start();?>
<?php

$farm = $_SESSION['db'];
if ($farm != 'dfarm') {
   $dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or 
       die ("Connect Failed! :".mysql_error());
   mysql_select_db('wahlst_users');
   $sql="select username from users where dbase='".$_SESSION['db']."'";
   $result = mysql_query($sql);
   echo mysql_error();
   $useropts='';
   while ($row = mysql_fetch_array($result)) {
      $useropts.='<option value="'.$row['username'].'">'.$row['username'].'</option>';
   }
}

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
$material = $_GET['material'];
$group 	  = $_GET['group'];
//echo $origFieldID. 'material '.$material.' group '.$group;
$sqlget = "SELECT id,year(inputdate) as yr, month(inputdate) as mth, day(inputdate) as dy, username,".
   "fertilizer ,fieldID, cropGroup, rate, numBeds, totalApply, comments FROM fertilizer where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
//$user = $row['username'];
$field = $row['fieldID'];
$fertilizer = $row['fertilizer'];
$cropGroup = $row['cropGroup'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$username = $row['username'];
$comments = $row['comments'];
$numBeds = $row['numBeds'];
$rate = $row['rate'];
$totalApply = $row['totalApply'];
echo "<form name='form' method='post' action='".$_SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deletesoil:deletefert:deletefertilizer:deletedryfertilizer&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&group=".$group."&fieldID=".$origFieldID."&material=".$material."&id=".$id."'>";
/*
echo '<input type="hidden" name="oldCrop" value="'.$cropGroup.'">';
echo '<input type="hidden" name="oldField" value="'.$field.'">';
*/

echo "<H3> Edit Dry Fertilizer Application Record </H3>";
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
echo '<label>Crop Group:&nbsp</label>';
echo '<div class="styled-select"><select name="cropGroup" id="cropGroup">';
echo '<option value="'.$cropGroup.'" selected>'.$cropGroup.' </option>';
$sql = 'select cropGroup from cropGroupReference';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['cropGroup'].'">'.$row['cropGroup'].' </option>';
}
echo '</div></select>';
echo '<br clear="all"/>';
echo '<label>Fertilizer:&nbsp</label>';
echo '<div class="styled-select"><select name="fertilizer" id="fertilizer">';
echo '<option value="'.$fertilizer.'" selected>'.$fertilizer.' </option>';
$sql = 'select fertilizerName from fertilizerReference';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fertilizerName'].'">'.$row['fertilizerName'].' </option>';
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
echo '<label>User Name:&nbsp</label>';
echo '<div class="styled-select"><select name="username" id="username">';
echo '<option value="'.$username.'" select>'.$username.'</option>';
if ($farm == 'dfarm') {
    $sql = 'select username from users where active = 1';
    $sqldata = mysql_query($sql) or die(mysql_error());
   while($row = mysql_fetch_array($sqldata)){
	echo '<option value="'.$row['username'].'">'.$row['username'].'</option>';
   }
} else {
   echo $useropts;
}
  
//echo '<input type="text" class="textbox3" name="username" id="username" value="'.$username.'">';

echo '</select></div>';
echo '<br clear="all"/>';
echo '<label>Rate of Application (lbs/acre):&nbsp</label>';
echo '<input type="text" class="textbox25"name="rate" id="rate" value="'.$rate.'">';

echo '<br clear="all"/>';

echo '<label>Number of Beds:&nbsp</label>';
echo '<input type="text" class="textbox2" name="numBeds" id="numBeds" value="'.$numBeds.'">';
echo '<br clear="all"/>';

echo '<label>Total Material Applied:&nbsp</label>';
echo '<input type="text" class="textbox3" name="totalApply" id="totalApply" value="'.$totalApply.'">';
echo '<br clear="all"/>';

echo '<label>Comments:&nbsp</label>';
echo '<br clear="all"/>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'>";
echo "</form>";
if (isset($_POST['submit'])) {
   $comSanitized = escapehtml($_POST['comments']);
   $fertilizer = escapehtml($_POST['fertilizer']);
   $cropGroup = escapehtml($_POST['cropGroup']);
   $fld = escapehtml($_POST['fieldID']);
   $username = escapehtml($_POST['username']);
   $numBeds = escapehtml($_POST['numBeds']);
   if ($numBeds == "") {
      $numBedss = 0;
   }
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $rate = escapehtml($_POST['rate']);
   $totalApply = escapehtml($_POST['totalApply']);
   $sql = "update fertilizer set cropGroup='".$cropGroup."', fieldID='".$fld."', inputdate='".$year."-".
     $month."-".$day."', fertilizer='".$fertilizer."',username='".$username."',numBeds=".$numBeds.",comments='".
     $comSanitized."',rate=".$rate.", totalApply=".$totalApply." where id=".$id;
//   echo $sql;
//   echo '<BR>';
//   echo $totalApply;
//echo $origFieldID. 'material '.$material.' group '.$group;

   $result = mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content=0;URL="fertilizerTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.'&fieldID='.$origFieldID.'&group='.$group.'&material='.$material.
        '&tab=admin:admin_delete:deletesoil:deletefert:deletefertilizer:deletedryfertilizer>';
   }
}
?>
