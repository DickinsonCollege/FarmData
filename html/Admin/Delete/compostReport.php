<?php session_start(); ?>
<form name='form' method='GET' action='compostTable.php'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

?>
<h1 class="hi"> Select Compost Application Records: </h1>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostapp">
<?php
echo '<label for="from">From:</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to"> To:</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all"/>';
$active='all';
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
