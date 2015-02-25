<?php session_start(); ?>
<form name='form' method='GET' action='compostActTable.php'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

?>
<h3 class="hi"> Select Compost Activity Records: </h3>
<br>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostact">
<?php
echo '<label for="from">From:</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to"> To:</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<label for="pileID">Pile ID:&nbsp;</label> 
<div class='styled-select'>
<select class='mobile-select' id='pileID' name='pileID'>
<option value='%'>All</option>
<?php
$result = mysql_query("Select pileID from compost_pile");
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row['pileID']."'>".$row['pileID']."</option>";
}
?>
</select>
</div>
<br clear="all"/>
<label for="act">Compost Activity:&nbsp;</label> 
<div class='styled-select'>
<select class='mobile-select' id='act' name='act'>
<option value='%'>All</option>
<?php
$result = mysql_query("Select activityName from compost_activities");
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row['activityName']."'>".$row['activityName']."</option>";
}
?>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
