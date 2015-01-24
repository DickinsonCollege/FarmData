 <script type="text/javascript">
 function getflats() {
    var p = document.getElementById("cropButton");
    var plnt = encodeURIComponent(p.value);
    var d = document.getElementById("seedDate");
    var dt = d.value;
    xmlhttp= new XMLHttpRequest();
    xmlhttp.open("GET", "getflats.php?crop="+plnt+"&date="+dt, false);
    xmlhttp.send();
    var fl = document.getElementById("flatsBox");
    fl.value= xmlhttp.responseText;
 }

 function addInput() {
      var newdiv = document.getElementById('seedInput');
        //for (i=0;i<newdiv.options.length-1;i++) {
        //ne/wdiv.remove(i);
//      }
//document.getElementById(div).appendChild(newdiv);
var e = document.getElementById("cropButton");
var strUser= encodeURIComponent(e.value);
//console.log(strUser);
xmlhttp= new XMLHttpRequest();
xmlhttp.open("GET", "update_trans.php?crop="+strUser, false);
xmlhttp.send();
//console.log(xmlhttp.responseText);
newdiv.innerHTML="<div class='styled-select' id ='seedInput'>" +
   "<select name= 'seedDate' id= 'seedDate' onchange='getflats();'>" + 
   xmlhttp.responseText+"</select> </div>";
   getflats();
}
 </script>
<?php
 $transplanting = true;
 $labor = false;
 $harvesting = false;
 include $_SERVER['DOCUMENT_ROOT'].'/chooseCrop.php';
?>
<!--
<label for="seedDate">Crop:</label>
<div id="plant" class="styled-select">
<select name="crop" id="crop" onChange="addInput()" >
<option value= 0 selected="selected" style="display:none" disabled> Crop</option>
<?php 
//$result = mysql_query("SELECT distinct  crop FROM gh_seeding");
	//while($row = mysql_fetch_array($result)) { 
		//echo "\n<option value=\"$row[crop]\">$row[crop]</option>";
	//} 
?>
</select>
</div>
-->
<br clear="all">
<label for='seedDate'> Date Seeded: </label>
<div id="seedInput" class="styled-select">
<select name="seedDate" id= "seedDate" class='mobile-select'>
<option value=0 selected="selected" style="display:none"> Seed Date </option>
</select>
</div>
<br clear="all">
<label for="flatsBox">Total Flats Seeded:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input" type="text" disabled readonly name ="flatsBox" value= '' id="flatsBox">

