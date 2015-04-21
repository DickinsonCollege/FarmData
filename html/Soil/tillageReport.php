<?php session_start(); ?>
<form name='form' method='GET' action='tillageTable.php'>
<input type="hidden" name="tab" value='soil:soil_fert:soil_till:till_report'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<h3 class="hi"> Tillage Report </h3>
<br clear="all"/>
<h1> Choose Date Range </h1>
<label for="from">From:&nbsp;</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="to"> To:&nbsp;</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all"/>

<input type="submit" class="submitbutton"  name="submit" value="Submit">
</form>
