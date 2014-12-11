<?php session_start(); ?>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_spray:bspray:bspray_input">
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<h3> Backpack Spraying Input Form </h3>
<br clear="all"/>
<label for='date'> Date: &nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<script>
function show_confirm() {
   var mth = document.getElementById("month").value;
   var con="Backpack Spray Date: "+mth+"-";
   var dy = document.getElementById("day").value;
   con += dy + "-";
   var yr = document.getElementById("year").value;
   con += yr + "\n";
   var fld = document.getElementById("fieldID").value;
   if (checkEmpty(fld)) {
       alert("Please Select a FieldID");
       return false;
   }
   con += "FieldID: "+ fld + "\n";
   var wat = document.getElementById("water").value;
   if (checkEmpty(wat)) {
      alert("Please Input How Much Water Was Used in Gallons");
      return false;
   }
   con += "Water (Gallons): "+ wat + "\n";
   var mat = document.getElementById("material").value;
   if (checkEmpty(mat)) {
       alert("Please Select Material Sprayed");
       return false;
   }
   con=con+"Material Sprayed: "+ mat + "\n";
   var rt = document.getElementById("rate").value;
   if (checkEmpty(rt)) {
       alert("Please Input Rate");
       return false;
   }
   con += "Rate: "+ rt + "\n";
   var tot = document.getElementById("tot").value;
   if (checkEmpty(tot) || tot <= 0 || isNaN(tot)) {
       alert("Please Input Total Material Actual");
       return false;
   }
   con += "Total Material: "+ tot + "   \n";

   var mx = document.getElementById("mix").value;
   if (checkEmpty(mx)) {
       alert("Please Input What the Spray was Mixed With");
       return false;
   }
   con += "Mixed With: " + mx + "\n";
   var crps = "";
   for (var i = 1; i <= numCropRows; i++) {
       var crp = document.getElementById("crop" + i).value;
       if (checkEmpty(crp)) {
           alert("Please Select the Crop Sprayed in row " + i);
           return false;
       } else {
           if (crps != "") {
              crps += "; ";
           }
           crps += crp;
       }
   }
   con += "Crops Sprayed: "+ crps + "\n";

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>
<label for="fieldID"> Field ID: </label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID" class="mobile-select">
<option value = 0 selected disabled> FieldID</option>
<?php 
$result=mysql_query("Select fieldID from field_GH");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="tractor"> Water (Gallons): &nbsp;</label>
<div class="styled-select" id="water2">
<select onchange="addInput();" name ="water" id="water" class="mobile-select">
<option value = 0 selected disabled> Gallons </option>
<?php 
$num = 1;
while ($num < 7){  
echo "\n<option value= '$num'> $num </option>";
$num++;
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="implement"> Material Sprayed: &nbsp;</label>
<div class="styled-select" id="material2">
<select onchange=" addInput2(); addInput3(); addInput();" name ="material" id="material" class="mobile-select">
<option value = 0 selected disabled> Material </option>
<?php 
$result=mysql_query("Select sprayMaterial from tSprayMaterials where active = 1");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[sprayMaterial]\">$row1[sprayMaterial]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="rate"> Rate:&nbsp; </label>
<script type="text/javascript">
 function addInput2(){
    var newdiv = document.getElementById('rate2');
    var mat = encodeURIComponent(document.getElementById("material").value);
    xmlhttp= new XMLHttpRequest();
    xmlhttp.open("GET", "update_rate.php?material="+mat, false);
    xmlhttp.send();
    console.log(xmlhttp.responseText);
    newdiv.innerHTML="<div class='styled-select' id ='rate2'>  <select onchange='addInput();' name= 'rate' id= 'rate' class='mobile-select'>"+xmlhttp
        .responseText+"</select> </div>";
}

function addInput() {
    var newdiv = document.getElementById('total2');
    var e = document.getElementById("rate");
    var strUser = e.value;
    var strUser2 = document.getElementById("water").value;
    var strUser3 = document.getElementById("material").value;
    var total = strUser * strUser2;
    newdiv.innerHTML="<div id ='total2'> <input name='total4' class='textbox2 mobile-input' type='text' id='total4' disabled value="+total+">";
}

function addInput3() {
    var mat = encodeURIComponent(document.getElementById("material").value);
    xmlhttp= new XMLHttpRequest();
    xmlhttp.open("GET", "update_unit.php?material="+mat, false);
    xmlhttp.send();
    console.log(xmlhttp.responseText);
    document.getElementById('unit4').innerHTML = "<label for='unit'>&nbsp;"+xmlhttp.responseText+"PER GALLON</label>";
    document.getElementById('total25').innerHTML = "<label for='unit35'>&nbsp;"+xmlhttp.responseText+"</label>";
    document.getElementById('total3').innerHTML = "<label for='unit2'>&nbsp;"+xmlhttp.responseText+"</label>";

}
 </script>
<div class="styled-select" id="rate2">
<select onchange="addInput();" name ="rate" id="rate" class="mobile-select">
<option value=0 selected disabled>Rate</option>
</select>
</div>
<div class="styled-select" id="unit4">
<label for="unit"> </label>
</div>
<br clear = "all"/>
<label for="passes"> Total Material, Suggested:&nbsp; </label>
<div id="total2">
<input name="total4" class= "textbox2 mobile-input"  type="text" disabled id="total4">
</div>
<div id="total25">
<label for="unit35"> </label>
</div>
<br clear="all"/>
<label style="margin-top:10px; font-size: 18pt;" for="passes2">(Please Input Total Material Actual Even if You Agree with the Suggested Amount) </label>
<br clear="all"/>
<label for="total"> Total Material Actual:&nbsp; </label>
<input type="text" class="textbox2 mobile-input" id="tot" name="tot">
<div id="total3">
<label for="unit2"> </label>
</div>
<br clear="all"/>
<label for="minues"> Mixed With:&nbsp; </label>
<input type="text" class="textbox2 mobile-input" id="mix" name="mix">
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>

<div>
<label for="comments"><b>Comments:</b></label>
<br clear="all"/>
<textarea name ="comments"
rows="10" cols="30">
</textarea>
</div>
<br clear="all"/>
<input type="submit" class = "submitbutton" name="submit" value="Submit" onclick= "return show_confirm();">
<br clear="all"/>
</form>
<form method="POST" action = "/Soil/sprayReport.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton" value = "View Table">
</form>
<?php
if(!empty($_POST['submit'])) {
   $comSanitized=escapehtml($_POST['comments']);
   $water=escapehtml($_POST['water']);
   $tot=escapehtml($_POST['tot']);
   $mix=escapehtml($_POST['mix']);
   $material=escapehtml($_POST['material']);
   $fieldID=escapehtml($_POST['fieldID']);
   $numCropRows=escapehtml($_POST['numCropRows']);
   $crops="";
   for ($i = 1; $i <= $numCropRows; $i++) {
      if ($crops != "") {
         $crops .= "; ";
      }
      $crops .= escapehtml($_POST['crop'.$i]);
    }
   $rate=escapehtml($_POST['rate']);
   echo $sql = "Insert into bspray(sprayDate,fieldID, water,materialSprayed, rate, totalMaterial, mixedWith, crops, comments) values('".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."','" .
      $fieldID."',".$water.",'".$material."',".$rate.",".$tot.",'".$mix.
      "','".$crops."','".  $comSanitized."');";
   $result = mysql_query($sql);
   if(!$result){
      echo "<script>alert(\"Could not input Backpack Spray Record: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Backpack Spray Record Entered Successfully!\");</script> \n";
   }
}
?>
