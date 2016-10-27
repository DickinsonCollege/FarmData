<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<center>
<h2 class="hi"> Weed Scouting Report </h2>
</center>
<form name='form' class='pure-form pure-form-aligned' id='test'  method='GET' action="weedTable.php">
<input type="hidden" name="tab" value="soil:soil_scout:soil_weed:weed_report">

<div class="pure-control-group">
<label for='date'> From: </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for='date2'> To:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>

<div class="pure-control-group">
<label for="weed"> Weed Species: </label>
 <select name ="weed" id="weed" class="mobile-select">
 <option value = "%" selected > All</option>
 <?php
 $result=$dbcon->query("Select weedName from weed");
 while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
 echo "\n<option value= \"$row1[weedName]\">$row1[weedName]</option>";
 }
 echo '</select>';
 echo '</div>';
 ?>
<br clear="all"/>
<br clear="all"/>

<input type="submit" name="submit" class="submitbutton pure-button wide" value="Submit">

