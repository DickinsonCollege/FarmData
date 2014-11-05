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
           } else if ($labor) {
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
           '<select name="crop" id="cropButton" onChange="update()";>' +
           '<option disabled selected="selected"  value = 0> Crop </option>' +
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
</SCRIPT>
<label for="cropButton"><b>Crop:&nbsp;</b></label>
<div class="styled-select">
<select name="crop" id="cropButton" class='mobile-select' onChange="update()";>
<option disabled selected="selected"  value = 0> Crop </option>
</select>
</div>
<script type="text/javascript">
overlay();
</script>
<?php
//$result=mysql_query("Select crop from plant");
//while ($row1 =  mysql_fetch_array($result)){
//     echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
//}
?>


<!--
<INPUT TYPE="text" class = "textbox5" readonly = "true" id = "cropButton" name = "crop" onclick="overlay();">
<div id="overlay">
     <div>
-->
<?php
//echo "harv: ".$harvesting;
//echo "\n";
//echo "trans: ".$transplanting;
//echo "\n";
      //$result = mysql_query("SELECT crop from plant");
/*
      if (!$cropQuery) {
         $cropQuery = "select distinct crop from plant";
      }
      $result = mysql_query($cropQuery);
       while ($row1 =  mysql_fetch_array($result)){
          echo '<input type="button" class = "choosecrop" value = "'.$row1['crop'].
             '" onclick="closeWin(\''.$row1['crop'].'\');">';
       }
*/
?>
<!--
     </div>
</div>
-->
