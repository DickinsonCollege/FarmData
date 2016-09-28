<?php session_start(); ?>

<form name = 'form' class = 'pure-form pure-form-aligned' method = 'GET' action = 'cropMasterTable.php'>
<input type = "hidden" name = "tab" value = "admin:admin_view:view_tables:view_history">
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

echo '<center><h2>Select Year Range And A Field</h2></center>';
echo '<div class = "pure-control-group">';
echo '<label for = "from">From:</label> ';
echo '<select name = "year" id = "year">';
$curYear = date("Y");
echo '<option value = '.$curYear.' selected>'.$curYear.'</option>';
for ($year = $curYear-5; $year < $curYear +1; $year++) {
   echo "\n<option value = \"$year\">$year</option>";
}
echo '</select></div>';


echo '<div class = "pure-control-group">';
echo '<label for = "to">To:</label> ';
echo '<select name = "tyear" id = "tyear">';
echo '<option value = '.$curYear.' selected>'.$curYear.'</option>';
for ($year = $curYear-5; $year < $curYear +1; $year++) {
   echo "\n<option value = \"$year\">$year</option>";
}
echo '</select></div>';
?>

<div class = "pure-control-group">
<label for="fieldID">FieldID:</label>
<select id = "fieldID" name = "fieldID">
<option value = "%">All</option>
<?php 
$result = $dbcon->query("SELECT distinct fieldID from field_GH");
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "\n<option value = \"$row[fieldID]\">$row[fieldID]</option>";
}
echo "</select></div>";
?>
<br clear = "all"/>
<input class = "submitbutton pure-button wide" type = "submit" name = "submit" value = "Submit">
</form>
