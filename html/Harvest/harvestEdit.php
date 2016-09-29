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
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $useropts.='<option value="'.$row['username'].'">'.$row['username'].'</option>';
   }
}

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$origCrop = $_GET['crop'];
$origField = $_GET['fieldID'];
$genSel = $_GET['genSel'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT gen, id, year(hardate) as yr, month(hardate) as mth, day(hardate) as dy, crop, username,".
   "hardate, fieldID, yield, unit, hours, comments FROM harvested where id = ".$id;
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
$user = $row['username'];
$egen = $row['gen'];
$field = $row['fieldID'];
$yield = $row['yield'];
$unit = $row['unit'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$curCrop = $row['crop'];
$comments = $row['comments'];
$hours = $row['hours'];
?>

<script type="text/javascript">
function addInput2(){
   var newdiv = document.getElementById('fieldID2');
   var crop = encodeURIComponent(document.getElementById("crop").value);
   var year = document.getElementById("year").value;
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "update_field.php?crop="+crop+"&plantyear="+year, false);
   xmlhttp.send();
   var cur="";
   if (crop=="<?php echo $curCrop;?>") {
      var fld = "<?php echo $field;?>";
      cur = '<option value="'+fld+'">'+fld+'</option>';
   }
   newdiv.innerHTML= '<div class="pure-control-group" id="fieldID2">' +
        '<label>Name of Field:</label>' +
        '<select name="fieldID" id="fieldID">' + cur +
     xmlhttp.responseText+"</select> </div>";
}

function addInput(){
   var newdiv = document.getElementById('unitInput');
   var crp = encodeURIComponent(document.getElementById("crop").value);
   var farm = encodeURIComponent("<?php echo $farm;?>");
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getUnit.php?crop="+crp+"&farm="+farm, false);
   xmlhttp.send();
   var unit = "<?php echo $unit;?>";
   newdiv.innerHTML= '<div class="pure-control-group" id="unitInput">' +
       '<label>Unit:</label>' +
       '<select name="unit" id="unit">' +
      // '<option value="' + unit + '">' + unit + '</option>' +
       xmlhttp.responseText + 
       "</select> </div>";
}
</script>

<?php
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=harvest:harvestReport&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&genSel=".$genSel."&fieldID=".encodeURIComponent($origField).
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop=".
   encodeURIComponent($origCrop)."&id=".$id."\">";

echo "<center>";
echo "<H2> Edit Harvest Record </H2>";
echo "</center>";
echo '<fieldset>';

echo '<div class="pure-control-group">';
echo '<label>Date:</label>';

echo '<select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";}
echo '</select>';

echo '<select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";}
echo '</select>';

echo '<select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Crop:</label>';
echo '<select name="crop" id="crop" onchange="addInput2();addInput();">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant where active=1';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</select></div>';
echo '<div class="pure-control-group">';
echo '<label>User:</label>';
echo '<select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.' </option>';
if ($farm == 'dfarm') {
   $sql = 'select username from users where active = 1';
   $sqldata = $dbcon->query($sql);
   while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo '<option value="'.$row['username'].'">'.$row['username'].' </option>';
   }
} else {
   echo $useropts;
}
echo '</select></div>';
// echo '<br clear="all"/>';

echo '<div class="pure-control-group" id="fieldID2">';
echo '<label>Name of Field:</label>';
echo '<select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
echo '</select></div>';
// echo '<br clear="all"/>';

echo '<div class="pure-control-group">';
echo '<label>Yield:</label>';
echo '<input type="text" class="textbox3" name="yield" id="yield" value="'.$yield.'">';
echo '</div>';

echo '<div class="pure-control-group" id="unitInput">';
echo '<label>Unit:</label>';
echo '<select name="unit" id="unit">';
echo '<option value='.$unit.' selected>'.$unit.' </option>';
//TODO: Add different unit conversions
echo '</select></div>';

include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/getGen.php';
if ($_SESSION['labor']) {
  echo '<div class="pure-control-group">';
  echo '<label>Hours:</label>';
  echo '<input type="text" class="textbox25" name="hours" id="hours" value="'.
           number_format((float) $hours, 2, '.', '').'">';
  echo '</div>';
}

echo '<div class="pure-control-group">';
echo '<label>Comments:</label>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '</div>';
echo '<br clear="all"/>';
?>
<script type="text/javascript">
window.onload=function(){addInput(); addInput2();};
</script>

<?php

echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo '<fieldset>';
echo "</form>";
if ($_POST['submit']) {
   $comSanitized = escapehtml($_POST['comments']);
   $fld = escapehtml($_POST['fieldID']);
   $crop = escapehtml($_POST['crop']);
   $unit = escapehtml($_POST['unit']);
   $yield = escapehtml($_POST['yield']);
   if ($yield == "" || $yield < 0) {
      $yield = 0;
   }
   if ($_SESSION['labor']) {
      $hours = escapehtml($_POST['hours']);
      if ($hours == "" || $hours < 0) {
         $hours = 0;
      }
   }
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $user = escapehtml($_POST['user']);
   include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';

   $sql = "update harvested set username='".$user."', fieldID='".$fld."', hardate='".$year."-".
     $month."-".$day."', yield=".$yield;
   if ($farm != 'wahlst_spiralpath') {
      $sql .= "/(Select conversion from units where crop= '".
           $crop."' and unit= '".$unit."')";
   }
   $sql .= ",hours=".$hours.",comments='".
     $comSanitized."',crop='".$crop."',gen=".$gen.",unit = ";
   if ($farm == 'wahlst_spiralpath') {
     $sql .= "'".$unit."'";
   } else {
     $sql .= "(select units from".  " plant where crop='".$crop."')";
   }
   $sql .= " where id=".$id;
   try {
     $prep = $dbcon->prepare($sql);
     $prep->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo "<meta http-equiv=\"refresh\" content=\"0;URL=harvestTable.php?year=".$origYear.'&month='
     .$origMonth.'&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
     "&crop=".encodeURIComponent($origCrop).
     "&genSel=".$genSel."&fieldID=".encodeURIComponent($origField).
     "&tab=harvest:harvestReport\">";
}
?>
