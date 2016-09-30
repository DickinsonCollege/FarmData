<SCRIPT type="text/javascript">
function overlay() {
        var but = document.getElementById("cropButton");
        var yr = document.getElementById("year");
        if (yr == null) {
           var yr = document.getElementById("tyear");
        }
        var typ =
        <?php
           if ($harvesting) {
              echo '"harvesting";';
           } else if ($transplanting) {
              echo '"transplanting";';
           } else if ($laborc) {
              echo '"labor";';
           } else {
              echo '"other";';
           }
        ?>
        xmlhttp= new XMLHttpRequest();
        xmlhttp.open("GET", "/updateCrop.php?year="+yr.value+"&typ="+typ, false);
        xmlhttp.send();
        if(xmlhttp.responseText=="") {
           alert("No crops planted in this year");
           but.value="";
        }
        but.innerHTML="<div class=\"styled-select\">" + 
           '<select name="crop" id="cropButton" onChange="update();clearTable();">' +
//           '<option disabled selected="selected"  value = 0> Crop </option>' +
           xmlhttp.responseText + "</select></div>";
}

function update() {
   if (typeof addInput == 'function') {
       addInput();
   }
   if (typeof addInput2 == 'function') {
       addInput2();
   }
}

function clearTable() {
}
</SCRIPT>
<div class='pure-control-group'>
<label for="cropButton">Crop:</label>
<select name="crop" id="cropButton" class='mobile-select' onChange="update();clearTable();">
<!--
<option disabled selected="selected"  value = 0> Crop </option>
-->
</select>
</div>
<script type="text/javascript">
window.onload=function() {
   overlay();
   addInput();
}
</script>
