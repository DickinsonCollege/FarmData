<?php session_start(); ?>
<form name='form' method='POST' action='coverTable.php?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
echo '<h3 class="hi"> Cover Crop Seeding Report </h3>';
echo "<br clear=\"all\">";
// echo  '<h1> Seeding Date Range</h1>';
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<script type="text/javascript">
 function addFieldID() {
//console.log("called");	
	var newdiv = document.getElementById('fieldID23');
	var e = document.getElementById("crop1");
	var h = document.getElementById("crop2");
	var f = document.getElementById("tyear");
	var g = document.getElementById("year");
	var crop1 = e.value;
	var crop2 = h.value;
	var tyear = f.options[f.selectedIndex].text;
	var year = g.options[g.selectedIndex].text;
	//console.log(crop1);
	//console.log(crop2);
	xmlhttp= new XMLHttpRequest();
	xmlhttp.open("GET", "update_fieldID2.php?crop1="+crop1+"&crop2="+crop2+"&year="+year+"&tyear="+tyear, false);
	xmlhttp.send();
	// console.log("update!" + xmlhttp.responseText);
	newdiv.innerHTML="<div id='fieldID23' class='styled-select'><select name= 'fieldID' id= 'fieldID' class='mobile-select'> <option value= \"%\" selected> All </option> " +xmlhttp.responseText+"</select></div>";
}
</script>
<br clear="all">
<?php
$active = 'active';
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<br clear="all">
<input type="submit" class="submitbutton" name="submit" value="Submit">
</form>

