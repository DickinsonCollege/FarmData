<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' method='GET' action="deleteTspray.php">
<?PHP
   echo '<input type="hidden" name = "tab" value = "soil:soil_spray:tspray:tspray_edit">';
?>
   
<h3 class="hi"> Tractor Spray Edit/Delete </h3>
<br clear="all"/>
<label for='from'>From:&nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for='to'>To:&nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>

<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" value="Submit" type="submit" name="submit" >
</body>
</html>
