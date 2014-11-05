<?php session_start();?>
<form name='form' method='GET' action='dirTable.php'>
<input type="hidden" value="admin:admin_delete:deleteseed:deletedirplant" name="tab">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

?>
<h1 class="hi"> Select Direct Planting Records: </h1>
<?php
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all">';
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all">';
?>
<label for="crop"> Crop:&nbsp;</label>
<div class ="styled-select">
<select name="crop" id = "crop" class='mobile-select'>
<option value = "%" selected> All </option>
<?php
$result = mysql_query("SELECT distinct crop from dir_planted");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
<br clear="all">
<br clear="all">

<input class="submitbutton" type="submit" name="submit" value="Submit"">
</form>
