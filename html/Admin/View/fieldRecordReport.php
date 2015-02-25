<?php session_start(); ?>
<form name='form' method='GET' action='fieldRecordTable.php'>
<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h3 class="hi"> Select Date Range and Field </h3>
<br>
<input type="hidden" name = "tab" value = "admin:admin_view:view_tables:viewfieldrecord">
<?php
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to"> To:&nbsp</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<label for="fieldID"> FieldID:&nbsp;</label>
<div class ="styled-select">
<select id = "fieldID" name="fieldID" class='mobile-select'>
<?php
$result = mysql_query("SELECT distinct fieldID from field_GH");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit" >
</form>
