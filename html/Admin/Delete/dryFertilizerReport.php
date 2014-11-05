<?php session_start(); ?>
<form name='form' method='GET' action='fertilizerTable.php?tab=admin:admin_delete:deletesoil:deletefert:deletefertilizer:deletedryfertilizer'>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletefert:deletefertilizer:deletedryfertilizer">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<h3 class="hi"> Dry Fertilizer Report </h3>
<br clear="all">
<h1> Fertilizer Application Date Range </h1>
<label for="from">From:&nbsp;</label>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To: &nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all">
<?php
$active = 'all';
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<!--<label for="fieldID"> Field ID:&nbsp; </label>
<div id="fieldID23" class="styled-select">
<select id= "fieldID" name="fieldID" class='mobile-select'>
<option value="%" selected> All </option>
<?php
$result = mysql_query("SELECT fieldID from field_GH");
while ($row =  mysql_fetch_array($result)){
  //echo "\n<option value= \"$row[fieldID]\">$row[fieldID]</option>";
}
?>
</select>
</div>-->

<label for="fieldID"> Crop Group: &nbsp; </label>
<div id="fieldID23" class="styled-select">
<select id= "group" name="group" class='mobile-select'>
<option value="%" selected> All </option>
<?php
$result = mysql_query("SELECT cropGroup from cropGroupReference");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row[cropGroup]\">$row[cropGroup]</option>";
}
?>
</select>
</div>

<br clear="all"/>
<label for="material"> Material:</label>
<div class ="styled-select">
<select name="material" id="material" class='mobile-select'>
<option value = "%" selected> All </option>
<?php
$result = mysql_query("SELECT fertilizerName from fertilizerReference");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row[fertilizerName]\">$row[fertilizerName]</option>";
}
?>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton" name="submit" value="Submit">
</form>
