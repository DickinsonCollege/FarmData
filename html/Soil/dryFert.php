<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3> Dry Fertilizer Input </h3>
<br clear="all"/>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_input">
<label for='date'> Fertilizer Application Date: </label>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="fieldID"> Field ID: </label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID" onchange="addInput();addInput3();" class="mobile-select">
<option value = 0 selected disabled> FieldID</option>
<?php
$result=mysql_query("Select distinct fieldID from field_GH");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<!--
<br clear="all">
<label for="crop"> Crop Group:&nbsp; </label>
<div class="styled-select" id="crop2">
<select name ="crop" id="crop" class="mobile-select">
<option value = 0 selected disabled> Crop Group</option>
<?php
$result=mysql_query("Select cropGroup from cropGroupReference");
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[cropGroup]\">$row1[cropGroup]</option>";
}
?>
</select>
</div>
<br clear="all"/>
-->
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>

<label for="crop"> Material: &nbsp; </label>
<div class="styled-select" id="mat2">
<select name ="mat" id="mat" class="mobile-select">
<option value = 0 selected disabled> Material </option>
<?php
$result=mysql_query("Select fertilizerName from fertilizerReference");
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[fertilizerName]\">$row1[fertilizerName]</option>";
}
?>
</select>
</div>
<br clear="all"/>

<label for"rate"> Rate of Application:&nbsp; </label>
<input type="text" class = "textbox2 mobile-input" name = "rate" id = "rate" onkeyup="addInput3();">
<!--
<div class="styled-select" id="rate2">
<select name ="rate" id="rate" onchange="addInput3();" >
<option disabled value = 0 selected>Rate</option>
<?php
$result= 5;
while ($result <= 100){
echo "\n<option value= \"$result\">$result</option>";
$result = $result + 5;
}
echo "</select>";
echo "</div>";
?>
-->
<label>&nbsp; Pounds/Acre</label>
<br clear="all">
<script>
var length = 0;
function addInput() {
   var newdiv = document.getElementById("bed2");
   var fieldID = encodeURIComponent(document.getElementById("fieldID").value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "update_beds.php?fieldID="+fieldID, false);
   xmlhttp.send();
   newdiv.innerHTML = "<div class='styled-select' id='bed2'><select onchange='addInput3();' name ='beds' id='beds' selected>"+xmlhttp.responseText+"</select></div>";

   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "update_pounds.php?fieldID="+fieldID, false);
   xmlhttp.send();
   length = xmlhttp.responseText;
}


function addInput3() {
console.log("length: " + length);
   var newdiv = document.getElementById("pounds2");
   var rate = document.getElementById("rate").value;
   var width = document.getElementById("width").value;
//   var beds = document.getElementById("beds").value;
//   var fieldID = document.getElementById("fieldID").value;
   newdiv.innerHTML = "<div id='pounds2'><input class='textbox25 mobile-input' type='text' readonly name='pounds' id='pounds' value="+((length*width*rate)/43560).toFixed(2)+"></div>";
}
</script>

<label for ="beds"> Number of Beds: </label>
<div class="styled-select" id="bed2">
<select onchange='addInput3();' name ="beds" id="beds" class="mobile-select">
<option value = 0 selected disabled> Number of Beds </option>
</select>
</div>
<br clear="all">
<label style="float:left;" for="width"> Width of Planted Area: &nbsp; </label>
<div class="styled-select" id="rows2">
<select name="width" id="width" onchange="addInput3();" class="mobile-select">
<option value=0 selected disabled> Width (feet)</option>
<?php
$result= 1;
while ($result < 5){
   echo "\n<option value= \"$result\">$result</option>";
   $result++;
}
echo "</select>";
echo "</div>";
?>
<br clear="all"/>

<label for="total"> Pounds per Bed to Apply:&nbsp; </label>
<div id="pounds2">
<input class="textbox25 mobile-input" type="text" readonly name="pounds" id="pounds" value=0>
</div>
<br clear="all">

<script> 
function show_confirm() {
   var mth = document.getElementById("month").value;
   var con = "Fertilizer Application Date: " + mth + "-";
   var dy = document.getElementById("day").value;
   con += dy + "-";
   var yr = document.getElementById("year").value;
   con += yr + "\n";
   
   var fld = document.getElementById("fieldID").value;
   if (checkEmpty(fld)) {
      alert("Please Select a Field");
      return false;
   }
   con += "Field ID: "+ fld + "\n";

   var crps = "";
   for (var i = 1; i <= numCropRows; i++) { 
      var crp = document.getElementById("crop" + i).value;
      if (checkEmpty(crp)) {
         alert("Please Select a Crop in row " + i);
         return false;
      } else {
         if (crps != "") {
            crps += "; ";
         }
         crps += crp;
      }
   }
   con += "Crops: "+ crps + "\n";

   var mat = document.getElementById("mat").value;
   if (checkEmpty(mat)) {
      alert("Please Select a Material");
      return false;
   }
   con += "Material: "+ mat + "\n";

   var rt = document.getElementById("rate").value;
   if (checkEmpty(rt)) {
      alert("Please Enter Application Rate ");
      return false;
   }
   con += "Rate: "+ rt + " lbs/acre\n";

   var bds = document.getElementById("beds").value;
   if (checkEmpty(bds)) {
      alert("Please Select Number of Beds");
      return false;
   }
   con += "Beds: " + bds + "\n";

   var wd = document.getElementById("width").value;
   if (checkEmpty(wd)) {
      alert("Please Select Width");
      return false;
   }
   con += "Width: " + wd + " feet\n";

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>
<label for="comments"> Comments: </label>
<br clear="all">
<textarea name="comments" id="comments"
cols=30 rows=10>
</textarea>
<br clear="all"><br>
<input type="submit" class="submitbutton" value="Submit" name="submit" onclick="return show_confirm();">
</form>
<form method="POST" action = "fertReport.php?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report"><input type="submit" class="submitbutton" value = "View Table">
</form>
<?php
if (isset($_POST['submit'])) {
   $comments = escapehtml($_POST['comments']);
   $username = escapehtml($_SESSION['username']);
   $fieldID = escapehtml($_POST['fieldID']);
   $mat = escapehtml($_POST['mat']);
   $crops = "";
   $numCrops = $_POST['numCropRows'];
   for ($i = 1; $i <= $numCrops; $i++) {
      if ($crops != "") {
          $crops .= "; ";
      }
      $crops .= escapehtml($_POST['crop'.$i]);
   }
   echo "<script>addInput3();</script>";
   $sql="Insert into fertilizer(username,inputDate,fieldID, fertilizer, crops, rate, numBeds, totalApply, comments) values ('".
      $username."','".$_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID."','".$mat.
      "', '".$crops."',".$_POST['rate'].",".$_POST['beds'].",".$_POST['pounds'] * $_POST['beds'].
      ",'".$comments."')";
   $result = mysql_query($sql);
   if (!$result) {
      echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>


