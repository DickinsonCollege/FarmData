<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' method='GET' action="incorpTable.php">
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletefert:deletecover:deletecoverincorp">
<h3 class="hi"> Select Cover Crop Incorporation Records:</h3>
<?php
echo '<br clear="all"/>';
echo '<label for="from">From: &nbsp;</label>';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to">To: &nbsp;</label>';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all"/>';
$active="all";
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all"/>
<input class='submitbutton' type="submit" name="submit" value="Submit">

