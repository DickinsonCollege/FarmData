<?php session_start();?>
<form name='form' method='GET' action='laborTable.php'>
<input name="tab" type="hidden" value="admin:admin_delete:deleteother:deletelabor">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h3 class="hi"> Select Labor Records: </h3>
<br clear="all">
<h4 class="hi"> (note: use "Edit/Delete Harvesting Record" or "Edit/Delete Transplanting Record" to
 edit/delete records for those tasks) </h4>
<br clear="all">
<label for="from">From:&nbsp;</label> 
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all">
<label for="crop"> Crop:&nbsp;</label>
<div id="crop2" class ="styled-select">
<select name="crop" id="crop" onChange="addFieldID();" class='mobile-select'>
<option value = "%"> All </option>
<?php
$result = mysql_query("SELECT distinct crop from laborview order by crop");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row[crop]\">$row[crop]</option>";
}
?>
</select>
</div>
<br clear="all">
<label for="fieldID"> Field ID:&nbsp; </label>
<div id="fieldID2" class="styled-select">
<select id= "fieldID" name="fieldID" class='mobile-select'>
<option value="%"> All </option>
</select>
</div>
<script type="text/javascript">
 function addFieldID() {
	
	var newdiv = document.getElementById('fieldID2');
	var e = document.getElementById("crop");
	var f = document.getElementById("tyear");
	var g = document.getElementById("year");
	var crop = e.value;
	var tyear = f.value;
	var year = g.value;
	xmlhttp= new XMLHttpRequest();
	console.log(crop+tyear+year);
	xmlhttp.open("GET", "/Harvest/update_field.php?crop="+crop+"&plantyear="+year+"&tyear="+tyear, false);
	xmlhttp.send();
	console.log(xmlhttp.responseText);
	newdiv.innerHTML="<div id='fieldID2' class='styled-select'>  <select name= 'fieldID' id= 'fieldID' class='mobile-select'> "
        + '<option value="%"> All </option>'
        + xmlhttp.responseText+"</select> </div>";
}
addFieldID();
</script>

<br clear="all">
<label for="taskDiv"> Task:&nbsp;</label>
<div id="taskDiv" class ="styled-select">
<select name="task" id="task" class='mobile-select'>
<option value = "%"> All </option>
<?php
$result = mysql_query("SELECT distinct task from task");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row[task]\">$row[task]</option>";
}
?>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
