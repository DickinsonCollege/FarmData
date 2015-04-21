<?php session_start(); ?>
<form name='form' method='GET' action='coverTable.php'>
<input type="hidden" name="tab" value="soil:soil_fert:soil_cover:soil_coverseed:coverseed_report">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
echo '<h3 class="hi"> Cover Crop Seeding Report </h3>';
echo "<br clear=\"all\">";
// echo  '<h1> Seeding Date Range</h1>';
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all">
<?php
$active = 'active';
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all">
<input type="submit" class="submitbutton" name="submit" value="Submit">
</form>

