<?php session_start(); ?>
<form name='form' class='pure-form pure-form-aligned' method='GET' action='tillageTable.php'>
<input type="hidden" name="tab" value='soil:soil_fert:soil_till:till_report'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<center>
<h2 class="hi"> Tillage Report </h2>
</center>

<div class="pure-control-group">
<label for="from">From:</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for="to"> To:</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
</div>
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all"/>

<input type="submit" class="submitbutton pure-button wide"  name="submit" value="Submit">
</form>
