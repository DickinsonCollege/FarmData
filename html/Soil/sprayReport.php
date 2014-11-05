<?php session_start(); ?>
<form name='form' method='POST' action='sprayTable.php?tab=soil:soil_spray:bspray:bspray_report'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<h3 class="hi"> Backpack Spray Report </h3>
<br clear="all"/>
<label for="from">From:&nbsp;</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="to"> To:&nbsp;</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<label for="material"> Sprayed Material:&nbsp;</label>
<script type="text/javascript"> 
	function addFieldID() {        
       var newdiv = document.getElementById('fieldID');
        var f = document.getElementById("tyear");
        var g = document.getElementById("year");
        var material = encodeURIComponent(document.getElementById("sprayMaterial").value);
        var tyear = f.value;
	var year = g.value;
	  xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "update_fieldID3.php?sprayMaterial="+material+"&year="+year+"&tyear="+tyear, false);
	xmlhttp.send();
	console.log(xmlhttp.responseText);        
        newdiv.innerHTML="<div id='fieldID23' class='styled-select'>  <select name= 'fieldID' id= 'fieldID'> <option value='%' selected> All </option>"+xmlhttp.responseText+"</select> </div>";
}
</script>
<div id="crop4" class ="styled-select">
<select name="sprayMaterial" id="sprayMaterial"  onChange="addFieldID();" class="mobile-select">
<option value = "%" selected> All </option>
<?php
$result = mysql_query("SELECT distinct sprayMaterial from tSprayMaterials where active=1 ");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row[sprayMaterial]\">$row[sprayMaterial]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<label for="fieldID"> Field ID: &nbsp; </label>
<div id="fieldID23" class="styled-select">
<select id= "fieldID" name="fieldID" class="mobile-select">
<option value="%" selected> All </option>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton" name="submit" value="Submit">
</form>
