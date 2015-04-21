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
$origSprayedMaterial = $_GET['sprayMaterial'];
 
$sqlget = "SELECT id,year(sprayDate) as yr, month(sprayDate) as mth, day(sprayDate) as dy, materialSprayed,".
   "sprayDate,fieldID, crops, water, totalMaterial, comments, rate, mixedWith FROM bspray where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
$field = $row['fieldID'];
$materialSprayed = $row['materialSprayed'];
$crops = $row['crops'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$water = $row['water'];
$comments = $row['comments'];
$totalMaterial = $row['totalMaterial'];
$rate = $row['rate'];
$mixedWith = $row['mixedWith'];
echo "<form name='form' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=soil:soil_spray:bspray:bspray_report&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&fieldID=".
   encodeURIComponent($origFieldID)."&sprayMaterial=".encodeURIComponent($origSprayedMaterial)."&id=".$id."\">";
echo "<H3> Edit Backpack Spray Record </H3>";
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
echo '<textarea name="crops">';
echo $crops; 
echo '</textarea>';
/*
echo '<label>Crop Group:&nbsp</label>';
echo '<div class="styled-select"><select name="cropGroup" id="cropGroup">';
echo '<option value="'.$cropGroup.'" selected>'.$cropGroup.' </option>';
$sql = 'select cropGroup from cropGroupReference';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['cropGroup'].'">'.$row['cropGroup'].' </option>';
}
echo '</div></select>';
*/
echo '<br clear="all"/>';
echo '<label>Material Sprayed:&nbsp</label>';
echo '<div class="styled-select"><select name="materialSprayed" id="materialSprayed">';
echo '<option value="'.$materialSprayed.'" selected>'.$materialSprayed.' </option>';
$sql = 'select sprayMaterial from tSprayMaterials';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['sprayMaterial'].'">'.$row['sprayMaterial'].' </option>';
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
echo '<label>Water:&nbsp</label>';
echo '<input type="text" class="textbox2"name="water" id="water" value="'.$water.'">';


echo '<br clear="all"/>';
echo '<label>Rate:&nbsp</label>';
echo '<input type="text" class="textbox2"name="rate" id="rate" value="'.$rate.'">';

echo '<br clear="all"/>';

echo '<label>Total Materials:&nbsp</label>';
echo '<input type="text" class="textbox2" name="totalMaterial" id="totalMaterial" value="'.$totalMaterial.'">';
echo '<br clear="all"/>';

echo '<label>Mixed With:&nbsp</label>';
echo '<input type="text" class="textbox3" name="mixedWith" id="totalMaterial" value="'.$mixedWith.'">';
echo'<br clear="all"/>';

echo '<label>Comments&nbsp</label>';
echo '<br clear="all"/>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\" >";
echo $comments;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'>";
echo "</form>";
if ($_POST['submit']) {
   $comSanitized = escapehtml($_POST['comments']);
   $materialSprayed = escapehtml($_POST['materialSprayed']);
   $crops = escapehtml($_POST['crops']);
   $fld = escapehtml($_POST['fieldID']);
   $water = escapehtml($_POST['water']);
   $totalMaterial = escapehtml($_POST['totalMaterial']);
	$mixedWith = escapehtml($_POST['mixedWith']); 
	if ($totalMaterial == "") {
      $totalMaterials = 0;
   }
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $rate = escapehtml($_POST['rate']);
   $sql = "update bspray set crops='".$crops."', fieldID='".$fld."', sprayDate='".$year."-".
     $month."-".$day."', materialSprayed='".$materialSprayed."',water=".$water.",totalMaterial=".$totalMaterial.",comments='".
     $comSanitized."',mixedWith='".$mixedWith."', rate='".$rate."' where id=".$id;
   $result = mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=sprayTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay."&fieldID=".
        encodeURIComponent($_GET['fieldID'])."&sprayMaterial=".encodeURIComponent($_GET['sprayMaterial']).
        "&tab=soil:soil_spray:bspray:bspray_report\">";
   }
}
?>
