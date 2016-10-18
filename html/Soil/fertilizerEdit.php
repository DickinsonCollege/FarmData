<?php session_start();?>
<?php

$farm = $_SESSION['db'];
if ($farm != 'dfarm') {
   try {
      $dbcon = new PDO('mysql:host=localhost;dbname=wahlst_users', 'wahlst_usercheck', 'usercheckpass',
         array(PDO::MYSQL_ATTR_INIT_COMMAND => 'set sql_mode="TRADITIONAL"'));
      $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
      die ("Connect Failed! :".$e->getMessage());
   }
   $sql="select username from users where dbase='".$_SESSION['db']."'";
   try {
      $result = $dbcon->query($sql);
   } catch (PDOException $p) {
      die($p->getMessage());
   }

   $useropts='';
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
$sqlget = "SELECT id,year(inputdate) as yr, month(inputdate) as mth, day(inputdate) as dy, username,".
   "fertilizer ,fieldID, crops, rate, numBeds, totalApply, hours, comments FROM fertilizer where id = ".$id;
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
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
$hours = $row['hours'];
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
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['fertilizerName'].'">'.$row['fertilizerName'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Field:</label> ';
echo '<select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>User Name:</label> ';
echo '<select name="username" id="username">';
echo '<option value="'.$username.'" select>'.$username.'</option>';
if ($farm == 'dfarm') {
    $sql = 'select username from users where active = 1';
    $sqldata = $dbcon->query($sql);
   while($row = $sqldata->fetch(PDO::FETCH_ASSOC)){
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

if ($_SESSION['labor']) {
   echo '<div class="pure-control-group">';
   echo '<label>Hours:</label>';
   echo '<input type="text" class="textbox2" name="hours" id="hours" value="'.$hours.'">';
   echo '</div>';
}

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
   $hours = 0;
   if ($_SESSION['labor']) {
      $hours = escapehtml($_POST['hours']);
      if ($hours == "") {
         $hours = 0;
      }
   }
   $sql = "update fertilizer set crops='".$crops."', fieldID='".$fld."', inputdate='".$year."-".
     $month."-".$day."', fertilizer='".$fertilizer."',username='".$username."',numBeds=".$numBeds.
     ",comments='".  $comSanitized."',rate=".$rate.", totalApply=".$totalApply.", hours=".$hours.
     " where id=".$id;

   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
     phpAlert('', $p);
     die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo '<meta http-equiv="refresh" content=0;URL="fertTable.php?year='.$origYear.'&month='.$origMonth.
     '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.'&fieldID='.$origFieldID.'&crop='.$origCrops.'&material='.$material.
     '&tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report>';
}
?>
