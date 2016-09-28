<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
//$origPileID = encodeURIComponent($_GET['pileID']);
//$origFieldID = encodeURIComponent($_GET['fieldID']);
$origPileID = $_GET['pileID'];
$origFieldID = $_GET['fieldID'];
$origMaterial = $_GET['material'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, year(accDate) as yr, month(accDate) as mth, day(accDate) as dy, accDate, pileID,
    material, pounds, cubicyards, comments FROM compost_accumulation where id = ".$id;
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);

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
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_compost:compost_report&year=".$origYear.
   "&month=".$origMonth.  "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&id=".$id.
   "&pileID=".encodeURIComponent($origPileID)."&fieldID=".encodeURIComponent($origFieldID)."\">";

echo "<center>";
echo "<H2> Edit Compost Accumulation Record </H2>";
echo "</center>";
echo '<div class="pure-control-group">';
echo '<label>Date:</label> ';
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
echo "</select>";
echo "</div>";

echo '<div class="pure-control-group">';
echo "<label>Compost Pile ID:</label> ";
echo "<select name='pileID' id='pileID'>";
echo "<option value=\"".$pileID."\" selected>".$pileID."</selected>";
$sql = "select pileID from compost_pile";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['pileID']."\">".$row['pileID']."</option>";
}
echo "</select></div>";

echo '<div class="pure-control-group">';
echo '<label>Compost Material:</label> ';
echo '<select name="material" id="material">';
echo '<option value="'.$material.'" selected>'.$material.' </option>';
$sql = 'select materialName from compost_materials';
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo '<option value="'.$row['materialName'].'">'.$row['materialName'].' </option>';
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo "<label>Pounds:</label> ";
echo "<input type='text' class='textbox25' name='pounds' id='pounds' value=".$pounds.">";
echo "</div>";

echo '<div class="pure-control-group">';
echo "<label>Cubic Yards:</label> ";
echo "<input type='text' class='textbox25' name='cubicyards' id='cubicyards' value=".$cubicyards.">";
echo "</div>";

echo '<div class="pure-control-group">';
echo '<label>Comments:</label> ';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo "</div>";

echo '<br clear="all"/>';
echo '<br clear="all"/>';


echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
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

   $sql = "update compost_accumulation set accDate='".$year."-".$month."-".$day.
      "', material='".$material."', 
      pounds='".$pounds."', pileId='".$pileID."', cubicyards=".$cubicyards.", comments='".$comments."'
      WHERE id=".$id;
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo '<meta http-equiv="refresh" content="0;URL=compostTable.php?year='.$origYear.'&month='.$origMonth.
     '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay."&pileID=".
     encodeURIComponent($origPileID)."&fieldID=".encodeURIComponent($origFieldID).
     "&tab=soil:soil_fert:soil_compost:compost_report\">";
}
?>
