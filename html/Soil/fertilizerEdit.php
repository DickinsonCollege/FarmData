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
$origCrops 	  = $_GET['crop'];
//echo $origFieldID. 'material '.$material.' group '.$group;
$sqlget = "SELECT id,year(inputdate) as yr, month(inputdate) as mth, day(inputdate) as dy, username,".
   "fertilizer ,fieldID, crops, rate, numBeds, totalApply, comments FROM fertilizer where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
//$user = $row['username'];
$field = $row['fieldID'];
$fertilizer = $row['fertilizer'];
$crops = $row['crops'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$username = $row['username'];
$comments = $row['comments'];
$numBeds = $row['numBeds'];
$rate = $row['rate'];
$totalApply = $row['totalApply'];
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action='".$_SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report&year=".$origYear.
   "&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop=".$origCrops."&fieldID=".$origFieldID."&material=".$material."&id=".$id."'>";

echo '<center>';
echo "<H2> Edit Dry Fertilizer Application Record </H2>";
echo '</center>';
echo '<div class="pure-control-group">';
echo '<label>Date:</label> ';
echo '<select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth < 13; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</select>';
echo '<select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day < 32; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</select>';
echo '<select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 3; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Crops:</label> ';
echo '<textarea name="crops">'.$crops.'</textarea>';
echo '</div>';

echo '<div class="pure-control-group">';
echo '<label>Fertilizer:</label> ';
echo '<select name="fertilizer" id="fertilizer">';
echo '<option value="'.$fertilizer.'" selected>'.$fertilizer.' </option>';
$sql = 'select fertilizerName from fertilizerReference';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fertilizerName'].'">'.$row['fertilizerName'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Field:</label> ';
echo '<select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = mysql_query($sql) or die("ERROR3");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>User Name:</label> ';
echo '<select name="username" id="username">';
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
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Rate of Application (lbs/acre):</label> ';
echo '<input type="text" class="textbox25" name="rate" id="rate" value="'.$rate.'">';
echo '</div>';

echo '<div class="pure-control-group">';
echo '<label>Number of Beds:</label> ';
echo '<input type="text" class="textbox2" name="numBeds" id="numBeds" value="'.$numBeds.'">';
echo '</div>';

echo '<div class="pure-control-group">';
echo '<label>Total Material Applied:</label> ';
echo '<input type="text" class="textbox3" name="totalApply" id="totalApply" value="'.$totalApply.'">';
echo '</div>';

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
if (isset($_POST['submit'])) {
   $comSanitized = escapehtml($_POST['comments']);
   $fertilizer = escapehtml($_POST['fertilizer']);
   $crops = escapehtml($_POST['crops']);
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
   $sql = "update fertilizer set crops='".$crops."', fieldID='".$fld."', inputdate='".$year."-".
     $month."-".$day."', fertilizer='".$fertilizer."',username='".$username."',numBeds=".$numBeds.",comments='".
     $comSanitized."',rate=".$rate.", totalApply=".$totalApply." where id=".$id;

   $result = mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content=0;URL="fertTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.'&fieldID='.$origFieldID.'&crop='.$origCrops.'&material='.$material.
        '&tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report>';
   }
}
?>
