<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' id='test' class='pure-form pure-form-aligned' method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_spray:bspray:bspray_input">
<center>
<h2> Backpack Spraying Input Form </h2>
</center>

<div class="pure-control-group">
<label for='date'> Date: </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
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
<div class="pure-control-group">
<label for="fieldID"> Name of Field: </label>
<select name ="fieldID" id="fieldID" class="mobile-select">
<option value = 0 selected disabled> Field Name</option>
<?php 
$result=mysql_query("Select fieldID from field_GH where active=1");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';
?>

<div class="pure-control-group">
<label for="tractor"> Water (Gallons):</label>
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

<div class="pure-control-group">
<label for="implement"> Material Sprayed: </label>
<select onchange=" addInput2(); addInput();" name ="material" id="material" class="mobile-select">
<option value = 0 selected disabled> Material </option>
<?php 
$result=mysql_query("Select sprayMaterial from tSprayMaterials where active = 1");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[sprayMaterial]\">$row1[sprayMaterial]</option>";
}
echo '</select>';
echo '</div>';
?>

<script type="text/javascript">
 function addInput2(){
    var newdiv = document.getElementById('rate2');
    var mat = encodeURIComponent(document.getElementById("material").value);
    xmlhttp= new XMLHttpRequest();
    xmlhttp.open("GET", "update_rate.php?material="+mat, false);
    xmlhttp.send();
    var content = '<div class="pure-control-group" id="rate2">' +
       '<label for="rate"> Rate: </label> ' + 
       '<select onchange="addInput();" name ="rate" id="rate" class="mobile-select">' + 
      xmlhttp.responseText + '</select>&nbsp;';

    xmlhttp.open("GET", "update_unit.php?material="+mat, false);
    xmlhttp.send();
    content += xmlhttp.responseText + "PER GALLON</div>";
    newdiv.innerHTML = content;
}

function addInput() {
    var newdiv = document.getElementById('total2');
    var e = document.getElementById("rate");
    var strUser = e.value;
    var strUser2 = document.getElementById("water").value;
    var mat = document.getElementById("material").value;
    var total = strUser * strUser2;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "update_unit.php?material="+mat, false);
    xmlhttp.send();
    newdiv.innerHTML = '<div class="pure-control-group" id="total2">' +
       '<label > Total Material, Suggested: </label> ' + 
       '<input name="total4" type="text" readonly id="total4" value = "' +
       total + '">&nbsp;' + xmlhttp.responseText + '</div>';
}

 </script>

<div class="pure-control-group" id="rate2">
<label for="rate"> Rate: </label>
<select onchange="addInput();" name ="rate" id="rate" class="mobile-select">
<option value=0 selected disabled>Rate</option>
</select>
</div>

<div class="pure-control-group" id="total2">
<label > Total Material, Suggested: </label>
<input name="total4" class= "textbox2 mobile-input single_table"  type="text" readonly id="total4">
</div>

<br clear="all"/>
<label>(Please Input Total Material Actual Even if You Agree with the Suggested Amount) </label>
<div class="pure-control-group">
<label for="total"> Total Material Actual: </label>
<input type="text" class="textbox2 mobile-input single_table" id="tot" name="tot">
</div>

<div class="pure-control-group">
<label for="minues"> Mixed With: </label>
<input type="text" class="textbox2 mobile-input single_table" id="mix" name="mix">
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>

<div class="pure-control-group">
<label for="comments">Comments:</label>
<textarea name ="comments"
rows="5" cols="30">
</textarea>
</div>
<br clear="all"/>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" class = "submitbutton pure-button wide" name="submit" value="Submit" onclick= "return show_confirm();">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "/Soil/sprayReport.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();">
</form>
</div>
</div>
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
   $sql = "Insert into bspray(sprayDate,fieldID, water,materialSprayed, rate, totalMaterial, mixedWith, crops, comments) values('".
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
