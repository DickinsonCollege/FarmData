<?php session_start();?>
<body onload="addFieldID();">
<form name='form' method='POST' action='harvestTable.php?tab=harvest:harvestReport'>
<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<h3>Harvest Report</h3>
<br clear="all">

<?php 
echo '<label for="from">From:&nbsp;</label> '; 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To:&nbsp;</label> '; 
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all">
<label for="crop"> Crop:&nbsp;</label>
<div id="crop2" class ="styled-select">
<select name="crop" id="crop" class="mobile-select" onChange="addFieldID()">
<option value = "%"> All </option>
<?php
$result = mysql_query("SELECT distinct crop from harvested");
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
</select>
</div>
<script type="text/javascript">
 function addFieldID() {
	
	var newdiv = document.getElementById('fieldID2');
	var crop = encodeURIComponent(document.getElementById("crop").value);
	var tyear = document.getElementById("tyear").value;
	var year = document.getElementById("year").value;
	xmlhttp= new XMLHttpRequest();
	console.log(crop+tyear+year);
	xmlhttp.open("GET", "update_fieldID.php?crop="+crop+"&year="+year+"&tyear="+tyear, false);
	xmlhttp.send();
	console.log(xmlhttp.responseText);
	newdiv.innerHTML="<div id='fieldID2' class='styled-select'>  <select name= 'fieldID' id= 'fieldID' class='mobile-select'> "+xmlhttp.responseText+"</select> </div>";
}
</script>
<?php
if ($_SESSION['gens']) {
   echo '<br clear="all"/>';
   echo '<label for="genSel">Generation #:&nbsp;</label>';
   echo '<div class="styled-select">';
   echo '<select name="genSel" class="mobile-select">';
   echo '<option value = "%" selected="selected"> All </option>';
   $result = mysql_query("SELECT distinct gen from harvested order by gen");
   while ($row1 =  mysql_fetch_array($result)){
      echo "\n<option value= \"$row1[gen]\">$row1[gen]</option>";
   }
   echo '</select>';
   echo '</div>';
} else {
   echo '<input type="hidden" name="genSel" value="%">';
}
?>

<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
