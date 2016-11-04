<?php session_start();?>
<form name='form' class = 'pure-form pure-form-aligned'  method='GET' action='laborTable.php'>
<input type="hidden" name="tab" value='labor:labor_report'>
<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
echo '<center><h2 class="hi"> Labor Report </h2></center>';
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
<label for="crop"> Crop:</label>
<!--
<select name="crop" id="crop" onChange="addFieldID();" class="mobile-select">
-->
<select name="crop" id="crop" class="mobile-select">
<option value = "%"> All </option>
<?php
$result = $dbcon->query("SELECT distinct crop from laborview order by crop");
while ($row = $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row[crop]\">$row[crop]</option>";
}
?>
</select>
</div>
<div class = "pure-control-group">
<label for="fieldID">Name of Field: </label>
<select id= "fieldID" name="fieldID" class="mobile-select">
<option value="%"> All </option>
<option value="N/A"> N/A </option>
<?php
$sql = "select fieldID from field_GH where active = 1";
$res = $dbcon->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row[fieldID]\">$row[fieldID]</option>";
}
?>
</select>
</div>
<!--
<script type="text/javascript">
 function addFieldID() {
	
console.log("HERE");
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

window.onload = function() {addFieldID();}
</script>
-->

<div class = "pure-control-group">
<label for="taskDiv"> Task:</label>
<select name="task" id="task" class="mobile-select">
<option value = "%"> All </option>
<?php
$result = $dbcon->query("SELECT distinct task from task");
echo "\n<option value= \"TRANSPLANTING\">TRANSPLANTING</option>";
echo "\n<option value= \"DIRECT PLANTING\">DIRECT PLANTING</option>";
echo "\n<option value= \"HARVESTING\">HARVESTING</option>";
if ($_SESSION['dryfertilizer']) {
   echo "\n<option value= \"DRY FERTILIZER\">DRY FERTILIZER</option>";
}
if ($_SESSION['liquidfertilizer']) {
   echo "\n<option value= \"LIQUID FERTILIZER\">LIQUID FERTILIZER</option>";
}
if ($_SESSION['insect']) {
   echo "\n<option value= \"INSECT SCOUTING\">INSECT SCOUTING</option>";
}
if ($_SESSION['weed']) {
   echo "\n<option value= \"WEED SCOUTING\">WEED SCOUTING</option>";
}
if ($_SESSION['disease']) {
   echo "\n<option value= \"DISEASE SCOUTING\">DISEASE SCOUTING</option>";
}
while ($row =  $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row[task]\">$row[task]</option>";
}
?>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit">

