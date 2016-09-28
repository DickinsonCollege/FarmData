<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
if ($_SESSION['cover']) {
  $isCover = true;
} else {
  $isCover = false;
}
?>

<form name='form' class='pure-form pure-form-aligned' method='POST' action="orderTable.php?tab=seeding:ordert:ordert_report">
<center>
<h2 class="hi"> Seed Order Report </h2>
</center>
<div class='pure-control-group'>
<label for='year'>Year:</label>
<select name='year' id='year' class='mobile-select'>
<option value="%">All</option>
<?php
$curYear = strftime("%Y");
for ($i = $curYear - 10; $i <= $curYear + 1; $i++) {
   echo '<option value="'.$i.'"';
   if ($i == $curYear) {
      echo ' selected';
   }
   echo '>'.$i.'</option>';
}
?>
</select></div>
<?php
if (!$isCover) {
   echo "<div class='pure-control-group'>";
   echo '<label for="crop">Crop:</label> ';
   echo '<select name="crop" class="mobile-select">';
   echo '<option value = "%"> All </option>';
   $result = $dbcon->query("SELECT distinct crop from plant");
   while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
     echo "\n<option value= '".$row1['crop']."'>".$row1['crop']."</option>";
   }
   echo '</select>';
   echo '</div>';
}
?>
<div class='pure-control-group'>
<label for="source">Source:</label>
<select name='source' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = $dbcon->query("SELECT distinct source from source order by source");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= '".$row1['source']."'>".$row1['source']."</option>";
}
?>
</select>
</div>

<div class='pure-control-group'>
<label for="status">Order Status:</label>
<select name='status' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$statusArray = array('PENDING', 'ORDERED', 'ARRIVED');
for ($i = 0; $i < count($statusArray); $i++) {
  echo "\n<option value= '".$statusArray[$i]."'>".$statusArray[$i]."</option>";
}
?>
</select>
</div>

<div class='pure-control-group'>
<label for="order">Order Result By:</label>
<select name='order' class='mobile-select'>
<option value = "crop" selected="selected"> Crop </option>
<option value = "organic"> Organic Status </option>
</select>
</div>
<?php
if ($isCover) {
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<table class="pure-table pure-table-bordered">';
   echo '<thead><tr><th align="center">Vegetable</th><th align="center">Cover Crop</th></tr></thead><tr><td>';
   echo "<div class='pure-control-group'>";
   echo '<label for="crop">Crop:</label> ';
   echo '<select name="crop" class="mobile-select">';
   echo '<option value = "%"> All </option>';
   $result = $dbcon->query("SELECT distinct crop from plant");
   while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
     echo "\n<option value= '".$row1['crop']."'>".$row1['crop']."</option>";
   }
   echo '</select>';
   echo '</div>';
}
?>

<br clear="all">
<br clear="all">
<input class="submitbutton pure-button wide" type="submit" name="submitCrop" value="Submit">

<?php
if ($isCover) {
   echo '</td><td>';
   echo "<div class='pure-control-group'>";
   echo '<label for="covercrop">Crop:</label> ';
   echo '<select name="covercrop" class="mobile-select">';
   echo '<option value = "%"> All </option>';
   $result = $dbcon->query("SELECT distinct crop from coverCrop");
   while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
     echo "\n<option value= '".$row1['crop']."'>".$row1['crop']."</option>";
   }
   echo '</select>';
   echo '</div>';
   echo '<br clear="all">';
   echo '<br clear="all">';
   echo '<input class="submitbutton pure-button wide" type="submit" name="submitCover" value="Submit">';
   echo '</td></tr></table>';
}
?>
