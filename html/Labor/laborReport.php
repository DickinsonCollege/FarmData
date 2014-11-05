<?php session_start();?>
<form name='form' method='POST' action='laborTable.php?tab=labor:labor_report'>
<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
echo '<h3 class="hi"> Labor Report </h3>';
echo "<br clear=\"all\">";
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all">
<label for="crop"> Crop:&nbsp;</label>
<div id="crop2" class ="styled-select">
<select name="crop" id="crop" onChange="addFieldID();" class="mobile-select">
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
<select id= "fieldID" name="fieldID" class="mobile-select">
<option value="%"> All </option>
<option value="N/A"> N/A </option>
</select>
</div>
<script type="text/javascript">
 function addFieldID() {
	
	var newdiv = document.getElementById('fieldID2');
	var f = document.getElementById("tyear");
	var g = document.getElementById("year");
	var crop = encodeURIComponent(document.getElementById("crop").value);
	var tyear = f.value;
	var year = g.value;
	xmlhttp= new XMLHttpRequest();
	xmlhttp.open("GET", "/Harvest/update_field.php?crop="+crop+"&plantyear="+year+"&tyear="+tyear, false);
	xmlhttp.send();
	console.log(xmlhttp.responseText);
	newdiv.innerHTML="<div id='fieldID2' class='styled-select'>  <select class='mobile-select' name= 'fieldID' id= 'fieldID'> "
        + '<option value="%"> All </option>'
        + '<option value="N/A"> N/A </option>'
        + xmlhttp.responseText+"</select> </div>";
}

addFieldID();
</script>

<br clear="all">
<label for="taskDiv"> Task:&nbsp;</label>
<div id="taskDiv" class ="styled-select">
<select name="task" id="task" class="mobile-select">
<option value = "%"> All </option>
<?php
$result = mysql_query("SELECT distinct task from task");
echo "\n<option value= \"TRANSPLANTING\">TRANSPLANTING</option>";
echo "\n<option value= \"DIRECT PLANTING\">DIRECT PLANTING</option>";
echo "\n<option value= \"HARVESTING\">HARVESTING</option>";
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
