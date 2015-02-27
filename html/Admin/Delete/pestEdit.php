<?php session_start();?>
<?php
$farm = $_SESSION['db'];
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
$origPest = $_GET['pest'];
$sqlget = "SELECT id,year(sDate) as yr, month(sDate) as mth, day(sDate) as dy, crops, pest,".
   "sDate,fieldID,avgCount, comments FROM pestScout where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
$pest = $row['pest'];
$field = $row['fieldID'];
$avgCount = $row['avgCount'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$curCrops = $row['crops'];
$comments = $row['comments'];

echo "<form name='form' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deleteseed:deletedirplant&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop=".encodeURIComponent($origCrop).
   "&fieldID=".encodeURIComponent($origField)."&pest=".encodeURIComponent($origPest)."&id=".$id."\">";
echo "<H3> Edit Insect Scouting Record </H3>";
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
echo '<label>Crops:&nbsp</label>';
echo '<br clear="all"/>';
echo '<textarea name="crops">'.$curCrops.'</textarea>';
/*
echo '<div class="styled-select"><select name="crop" id="crop">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant where active=1';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</div></select>';
*/
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

echo '<label>Insect:&nbsp</label>';
echo '<div class="styled-select"><select name="pest" id="pest">';
echo '<option value="'.$pest.'" selected>'.$pest.' </option>';
$sql = 'select pestName from pest';
$sqldata = mysql_query($sql) or die("ERROR4");
while ($row = mysql_fetch_array($sqldata)) {
	echo '<option value="'.$row['pestName'].'">'.$row['pestName'].' </option>';
}
echo '</div></select>';
echo '<br clear="all"/>';

echo '<label>Average Count:&nbsp</label>';
echo '<input type="text" class="textbox3" name="avgCount" id="avgCount" value="'.$avgCount.'">';
echo '<br clear="all"/>';

echo '<label>Comments:&nbsp</label>';
echo '<br clear="all"/>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'>";
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
   $result = mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=pestTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.'&crop='.
        encodeURIComponent($origCrop).'&fieldID='.encodeURIComponent($origField).'&pest='.
        encodeURIComponent($origPest).
        "&tab=admin:admin_delete:deletesoil:deletescout:deletepestscout&submit=Submit\">";
   }
}
?>
