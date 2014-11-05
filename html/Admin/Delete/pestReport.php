<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h1> Select Insect Scouting Records </h1>
<form name='form' id='test'  method='GET' action="pestTable.php">
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletescout:deletepestscout">
<?php
echo "<label for='date'> From:&nbsp; </label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo "<label for='date2'> To:&nbsp; </label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all"/>';
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>

<label for="crop"> Crop:&nbsp; </label>
 <div class="styled-select" id="field">
 <select name ="crop" id="crop" class='mobile-select'>
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
 <select name ="pest" id="pest" class='mobile-select'>
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

<input class='submitbutton' type="submit" name="submit" value="Submit">

