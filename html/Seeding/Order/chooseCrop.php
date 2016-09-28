<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<center>
<h2> Seed Order and Inventory </h2>
</center>

<form name='form' id='seedform' class='pure-form pure-form-aligned' method='POST' action='updateSeed.php?tab=seeding:ordert:ordert_input'>
<?php
if ($_SESSION['cover']) {
   echo '<table class="pure-table pure-table-bordered">';
   echo '<thead><tr><th align="center">Vegetable</th><th align="center">Cover Crop</th></tr></thead><td>';
}
?>
<div class="pure-control-group" id="cropdiv">
<label for="crop">Crop:</label>
<select name='crop' id='crop' class='mobile-select'>
<?php
$sql = "select crop from plant where active=1";
try {
  $res = $dbcon->query($sql);
} catch (PDOException $p) {
   phpAlert('', $p);
   die();
}
echo "<option value='Crop' disabled";
echo ">Crop</option>";
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value='".$row['crop']."'";
   echo ">".$row['crop']."</option>";
}
echo '</select>';
echo '</div>';
?>

<?php
function printYear($name) {
   if ($name == "coverYear") {
      $isCov = "true";
   } else {
      $isCov = "false";
   }
   $curYear = strftime("%Y");
   echo '<div class="pure-control-group">';
   echo '<label for="'.$name.'">Planting Year:</label> ';
   // echo "<div id='".$name."div' class='styled-select'>";
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
<input type="submit" name="submitCrop" class = "submitbutton pure-button wide" value="Choose Crop and Year" >
<?php 
if ($_SESSION['cover']) {
   echo '</td><td>';
   echo '<div class="pure-control-group">';
   echo '<label for="cover">Crop:</label> ';
   // echo "<div id='covercropdiv' class='styled-select'>";
   echo "<select name='cover' id='cover' class='mobile-select'>";
   $sql = "select crop from coverCrop where active = 1";
   try {
      $res = $dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<option value='Cover Crop' disabled";
   echo ">Crop</option>";
   while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
      echo "<option value='".$row['crop']."'";
      echo ">".$row['crop']."</option>";
   }
   echo "</select></div>";
   printYear("coverYear");
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<input type="submit" name="submitCoverCrop" class = "submitbutton pure-button wide" value="Choose Crop and Year" >';
   echo '</td></tr></table>';
}
?>
</form>

