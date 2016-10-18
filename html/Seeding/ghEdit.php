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
$origCrop = $_GET['crop'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$genSel = $_GET['genSel'];

$sqlget = "SELECT id, gen, year(seedDate) as yr, month(seedDate) as mth, day(seedDate) as dy, crop, username, 
    seedDate, numseeds_planted, comments, varieties, flats, cellsFlat FROM gh_seeding where id = ".$id;

$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);

$id = $row['id'];
$egen = $row['gen'];
$curDay = $row['dy'];
$curMonth = $row['mth'];
$curYear = $row['yr'];
$user = $row['username'];
$curCrop = $row['crop'];
$seedDate = $row['seedDate'];
$numseeds_planted = $row['numseeds_planted'];
$comments = escapeescapehtml($row['comments']);
$varieties = escapeescapehtml($row['varieties']);
$flats = $row['flats'];
$cellsFlat = $row['cellsFlat'];

echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=seeding:flats:flats_report&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&genSel=".$genSel."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop=".
   encodeURIComponent($origCrop)."&id=".$id."\">";

echo "<center>";
echo "<H2> Edit Tray Seeding Record </H2>";
echo "</center>";
echo "<fieldset>";
echo '<div class="pure-control-group">';
echo '<label>Date:</label>';
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
echo '</select>';
echo '</div>';

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
echo '<label>Crop:</label>';
echo '<select name="crop" id="crop">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant where active=1';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo "<label>Number of Seeds Planted:</label>";
echo "<input type='text' class='textbox2' name='numseeds_planted' id='numseeds_planted' value='".$numseeds_planted."'>";
echo '</div>';

echo '<div class="pure-control-group">';
echo "<label>Varieties:</label>";
echo "<textarea rows=\"5\" cols=\"30\" name = \"varieties\" id = \"varieties\">";
$vararr = explode("<br>", $varieties);
foreach ($vararr as $var) {
   echo $var;
   echo "\n";
}
echo "</textarea>";
echo '</div>';

echo '<div class="pure-control-group">';
echo "<label>Trays:</label>";
echo "<input type='text' class='textbox2' name='flats' id='flats' value='".$flats."'>";
echo '</div>';

echo '<div class="pure-control-group">';
echo "<label>Tray size:</label>";
echo '<select name ="cellsFlat" id="cellsFlat" class="mobile-select">';
echo "\n<option value=".$cellsFlat.">".$cellsFlat."</option>";
$sql = "select cells from flat";
$result = $dbcon->query($sql);
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)) {
   echo "\n<option value=".$row1['cells'].">".$row1['cells']."</option>";
}
echo '</select>';
echo '</div>';

include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/getGen.php';

echo '<div class="pure-control-group">';
echo '<label>Comments:</label>';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
$comarr = explode("<br>", $comments);
foreach ($comarr as $com) {
   echo $com;
   echo "\n";
}
echo "</textarea>";
echo '</div>';
echo '<br clear="all"/>';
echo '<br clear="all"/>';

echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "<fieldset>";
echo "</form>";
if ($_POST['submit']) {
    $comSanitized=str_replace("\n", "<br>", trim(escapehtml($_POST['comments'])));
    $crop = escapehtml($_POST['crop']);
    $numseeds_planted = escapehtml($_POST['numseeds_planted']);
    // $varieties = escapehtml($_POST['varieties']);
    $varieties=str_replace("\n", "<br>", trim(escapehtml($_POST['varieties'])));
    $flats = escapehtml($_POST['flats']);
    $cellsFlat = escapehtml($_POST['cellsFlat']);
    $year = escapehtml($_POST['year']);
    $month = escapehtml($_POST['month']);
    $day = escapehtml($_POST['day']);
    $user = escapehtml($_POST['user']);
    include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';
   
    $sql = "update gh_seeding set username='".$user."',crop='".$crop."', seedDate='".$year."-".
        $month."-".$day."', numseeds_planted=".$numseeds_planted.", comments='".$comSanitized."', 
        varieties='".$varieties."', flats=".$flats.", cellsFlat=".$cellsFlat.
                ",gen=".$gen." WHERE id=".$id;
    try {
       $stmt = $dbcon->prepare($sql);
       $stmt->execute();
    } catch (PDOException $p) {
       phpAlert('Could not update tray seeding record', $p);
       die();
    }
   
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo "<meta http-equiv=\"refresh\" content=\"0;URL=gh_table.php?year=".$origYear.'&month='.$origMonth.
     '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
     "&crop=".encodeURIComponent($origCrop)."&genSel=".$genSel.
     "&tab=seeding:flats:flats_report&submit=Submit\">";
}
?>
