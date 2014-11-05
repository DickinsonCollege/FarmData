<?php session_start(); ?>
<form name='form' method='GET' action='tillageTable.php'>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletefert:deletetill">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

echo '<h1 class="hi"> Select Tillage Records: </h1>';
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all"/>';

include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
