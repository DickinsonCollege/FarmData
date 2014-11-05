<?php session_start(); ?>
<form name='form' method='GET' action='coverTable.php'>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletefert:deletecover:deletecoverseed">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h1 class="hi"> Select Cover Crop Seeding Records: </h1>
<?php
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all">';
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all">
<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all">
<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
