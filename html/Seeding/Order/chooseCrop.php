<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<h3> Seed Order and Inventory </h3>
<br clear="all"/>

<form name='form' id = 'seedform' method='POST' action='updateSeed.php?tab=seeding:ordert:ordert_input'>
<?php
if ($_SESSION['cover']) {
   echo '<table>';
   echo '<tr><th>Vegetable</th><th>Cover Crop</th></tr><td>';
}
?>
<label for="crop">Crop:&nbsp;</label>
<div id='cropdiv' class='styled-select'>
<select name='crop' id='crop' class='mobile-select'>
<?php
$sql = "select crop from plant";
$res = mysql_query($sql);
echo mysql_error();
echo "<option value='Crop' disabled";
echo ">Crop</option>";
while ($row = mysql_fetch_array($res)) {
   echo "<option value='".$row['crop']."'";
   echo ">".$row['crop']."</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>

<?php
function printYear($name) {
   if ($name == "coverYear") {
      $isCov = "true";
   } else {
      $isCov = "false";
   }
   $curYear = strftime("%Y");
   echo '<label for="'.$name.'">Planting Year:&nbsp;</label>';
   echo "<div id='".$name."div' class='styled-select'>";
   echo "<select name='".$name."' id='".$name."' class='mobile-select'>";
   for ($y = $curYear - 3; $y < $curYear + 5; $y++) {
      echo "<option value='".$y."'";
      if ($y == $curYear) {
         echo " selected";
      }
      echo ">".$y."</option>";
   }
   echo '</select>';
   echo '</div>';
}
printYear("year");
?>

<br clear="all"/>
<br clear="all"/>
<input type="submit" name="submitCrop" class = "submitbutton" value="Choose Crop and Year" >
<?php 
if ($_SESSION['cover']) {
   echo '</td><td>';
   echo '<label for="cover">Crop:&nbsp;</label>';
   echo "<div id='covercropdiv' class='styled-select'>";
   echo "<select name='cover' id='cover' class='mobile-select'>";
   $sql = "select crop from coverCrop";
   $res = mysql_query($sql);
   echo mysql_error();
   echo "<option value='Cover Crop' disabled";
   echo ">Crop</option>";
   while ($row = mysql_fetch_array($res)) {
      echo "<option value='".$row['crop']."'";
      echo ">".$row['crop']."</option>";
   }
   echo "</select></div>";
   echo "<br clear='all'/>";
   printYear("coverYear");
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<input type="submit" name="submitCoverCrop" class = "submitbutton" value="Choose Crop and Year" >';
   echo '</td></tr></table>';
}
?>
</form>

