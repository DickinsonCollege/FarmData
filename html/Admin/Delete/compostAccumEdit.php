<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origPileID = $_GET['pileID'];
$origMaterial = $_GET['material'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, year(accDate) as yr, month(accDate) as mth, day(accDate) as dy, accDate, pileID,
    material, pounds, cubicyards, comments FROM compost_accumulation where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$accDate = $row['accDate'];
$pileID = $row['pileID'];
$material= $row['material'];
$pounds = $row['pounds'];
$cubicyards = $row['cubicyards'];
$id = $row['id'];
$comments = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
?>

<?php
echo "<form name='form' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostaccum&year=".$origYear.
   "&month=".$origMonth.  "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&id=".$id.
   "&pileID=".encodeURIComponent($origPileID)."&material=".encodeURIComponent($origMaterial)."\">";

echo "<H3> Edit Compost Accumulation Record </H3>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<label>Date:&nbsp</label>';
echo '<div class="styled-select"><select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo "</select>";
echo "</div>";
echo "<br clear='all'>";

echo "<label>Compost Pile ID:&nbsp</label>";
echo "<div class='styled-select'>";
echo "<select name='pileID' id='pileID'>";
echo "<option value=\"".$pileID."\" selected>".$pileID."</selected>";
$sql = "select pileID from compost_pile";
$result = mysql_query($sql) or die();
while ($row = mysql_fetch_array($result)) {
   echo "<option value=\"".$row['pileID']."\">".$row['pileID']."</option>";
}
echo "</select></div>";
echo "<br clear='all'/>";

echo '<label>Compost Material:&nbsp</label>';
echo '<div class="styled-select"><select name="material" id="material">';
echo '<option value="'.$material.'" selected>'.$material.' </option>';
$sql = 'select materialName from compost_materials';
$sqldata = mysql_query($sql) or die();
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['materialName'].'">'.$row['materialName'].' </option>';
}
echo '</select></div>';
echo '<br clear="all"/>';

echo "<label>Pounds:&nbsp</label>";
echo "<input type='text' class='textbox25' name='pounds' id='pounds' value=".$pounds.">";
echo "<br clear='all'>";

echo "<label>Cubic Yards:&nbsp</label>";
echo "<input type='text' class='textbox25' name='cubicyards' id='cubicyards' value=".$cubicyards.">";
echo "<br clear='all'/>";

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
   $comments = escapehtml($_POST['comments']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $pileID = escapehtml($_POST['pileID']);
   $pounds = escapehtml($_POST['pounds']);
   $cubicyards = escapehtml($_POST['cubicyards']);
   $material = escapehtml($_POST['material']);

   echo $sql = "update compost_accumulation set accDate='".$year."-".$month."-".$day.
      "', material='".$material."', 
      pounds='".$pounds."', pileId='".$pileID."', cubicyards=".$cubicyards.", comments='".$comments."'
      WHERE id=".$id;
   $result = mysql_query($sql);
   
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=compostAccumTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay."&pileID=".
        encodeURIComponent($origPileID)."&material=".encodeURIComponent($origMaterial).
        "&tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostaccum&submit=Submit\">";
   }
}
?>
