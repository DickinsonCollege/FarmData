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

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origCrop = $_GET['transferredCrop'];
$origField = $_GET['fieldID'];
$origGen = $_GET['genSel'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, gen, year(transdate) as tyr, month(transdate) as tmth, day(transdate) as tdy, ".
   "crop, username, year(seedDate) as syr, month(seedDate) as smth, day(seedDate) as sdy, transdate, ".
   "seedDate, fieldID, bedft, rowsBed, hours, flats, comments, annual, year(lastHarvest) as lastYear, ".
   "month(lastHarvest) as lastMonth, day(lastHarvest) as lastDay ".
   "FROM transferred_to WHERE id = ".$id;

$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);

$id = $row['id'];
$egen = $row['gen'];
$seedDay = $row['sdy'];
$seedMonth = $row['smth'];
$seedYear = $row['syr'];
$transDay = $row['tdy'];
$transMonth = $row['tmth'];
$transYear = $row['tyr'];

$curCrop = $row['crop'];
$user = $row['username'];
$transdate = $row['transdate'];
$seedDate = $row['seedDate'];
$fieldID = $row['fieldID'];
$bedftv = $row['bedft'];
$rowsBed = $row['rowsBed'];
$hours = $row['hours'];
$flats = $row['flats'];
$comments = escapeescapehtml($row['comments']);
$annual = $row['annual'];
$lastYear = $row['lastYear'];
$lastMonth = $row['lastMonth'];
$lastDay = $row['lastDay'];
?>

<script type='text/javascript'>
function updateSeedDate() {
   var crop = document.getElementById('crop').value;
   var cropEnc = encodeURIComponent(crop);

   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "update_trans.php?crop="+cropEnc, false);
   xmlhttp.send();
   
        var cur = "";
        if (crop == "<?php echo $curCrop;?>") {
           var dt = "<?php echo $seedDate;?>";
           if (dt != '0000-00-00') {
              cur = '<option value="'+dt+'">'+dt+'</option>';
           }
        }
   var seedDates = document.getElementById('seedDateDiv');
   seedDates.innerHTML = '<div class="pure-control-group" id="seedDateDiv">' +
      "<label>Date Seeded:</label>" +
      "<select name='seedDate' id='seedDate'>" + cur +
      xmlhttp.responseText + 
      "</select></div>";
} 
window.onload=function(){updateSeedDate();};
</script>

<?php
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=seeding:transplant:transplant_report&year=".$origYear."&month=".
   $origMonth."&day=".$origDay.  "&tyear=".$tcurYear."&tmonth=".$tcurMonth.
   "&fieldID=".encodeURIComponent($origField)."&genSel=".$origGen.
   "&tday=".$tcurDay."&transferredCrop=".encodeURIComponent($origCrop)."&id=".$id."\">";

echo "<center>";
echo "<H2> Edit Transplant Seeding Report </H2>";
echo "</center>";

echo '<fieldset>';
echo '<div class="pure-control-group">';
echo "<label>Date Transplanted:</label>";
echo "<select name='month' id='month'>";
echo '<option value='.$transMonth.' selected>'.date("F", mktime(0,0,0, $transMonth,10)).' </option';
   for ($mth = 1; $mth <= 12; $mth++) {
      echo "\n <option value='$mth'>".date("F", mktime(0,0,0, $mth, 10))."</option>";
   }
echo "</select>";
echo "<select name='day' id='day'>";
echo "<option value='".$transDay."' selected>".$transDay."</option>";
   for ($day = $transDay - $transDay+1; $day <= 31; $day++) {
      echo "\n <option value='".$day."'>".$day."</option>";
   }
echo "</select>";
echo "<select name='year' id='year'>";
echo "<option value='".$transYear."' selected>".$transYear."</option>";
   for ($yr = $transYear - 4; $yr < $transYear + 5; $yr++) {
      echo "\n <option value='".$yr."'>".$yr."</option>";
   }
echo "</select>";
echo "</div>";

echo '<div class="pure-control-group">';
echo '<label>Crop:</label>';
echo '<select name="crop" id="crop" onchange="updateSeedDate();">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select distinct crop from gh_seeding WHERE year(seedDate)=year(now())';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</select>';
echo '</div>';

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

echo '<div class="pure-control-group" id="seedDateDiv">';
echo "<label>Date Seeded:</label>";
echo "<select name='seedDate' id='seedDate'>";
echo "<option value='".$seedDate."' selected>".$seedDate."</option>";
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Username:</label>';
echo '<select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.'</option>';
if ($farm == 'dfarm') {
   $sql = 'select username from users where active = 1';
   $sqldata = $dbcon->query($sql);
   while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo '<option value="'.$row['username'].'">'.$row['username'].'</option>';
   }
} else {
   echo $useropts;
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo "<label>Name of Field:</label>";
echo "<select name='fieldID' id='fieldID'>";
echo "<option value='".$fieldID."' selected>".$fieldID."</option>";
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value='".$row['fieldID']."'>".$row['fieldID']."</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo "<label>Bed Feet Planted:</label>";
echo "<input type='text' class='textbox2' name='bedfeet' id='bedfeet' value='".$bedftv."'>";
echo "</div>";

echo '<div class="pure-control-group">';
echo "<label>Rows per Bed:</label>";
echo '<select name="rowsbed" id="rowsbed">';
echo '<option value='.$rowsBed.' selected>'.$rowsBed.' </option>';
for ($row = 1; $row <= 7; $row++) {
   echo '<option value='.$row.'>'.$row.'</option>';
}
echo '</select></div>';

// echo "<input type='text' class='textbox2' name='rowsbed' id='rowsbed' value='".$rowsBed."'>";

echo '<div class="pure-control-group">';
echo "<label>Number of Trays:</label>";
echo "<input type='text' class='textbox2' name='flats' id='flats' value='".$flats."'>";
echo "</div>";

include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/getGen.php';
if ($_SESSION['labor']) {
   echo '<div class="pure-control-group">';
   echo "<label>Hours Worked:</label>";
   echo "<input type='text' class='textbox2' name='hours' id='hours' value='".$hours."'>";
   echo "</div>";
}

echo '<div class="pure-control-group">';
echo '<label>Comments:</label>';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
$comarr = explode("<br>", $comments);
foreach ($comarr as $com) {
   echo $com;
   echo "\n";
}
echo "</textarea>";
echo "</div>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';

?>
<script type="text/javascript">
window.onload=function() {addLastHarvestDate();}
</script>

<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>
</fieldset>
</form>

<?php
if ($_POST['submit']) {
   $username = escapehtml($_POST['user']);
   $comments=str_replace("\n", "<br>", trim(escapehtml($_POST['comments'])));
   $crop = escapehtml($_POST['crop']);
   $fieldID = escapehtml($_POST['fieldID']);
   $flats = escapehtml($_POST['flats']);
   $bedftv = escapehtml($_POST['bedfeet']);
   $rowsbed = escapehtml($_POST['rowsbed']);
   if ($_SESSION['labor']) {
      $hours = escapehtml($_POST['hours']);
   }
   $seedDate = escapehtml($_POST['seedDate']);
   $transYear = escapehtml($_POST['year']);
   $transMonth = escapehtml($_POST['month']);
   $transDay = escapehtml($_POST['day']);  
   include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';
   $annual = escapehtml($_POST['annual']);
   if ($annual == 1) {
      $lastYear = $_POST['year'];
      $lastMonth = 12;
      $lastDay = 31;
   } else {
      $lastYear = $_POST['lastYear'];
      $lastMonth = $_POST['lastMonth'];
      $lastDay = $_POST['lastDay'];
   }
 
   $sql = "update transferred_to set username='".$username."',crop='".$crop."', seedDate='".$seedDate."', 
      transdate='".$transYear."-".$transMonth."-".$transDay."', flats='".$flats."', bedft='"
      .$bedftv."', rowsBed='".$rowsbed."', hours='".$hours."', comments='".$comments."', fieldID='".
      $fieldID."',gen=".$gen.", annual = ".$annual.", lastHarvest = '".
      $lastYear."-".$lastMonth."-".$lastDay."' WHERE id=".$id;

   try {
//      $dbcon->query("SET SESSION sql_mode = 'ALLOW_INVALID_DATES'");
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
       phpAlert('Could not update transplant data', $p);
       die();
   } 
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo '<meta http-equiv="refresh" content="0;URL=transTable.php?year='.$origYear.'&month='.$origMonth.
     '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
     "&transferredCrop=".encodeURIComponent($origCrop).
     "&fieldID=".encodeURIComponent($origField)."&genSel=".$origGen.
     "&tab=seeding:transplant:transplant_report&submit=Submit\">";
}

?>
