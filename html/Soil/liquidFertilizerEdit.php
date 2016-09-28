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
$origFieldID = encodeURIComponent($_GET['fieldID']);
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$material = encodeURIComponent($_GET['material']);
//echo $origFieldID. 'material '.$material.' group '.$group;
$sqlget = "SELECT id, year(inputdate) as yr, month(inputdate) as mth, day(inputdate) as dy, username,".
   "fertilizer ,fieldID, dripRows, unit, quantity, comments FROM liquid_fertilizer where id = ".$id;
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
//$user = $row['username'];
$field = $row['fieldID'];
$fertilizer = $row['fertilizer'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$username = $row['username'];
$comments = $row['comments'];
$dripRows = $row['dripRows'];
$unit = $row['unit'];
$quantity = $row['quantity'];
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action='".$_SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report&year=".$origYear.
   "&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&fieldID=".$origFieldID."&material=".$material."&id=".$id."'>";

echo '<center>';
echo "<H2> Edit Liquid Fertilizer Application Record </H2>";
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
echo '<label>Fertilizer:</label> ';
echo '<select name="fertilizer" id="fertilizer">';
echo '<option value="'.$fertilizer.'" selected>'.$fertilizer.' </option>';
$sql = 'select fertilizerName from liquidFertilizerReference';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['fertilizerName'].'">'.$row['fertilizerName'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Name of Field:</label> ';
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
echo "<label>Drip Rows:</label> ";
echo "<input type='text' class='textbox25' name='dripRows' id='dripRows' value='".$dripRows."'>";
echo "</div>";

echo '<div class="pure-control-group">';
echo "<label>Quantity/Units:</label> ";
echo "<input type='text' size='8' name='quantity' id='quantity' value='".$quantity."'>";
echo "&nbsp;";
echo "<select name='unit' id='unit'>";
echo "<option value='".$unit."' selected>".$unit."</option>";
echo "<option value='QUARTS'>QUARTS</option>";
echo "<option value='GALLONS'>GALLONS</option>";
echo "</select></div>";

echo '<div class="pure-control-group">';
echo '<label>Comments:</label> ';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "</form>";
if (isset($_POST['submit'])) {
   $comSanitized = escapehtml($_POST['comments']);
   $fertilizer = escapehtml($_POST['fertilizer']);
   $fld = escapehtml($_POST['fieldID']);
   $username = escapehtml($_POST['username']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $dripRows = escapehtml($_POST['dripRows']);
   $unit = escapehtml($_POST['unit']);
   $quantity = escapehtml($_POST['quantity']);

   $sql = "update liquid_fertilizer set fieldID='".$fld."', inputdate='".$year."-".
     $month."-".$day."', fertilizer='".$fertilizer."',username='".$username."', dripRows=".$dripRows.
     ",comments='".  $comSanitized."', unit='".$unit."', quantity=".$quantity." where id=".$id;
//   echo $sql;
//   echo '<BR>';
//   echo $totalApply;
//echo $origFieldID. 'material '.$material.' group '.$group;

   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo '<meta http-equiv="refresh" content=0;URL="liquidFertTable.php?year='.$origYear.
     '&month='.$origMonth.'&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
     '&fieldID='.$origFieldID.'&material='.$material.
     '&tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report>';
}
?>
