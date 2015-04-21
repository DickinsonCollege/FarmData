<?php session_start(); ?>
<html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="soil">
<form name='form' method='get' action="incorpTable.php">
<input type="hidden" name="tab" value="soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report">
<h3 class="hi"> Incorporation Report</h3>
<br clear="all"/>
<label for="from">From:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="to">To:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>

<br clear="all"/>
<label> Field ID: </label>
<div class="styled-select">
<select name='fieldID' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = mysql_query("SELECT distinct fieldID from coverSeed_master");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton" name="submit" value="Submit">
