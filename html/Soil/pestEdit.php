<?php session_start();?>
<?php
$farm = $_SESSION['db'];
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Soil/clearForm.php';

$id=$_GET['id'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$origCrop = $_GET['crop'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$origField = $_GET['fieldID'];
$origPest = $_GET['pest'];
$sqlget = "SELECT id,year(sDate) as yr, month(sDate) as mth, day(sDate) as dy, crops, pest,".
   "sDate,fieldID,avgCount,comments,filename FROM pestScout where id = ".$id;
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
$pest = $row['pest'];
$field = $row['fieldID'];
$avgCount = $row['avgCount'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$curCrops = $row['crops'];
$comments = $row['comments'];
$filename = $row['filename'];

echo "<form class='pure-form pure-form-aligned' name='form' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=soil:soil_scout:soil_pest:pest_report&year=".$origYear.
   "&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&crop=".encodeURIComponent($origCrop).
   "&fieldID=".encodeURIComponent($origField).
   "&pest=".encodeURIComponent($origPest)."&id=".$id."\" enctype='multipart/form-data'>";
echo "<center>";
echo "<H2> Edit Insect Scouting Record </H2>";
echo "</center>";

echo "<div class='pure-control-group'>";
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

echo "<div class='pure-control-group'>";
echo '<label>Crops:</label> ';
echo '<textarea name="crops">'.$curCrops.'</textarea>';
echo "</div>";

echo "<div class='pure-control-group'>";
echo '<label>Name of Field:</label> ';
echo '<select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</select></div>';

echo "<div class='pure-control-group'>";
echo '<label>Insect:</label> ';
echo '<select name="pest" id="pest">';
echo '<option value="'.$pest.'" selected>'.$pest.' </option>';
$sql = 'select pestName from pest';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
	echo '<option value="'.$row['pestName'].'">'.$row['pestName'].' </option>';
}
echo '</select></div>';

echo "<div class='pure-control-group'>";
echo '<label>Average Count:</label> ';
echo '<input type="text" class="textbox3" name="avgCount" id="avgCount" value="'.$avgCount.'">';
echo '</div>';
?>

<div class="pure-control-group">
<label>Current Picture: </label>
<?php
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
echo "<div class='pure-control-group'>";
echo '<label>Comments:</label> ';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '</div>';
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "</form>";
if ($_POST['submit']) {
   $comSanitized=escapehtml($_POST['comments']);
   $avgCount = escapehtml($_POST['avgCount']);
   $fld = escapehtml($_POST['fieldID']);
   $crops = escapehtml($_POST['crops']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $pest = escapehtml($_POST['pest']);
   $sql = "update pestScout set pest='".$pest."', fieldID='".$fld."', sDate='".$year."-".
     $month."-".$day."', avgCount=".$avgCount.",comments='".
     $comSanitized."',crops='".$crops."' where id=".$id;
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }

   include $_SERVER['DOCUMENT_ROOT'].'/Soil/imageEdit.php';

   if ($newfile != "") {
      $sql = "update pestScout set filename=";
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
   echo '<meta http-equiv="refresh" content="0;URL=pestTable.php?year='.$origYear.'&month='.$origMonth.
     '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.'&crop='.
     encodeURIComponent($origCrop).'&fieldID='.encodeURIComponent($origField).'&pest='.
     encodeURIComponent($origPest).
     "&tab=soil:soil_scout:soil_pest:pest_report\">";
}
?>
