<?php session_start(); ?>
<form name='form' class = "pure-form pure-form-aligned" method='GET' action='fieldRecordTable.php'>
<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<center><h2 class="hi"> Select Date Range and Field </h2></center>
<input type="hidden" name = "tab" value = "admin:admin_view:view_tables:viewfieldrecord">

<?php
echo '<div class = "pure-control-group">';
echo '<label for="from">From:</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '</div>';

echo '<div class = "pure-control-group">';
echo '<label for="to"> To:</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '</div>';
?>

<div class = "pure-control-group">
<label for="fieldID">FieldID:</label>
<select id = "fieldID" name="fieldID" class='mobile-select'>
<?php
$result = $dbcon->query("SELECT distinct fieldID from field_GH");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit" >
</form>
