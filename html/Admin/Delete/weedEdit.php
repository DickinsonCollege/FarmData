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

$sqlget = "SELECT id,year(sDate) as yr, month(sDate) as mth, day(sDate) as dy, weed,".
   "sDate,fieldID, infestLevel, goneToSeed, comments FROM weedScout where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
//$user = $row['username'];
$field = $row['fieldID'];
$weed = $row['weed'];
$infestLevel = $row['infestLevel'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$goneToSeed = $row['goneToSeed'];
$comments = $row['comment'];
echo "<form name='form' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deletesoil:deletescout:deleteweedscout&year=".$origYear."&month=".$origMonth.
   "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&species=".
   encodeURIComponent($_GET['species'])."&fieldID=".encodeURIComponent($origFieldID)."&id=".$id."\">";
/*
echo '<input type="hidden" name="oldCrop" value="'.$infestLevel.'">';
echo '<input type="hidden" name="oldField" value="'.$field.'">';
*/
echo "<H3> Edit Weed Record </H3>";
echo '<br clear="all"/>';
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
echo '<label>Infest Level:&nbsp</label>';
echo '<div class="styled-select"><select name="infestLevel" id="infestLevel">';
echo '<option value="'.$infestLevel.'" selected>'.$infestLevel.' </option>';
echo '<option>0</option> <option>1</option> <option>2</option> <option>3</option>';
echo '</div></select>';
echo '<br clear="all"/>';
echo '<label>Weed:&nbsp</label>';
echo '<div class="styled-select"><select name="weed" id="weed">';
echo '<option value="'.$weed.'" selected>'.$weed.' </option>';
$sql = 'select weedName from weed';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['weedName'].'">'.$row['weedName'].' </option>';
}
echo '</div></select>';

echo '<br clear="all"/>';

echo '<label>Field:&nbsp</label>';
echo '<div class="styled-select"><select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = mysql_query($sql) or die("ERROR3");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</div></select>';

echo '<br clear="all"/>';
echo '<label>Gone To Seed:&nbsp</label>';
echo '<div class="styled-select" id="g2seedDiv"><select name="g2seed" id="g2seed">';
echo '<option value ="'.$goneToSeed.'">'.$goneToSeed.'</option> <option>0</option> <option>25</option> <option>50</option> <option>75</option> <option>100</option> </select></div>';

echo '<br clear="all"/>';

echo '<label>Comments&nbsp</label>';
echo '<br clear="all"/>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\" >";
echo $com;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'>";
echo "</form>";
if ($_POST['submit']) {
   $comSanitized = escapehtml($_POST['comments']);
   $weed = escapehtml($_POST['weed']);
   $infestLevel = escapehtml($_POST['infestLevel']);
   $fld = escapehtml($_POST['fieldID']);
   $goneToSeed = escapehtml($_POST['g2seed']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $sql = "update weedScout set infestLevel='".$infestLevel."', fieldID='".$fld."', sDate='".$year."-".
     $month."-".$day."', weed='".$weed."',goneToSeed=".$goneToSeed.",comments='".
     $comSanitized."' where id=".$id;
	//echo $sql;
   $result = mysql_query($sql);
// START - check if old crop can be deleted first!!!
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      //echo "field ID is ". $origFieldID;
      echo '<meta http-equiv="refresh" content="0;URL=weedTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay."&fieldID=".
        encodeURIComponent($_GET['fieldID'])."&species=".encodeURIComponent($_GET['species']).
        "&tab=admin:admin_delete:deletesoil:deletescout:deleteweedscout&submit=Submit\">";
   }
}
?>
