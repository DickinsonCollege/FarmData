<?php
 session_start(); 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<form name='form' method='GET' action='bsprayTable.php'>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletespray:deletebspray">
<h3 class="hi"> Select Backpack Spraying Records: </h3>
<br>
<label for="from">From:</label> 
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to"> To:</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all"/>';
?>
<label for="material"> Sprayed Material:</label>
<script type="text/javascript"> 
function addFieldID() {        
       var newdiv = document.getElementById('fieldID');
        var material = encodeURIComponent(document.getElementById("sprayMaterial").value);
        var tyear = document.getElementById("tyear").value;
	var year = document.getElementById("year").value;
	console.log("inside");
	  xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "/Soil/update_fieldID3.php?sprayMaterial="+material+"&year="+year+"&tyear="+tyear, false);
	xmlhttp.send();
	console.log(xmlhttp.responseText);        
        newdiv.innerHTML="<div id='fieldID23' class='styled-select'>  <select name= 'fieldID' id= 'fieldID'> <option value='%' selected> All </option>"+xmlhttp.responseText+"</select> </div>";
}
</script>
<div id="crop4" class ="styled-select">
<select name="sprayMaterial" id="sprayMaterial"  onChange="addFieldID();" class='mobile-select'>
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
<label for="fieldID"> Field ID: </label>
<div id="fieldID23" class="styled-select">
<select id= "fieldID" name="fieldID" class='mobile-select'>
<option value="%" selected> All </option>
</select>
</div>
<br clear="all"/>
<br clear="all"/>

<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
