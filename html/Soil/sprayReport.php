<?php session_start(); ?>
<form name='form' class='pure-form pure-form-aligned' method='GET' action='sprayTable.php'>
<input type="hidden" name="tab" value='soil:soil_spray:bspray:bspray_report'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<center>
<h2 class="hi"> Backpack Spray Report </h2>
</center>

<div class="pure-control-group">
<label for="from">From:</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for="to"> To:</label> 
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
</div>

<div class="pure-control-group">
<label for="material"> Sprayed Material:</label>
<script type="text/javascript"> 
	function addFieldID() {        
       var newdiv = document.getElementById('fieldID23');
        var f = document.getElementById("tyear");
        var g = document.getElementById("year");
        var material = encodeURIComponent(document.getElementById("sprayMaterial").value);
        var tyear = f.value;
	var year = g.value;
	  xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "update_fieldID3.php?sprayMaterial="+material+"&year="+year+"&tyear="+tyear, false);
	xmlhttp.send();
        newdiv.innerHTML = '<div class="pure-control-group" id="fieldID23">' +
           '<label for="fieldID">Name of Field: </label> ' +
           '<select id= "fieldID" name="fieldID" class="mobile-select">' +
           '<option value="%" selected> All </option>' +
           xmlhttp.responseText + '</select></div>';
}
</script>
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

<div class="pure-control-group" id="fieldID23">
<label for="fieldID">Name of Field: </label>
<select id= "fieldID" name="fieldID" class="mobile-select">
<option value="%" selected> All </option>
</select>
</div>

<div class="pure-control-group">
<label for="Crop"> Crop: </label>
<select id= "crop" name="crop" class="mobile-select">
<option value="%" selected> All </option>
<?php
$result = mysql_query("SELECT crop from plant");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= '".$row['crop']."'>".$row['crop']."</option>";
}
?>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton pure-button wide" name="submit" value="Submit">
</form>
