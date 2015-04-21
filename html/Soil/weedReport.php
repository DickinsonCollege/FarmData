<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3 class="hi"> Weed Scout Report </h3>
<form name='form' id='test'  method='GET' action="weedTable.php">
<input type="hidden" name="tab" value="soil:soil_scout:soil_weed:weed_report">
<br clear="all"/>
<label for='date'> From:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for='date2'> To:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>

<label for="weed"> Weed Species:&nbsp; </label>
 <div class="styled-select">
 <select name ="weed" id="weed" class="mobile-select">
 <option value = "%" selected > All</option>
 <?php
 $result=mysql_query("Select weedName from weed");
 while ($row1 =  mysql_fetch_array($result)){
 echo "\n<option value= \"$row1[weedName]\">$row1[weedName]</option>";
 }
 echo '</select>';
 echo '</div>';
 ?>
<br clear="all"/>
<br clear="all"/>

<input type="submit" name="submit" class="submitbutton" value="Submit">

