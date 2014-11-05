<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3> Insect Scouting Report </h3>
<form name='form' id='test'  method='POST' action="pestTable.php?tab=soil:soil_scout:soil_pest:pest_report">
<br clear="all"/>
<label for='date'> From:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for='date2'> To: &nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<label for="crop"> Crop:&nbsp; </label>
 <div class="styled-select" id="field">
 <select name ="crop" id="crop" class="mobile-select">
 <option value = "%" selected > All</option>
 <?php
 $result=mysql_query("Select crop from plant");
 while ($row1 =  mysql_fetch_array($result)){
 echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
 }
 echo '</select>';
 echo '</div>';
 ?>
<br clear="all"/>

<label for="pest"> Insect:&nbsp; </label>
 <div class="styled-select" id="Pest">
 <select name ="pest" id="pest" class="mobile-select">
 <option value = "%" selected > All</option>
 <?php
 $result=mysql_query("Select pestName from pest");
 while ($row1 =  mysql_fetch_array($result)){
 echo "\n<option value= \"$row1[pestName]\">$row1[pestName]</option>";
 }
 echo '</select>';
 echo '</div>';
 ?>
<br clear="all"/>
<br clear="all"/>

<input type="submit" class="submitbutton" name="submit" value="Submit">

