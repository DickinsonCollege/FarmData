<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' class='pure-form pure-form-aligned' method='GET' action="deleteTspray.php">
<?PHP
   echo '<input type="hidden" name = "tab" value = "soil:soil_spray:tspray:tspray_edit">';
?>
   
<center>
<h2 class="hi"> Tractor Spray Edit/Delete </h2>
</center>

<div class="pure-control-group">
<label for='from'>From:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for='to'>To:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
</div>

<br clear="all"/>
<br clear="all"/>
<input class="submitbutton pure-button wide" value="Submit" type="submit" name="submit" >
</body>
</html>
