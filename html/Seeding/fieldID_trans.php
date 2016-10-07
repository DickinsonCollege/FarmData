 <script type="text/javascript">
 function addFieldID() {}

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
   var e = document.getElementById("cropButton");
   var strUser= encodeURIComponent(e.value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "update_trans.php?crop="+strUser, false);
   xmlhttp.send();
   newdiv.innerHTML= '<div class="pure-control-group" id="seedInput">' + 
      '<label for="seedDate">Date of Tray Seeding: </label>' +
      '<select name="seedDate" id= "seedDate" onchange="getflats();">' + 
      xmlhttp.responseText+"</select> </div>";
   getflats();
}
 </script>
<?php
 $transplanting = true;
 $laborc = false;
 $harvesting = false;
// assumes onload not called from this file
 include $_SERVER['DOCUMENT_ROOT'].'/chooseCrop.php';
?>

<div class="pure-control-group" id = "annualdiv">
<label>Annual:</label>
<select name="annual" id="annual" class="mobile-select" onchange="addLastHarvestDate();">
<option value=1 selected>Annual</option>
<option value=0>Perennial</option>
</select>
</div>

<div class="pure-control-group" id = "lastharvdiv">
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/Seeding/annual.php';
?>

<div class="pure-control-group" id="seedInput">
<label for='seedDate'>Date of Tray Seeding: </label>
<select name="seedDate" id= "seedDate" class='mobile-select'>
<option value=0 selected="selected" style="display:none"> Seed Date </option>
</select>
</div>
<div class="pure-control-group">
<label for="flatsBox">Total Number of Trays Seeded:</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input" type="text" disabled readonly name ="flatsBox" value= '' id="flatsBox">
</div>

