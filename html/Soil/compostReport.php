<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' id='test'  method='POST' action="compostTable.php?tab=soil:soil_fert:soil_compost:compost_report">
<h3 class="hi"> Compost Report </h3>
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

<br clear="all">
<label for='pileIDlabel'>Pile ID:&nbsp;</label>
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
<?php
	$active = 'active';
	include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>

<br clear="all"/>
<input type="submit" class = "genericbutton" name="submit" value="Submit">

