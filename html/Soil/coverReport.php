<?php session_start(); ?>
<form name='form' class='pure-form pure-form-aligned' method='GET' action='coverTable.php'>
<input type="hidden" name="tab" value="soil:soil_fert:soil_cover:soil_coverseed:coverseed_report">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
echo "<center>";
echo '<h2 class="hi"> Cover Crop Seeding Report </h2>';
echo "</center>";
echo "<fieldset>";
echo '<div class="pure-control-group">';
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "</div>";
echo '<div class="pure-control-group">';
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "</div>";
?>
<?php
$active = 'active';
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
echo "</fieldset>";
?>
<br clear="all">
<input type="submit" class="submitbutton pure-button wide" name="submit" value="Submit">
</form>

