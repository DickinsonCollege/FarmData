<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Soil/clearForm.php';

$id=$_GET['id'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$origFieldID = $_GET['fieldID'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$origDisease = $_GET['disease'];
$origCrop= $_GET['crop'];
$origStage= $_GET['stage'];
$sqlget = "SELECT id, year(sDate) as yr, month(sDate) as mth, day(sDate) as dy,".
   "disease ,fieldID, crops, infest, stage, comments, hours, filename FROM diseaseScout where id = ".$id;
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
$fieldID = $row['fieldID'];
$disease = $row['disease'];
$crops = $row['crops'];
$infest = $row['infest'];
$stage = $row['stage'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$comments = $row['comments'];
$filename = $row['filename'];
$hours = $row['hours'];
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=soil:soil_scout:soil_disease:disease_report&year=".$origYear.
   "&month=".$origMonth."&day=".$origDay."&tyear=".$tcurYear.
   "&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&crop=".encodeURIComponent($origCrop).
   "&fieldID=".encodeURIComponent($origFieldID).
   "&stage=".encodeURIComponent($origStage).
   "&disease=".encodeURIComponent($origDisease)."&id=".$id."\" enctype='multipart/form-data'>";

echo "<center>";
echo "<H2> Edit Disease Scouting Record </H2>";
echo "</center>";

echo "<div class='pure-control-group'>";
echo '<label>Date:</label> ';
echo '<select name="month" id="month" class="mobile-select">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth < 13; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</select>';
echo '<select name="day" id="day" class="mobile-select">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day < 32; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</select>';
echo '<select name="year" id="year" class="mobile-select">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 3; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</select></div>';

echo "<div class='pure-control-group'>";
echo "<label>Name of Field:</label> ";
echo "<select name='fieldID' id='fieldID' class='mobile-select'>";
echo "<option value='".$fieldID."' selected>".$fieldID."</option>";
$sql = "SELECT fieldID from field_GH where active=1";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	echo "<option value='".$row['fieldID']."'>".$row['fieldID']."</option>";
}
echo '</select></div>';

echo "<div class='pure-control-group'>";
echo '<label>Crops:</label> ';
echo "<textarea name='crops'>".$crops."</textarea>";
echo '</div>';

echo "<div class='pure-control-group'>";
echo "<label>Disease:</label> ";
echo "<select name='disease' id='disease' class='mobile-select'>";
echo "<option value='".$disease."' selected>".$disease."</option>";
$sql = "select diseaseName from disease";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	echo "<option value='".$row['diseaseName']."'>".$row['diseaseName']."</option>";
}
echo '</select></div>';

echo "<div class='pure-control-group'>";
echo "<label>Infestation:</label> ";
echo "<select name='infest' id='infest' class='mobile-select'>";
echo "<option value='".$infest."' selected>".$infest."</option>";
for ($i = 0; $i < 5; $i++) {
	echo "<option value='".$i."'>".$i."</option>";
}
echo '</select></div>';

echo "<div class='pure-control-group'>";
echo "<label>Stage:</label> ";
echo "<select name='stage' id='stage' class='mobile-select'>";
$sql = "select stage from stage";
$res = $dbcon->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value='".$row['stage']."' ";
   if ($stage == $row['stage']) {
      echo " selected";
   }
   echo ">".$row['stage']."</option>";
}
echo "</select></div>";

echo '<div class="pure-control-group">';
echo '<label>Current Picture: </label>';
if ($filename == "") {
   echo "None";
   echo "</div>";
} else {
   $pos = strrpos($filename, "/");
   echo "<input readonly class='textbox2 mobile-input' type='text' value='";
   echo substr($filename, $pos + 1);
   echo "'/>";
   echo "</div>";
   echo "\n\n";
   echo '<div class="pure-control-group">';
   echo "\n";
   echo '<label for="del">Delete: </label>';
   echo "\n";
   echo '<input type="checkbox" id="del" name="del">';
   echo "\n";
   echo '</div>';
   echo "\n";
}
?>

<div class="pure-control-group" id="filediv">
<label for="file">Upload New Picture (optional): </label>
<input type="file" name="fileIn" id="file">
</div>

<div class="pure-control-group">
<label for="clear">Max File Size: 2 MB </label>
<input type="button" value="Clear Picture" onclick="clearForm();">
</div>

<?php
if ($_SESSION['labor']) {
   echo '<div class="pure-control-group">';
   echo "\n";
   echo '<label>Hours:</label>';
   echo "\n";
   echo '<input type="text" class="textbox2" name="hours" id="hours" value="'.$hours.'">';
   echo "\n";
   echo '</div>';
   echo "\n";
}

echo "<div class='pure-control-group'>";
echo '<label>Comments:</label>';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo "</div>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "</form>";
if (isset($_POST['submit'])) {
   $comments = escapehtml($_POST['comments']);
   $disease = escapehtml($_POST['disease']);
   $crops = escapehtml($_POST['crops']);
   $fieldID = escapehtml($_POST['fieldID']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $infest = escapehtml($_POST['infest']);
   $stage = escapehtml($_POST['stage']);
   $hours = 0;
   if ($_SESSION['labor']) {
      $hours = escapehtml($_POST['hours']);
      if ($hours == "") {
         $hours = 0;
      }
   }

   $sql = "update diseaseScout set crops='".$crops."', fieldID='".$fieldID."', sDate='".$year."-".
     $month."-".$day."', disease='".$disease."', infest=".$infest.",stage='".$stage."', comments='".
     $comments."', hours=".$hours." where id=".$id;

   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }

   include $_SERVER['DOCUMENT_ROOT'].'/Soil/imageEdit.php';

   if ($newfile != "") {
      $sql = "update diseaseScout set filename=";
      if ($newfile == "null") {
         $sql .= "null";
      } else {
         $sql .= "'".$newfile."'";
      }
      $sql .= " where id=".$id;
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
   }

   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo '<meta http-equiv="refresh" content="0;URL=diseaseTable.php?year='.
     $origYear.'&month='.$origMonth.'&day='.$origDay.'&tyear='.$tcurYear.
     '&tmonth='.$tcurMonth.'&tday='.$tcurDay.
     "&fieldID=".encodeURIComponent($origFieldID).
     "&crop=".encodeURIComponent($origCrop).
     "&disease=".encodeURIComponent($origDisease).
     "&stage=".encodeURIComponent($origStage).
     "&tab=soil:soil_scout:soil_disease:disease_report\">";
}
?>
