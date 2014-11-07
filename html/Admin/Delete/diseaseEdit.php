<?php session_start();?>
<?php

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
$origDisease = $_GET['disease'];
$origCropGroup = $_GET['cropGroup'];
//echo $origFieldID. 'material '.$material.' group '.$group;
$sqlget = "SELECT id, year(sDate) as yr, month(sDate) as mth, day(sDate) as dy,".
   "disease ,fieldID, cropGroup, infest, stage, comments FROM diseaseScout where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
//$user = $row['username'];
$fieldID = $row['fieldID'];
$disease = $row['disease'];
$cropGroup = $row['cropGroup'];
$infest = $row['infest'];
$stage = $row['stage'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$comments = $row['comments'];
echo "<form name='form' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deletesoil:deletescout:deletediseasescout&year=".$origYear."&month=".
   $origMonth."&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&cropGroup=".
   encodeURIComponent($origCropGroup)."&fieldID=".encodeURIComponent($origFieldID)."&disease=".
   encodeURIComponent($origDisease)."&id=".$id."\">";

echo "<H3> Edit Disease Scouting Record </H3>";
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

echo "<label>Field ID:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='fieldID' id='fieldID'>";
echo "<option value='".$fieldID."' selected>".$fieldID."</option>";
$sql = "SELECT fieldID from field_GH where active=1";
$result = mysql_query($sql) or die();
while ($row = mysql_fetch_array($result)) {
	echo "<option value='".$row['fieldID']."'>".$row['fieldID']."</option>";
}
echo "</div></select>";
echo "<br clear='all'>";

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

echo "<label>Disease:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='disease' id='disease'>";
echo "<option value='".$disease."' selected>".$disease."</option>";
$sql = "select diseaseName from disease";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
	echo "<option value='".$row['diseaseName']."'>".$row['diseaseName']."</option>";
}
echo "</div></select>";
echo "<br clear='all'>";

echo "<label>Infestation:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='infest' id='infest'>";
echo "<option value='".$infest."' selected>".$infest."</option>";
for ($i = 0; $i < 5; $i++) {
	echo "<option value='".$i."'>".$i."</option>";
}
echo "</div></select>";
echo "<br clear='all'>";

echo "<label>Stage:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='stage' id='stage'>";
echo "<option value='".$stage."' selected>".$stage."</option>";
echo "<option value='MODERATE'> MODERATE </option>";
echo "<option value='SAD'> SAD </option>";
echo "<option value='SEVERE'> SEVERE </option>";
echo "</select></div>";
echo "<br clear='all'>";


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
   $comments = escapehtml($_POST['comments']);
   $disease = escapehtml($_POST['disease']);
   $cropGroup = escapehtml($_POST['cropGroup']);
   $fieldID = escapehtml($_POST['fieldID']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $infest = escapehtml($_POST['infest']);
   $stage = escapehtml($_POST['stage']);

   $sql = "update diseaseScout set cropGroup='".$cropGroup."', fieldID='".$fieldID."', sDate='".$year."-".
     $month."-".$day."', disease='".$disease."', infest=".$infest.",stage='".$stage."', comments='".
     $comments."' where id=".$id;
//   echo $sql;
//   echo '<BR>';
//   echo $totalApply;
//echo $origFieldID. 'material '.$material.' group '.$group;

   $result = mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=diseaseTable.php?year='.$origYear.'&month='.
        $origMonth.'&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay."&fieldID=".
        encodeURIComponent($origFieldID)."&cropGroup=".encodeURIComponent($origCropGroup)."&disease=".
        encodeURIComponent($origDisease).
        "&tab=admin:admin_delete:deletesoil:deletescout:deletediseasescout&submit=Submit\">";
   }
}
?>
