<?php session_start(); ?>
<html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<head>
	<link rel="stylesheet" href="/pure-release-0.5.0/pure-min.css">
</head>
<form name='form' class='pure-form pure-form-aligned' method='post' action="pumpTable.php?tab=soil:soil_irrigation:pump_report">
	<fieldset>
		<legend><h4 class="hi"> Pump Log Report</h4></legend>
		<div class='pure-control-group'>
			<label for="from">From:&nbsp;</label>
			<?php
			include $_SERVER['DOCUMENT_ROOT'].'/date.php';
			?>
		</div>
		<br clear="all"/>
		<div class='pure-control-group'>
			<label for="to">To:&nbsp; </label>
			<?php
			include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
			?>
		</div>

		<br clear="all"/>
		<div class='pure-controls'>
			<input type="submit" class="submitbutton" name="submit" value="Submit">
		</div>
