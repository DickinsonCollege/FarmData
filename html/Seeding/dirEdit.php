<?php session_start();?>
<?php
$farm = $_SESSION['db'];
if ($farm != 'dfarm') {
   try {
      $dbcon = new PDO('mysql:host=localhost;dbname=wahlst_users', 'wahlst_usercheck', 'usercheckpass');
      $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $d) {
      die($d->getMessage());
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
$origCrop = $_GET['crop'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$origField = $_GET['fieldID'];
$origGen = $_GET['genSel'];


$sqlget = "SELECT gen,id,year(plantdate) as yr, month(plantdate) as mth, day(plantdate) as dy, crop, ".
   "username, plantdate, fieldID, bedft, rowsBed, hours, annual, year(lastHarvest) as lastYear, comments ".
   "FROM dir_planted where id = ".$id;
try {
   $sqldata = $dbcon->query($sqlget);
} catch (PDOException $p) {
   phpAlert('', $p);
}
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
$user = $row['username'];
$egen = $row['gen'];
$field = $row['fieldID'];
$bedftv = $row['bedft'];
$rowsBed = $row['rowsBed'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$curCrop = $row['crop'];
$comments = $row['comments'];
$hours = $row['hours'];
$annual = $row['annual'];
$lastYear = $row['lastYear'];

echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=seeding:direct:direct_report&year=".$origYear."&month=".$origMonth."&day=".$origDay.
    "&fieldID=".$origField."&genSel=".$origGen.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop=".
    encodeURIComponent($origCrop)."&id=".$id."\">";
echo '<center>';
echo "<H2> Edit Direct Planting Record </H2>";
echo '</center>';
echo '<fieldset>';
echo '<div class="pure-control-group">';
echo '<label>Date:</label>';
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
echo '<label>Crop:</label>';
echo '<select name="crop" id="crop">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant where active=1';
try {
   $sqldata = $dbcon->query($sql);
} catch (PDOException $p) {
   phpAlert('', $p);
}
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Name of Field:</label>';
echo '<select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
try {
   $sqldata = $dbcon->query($sql);
} catch (PDOException $p) {
   phpAlert('', $p);
}
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group" id="annualdiv">';
echo '<label>Annual:</label>';
echo '<select name="annual" id="annual" class="mobile-select" onchange="addLastHarvestDate();">';
if ($annual == 1) {
   echo "<option value=1 selected>Annual</option>";
   echo "<option value=0>Perennial</option> ";
} else {
   echo "<option value=0 selected>Perennial</option> ";
   echo "<option value=1>Annual</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group" id="lastharvdiv">';
echo '</div>';

include $_SERVER['DOCUMENT_ROOT'].'/Seeding/annual.php';

echo '<div class="pure-control-group">';
echo '<label>User:</label>';
echo '<select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.' </option>';
if ($farm == 'dfarm') {
   $sql = 'select username from users where active = 1';
   try {
      $sqldata = $dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
   }
   while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo '<option value="'.$row['username'].'">'.$row['username'].' </option>';
   }
} else {
   echo $useropts;
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Bed Feet:</label>';
echo '<input type="text" class="textbox3" name="bedftv" id="bedftv" value="'.$bedftv.'">';
echo '</div>';

echo '<div class="pure-control-group">';
echo '<label>Rows/Bed:</label>';
echo '<select name="rowsbed" id="rowsbed">';
echo '<option value='.$rowsBed.' selected>'.$rowsBed.' </option>';
for ($row = 1; $row <= 9; $row++) {
   echo '<option value='.$row.'>'.$row.'</option>';
}
echo '</select></div>';

include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/getGen.php';
if ($_SESSION['labor']) {
   echo '<div class="pure-control-group">';
   echo '<label>Hours:</label>';
   echo '<input type="text" class="textbox2" name="hours" id="hours" value="'.$hours.'">';
   echo '</div>';
}

echo '<div class="pure-control-group">';
echo '<label>Comments:</label>';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '</div>';
echo '<br clear="all"/>';
?>

<script type="text/javascript">
window.onload=function() {addLastHarvestDate();}
</script>

<?php
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo '</fieldset>';
echo "</form>";
if ($_POST['submit']) {
   $comSanitized=escapehtml($_POST['comments']);
   $bedftv = escapehtml($_POST['bedftv']);
   $numrows = escapehtml($_POST['rowsbed']);
   $fld = escapehtml($_POST['fieldID']);
   $crop = escapehtml($_POST['crop']);
   if ($_SESSION['labor']) {
      $hours = escapehtml($_POST['hours']);
      if ($hours == "") {
         $hours = 0;
      }
   }
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $user = escapehtml($_POST['user']);
   $annual = escapehtml($_POST['annual']);
   if ($annual == 1) {
      $lastYear = $_POST['year'];
   } else {
      $lastYear = $_POST['lastYear'];
   }
   include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';
   $sql = "update dir_planted set username='".$user."', fieldID='".$fld."', plantdate='".$year."-".
     $month."-".$day."', bedft=".$bedftv.",rowsBed=".$numrows.",hours=".$hours.",comments='".
     $comSanitized."',crop='".$crop."',gen=".$gen.", annual = ".$annual.", lastHarvest = '".
     $lastYear."-12-31' where id=".$id;
   try {
      $result = $dbcon->prepare($sql);
      $result->execute();
   } catch (PDOException $p) {
      phpAlert('Could not update data', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo "<meta http-equiv=\"refresh\" content=\"0;URL=dir_table.php?year=".$origYear."&month=".$origMonth.
     "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
     "&fieldID=".$origField."&genSel=".$origGen.
     "&tab=seeding:direct:direct_report"
     ."&crop=".encodeURIComponent($origCrop)."\">";
}
?>
