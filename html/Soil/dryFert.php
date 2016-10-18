<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<center>
<h2> Dry Fertilizer Input </h2>
</center>
<form name='form' class="pure-form pure-form-aligned" id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_input">
<div class="pure-control-group">
<label for='date'> Fertilizer Application Date: </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<div class="pure-control-group">
<label for="fieldID"> Field ID: </label>
<select name ="fieldID" id="fieldID" onchange="addInput();addInput3();" class="mobile-select">
<option value = 0 selected disabled> FieldID</option>
<?php
$result=$dbcon->query("Select distinct fieldID from field_GH where active=1");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>

<div class="pure-control-group">
<label for="crop"> Material:  </label>
<select name ="mat" id="mat" class="mobile-select">
<?php
$result=$dbcon->query("Select fertilizerName from fertilizerReference");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= \"$row1[fertilizerName]\">$row1[fertilizerName]</option>";
}
?>
</select>
</div>

<div class="pure-control-group">
<label for"rate"> Rate of Application: </label>
<input type="text" class = "textbox2 mobile-input single_table" name = "rate" id = "rate" onkeyup="addInput3();">
&nbsp; Pounds/Acre
</div>
<script>
var length = 0;
function addInput() {
   var newdiv = document.getElementById("bed2");
   var fieldID = encodeURIComponent(document.getElementById("fieldID").value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "update_beds.php?fieldID="+fieldID, false);
   xmlhttp.send();
   newdiv.innerHTML = '<div class="pure-control-group" id="bed2">' +
     '<label for ="beds"> Number of Beds: </label> ' + 
     '<select onchange="addInput3();" name ="beds" id="beds" class="mobile-select">' +
     xmlhttp.responseText + '</select></div>';

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
   newdiv.innerHTML = '<div class="pure-control-group" id="pounds2">' +
     '<label for="total"> Pounds per Bed to Apply: </label> ' + 
     '<input type="text" readonly name="pounds" id="pounds" value=' +
     ((length*width*rate)/43560).toFixed(2)+"></div>";

/*
"<div id='pounds2'><input class='textbox25 mobile-input' type='text' readonly name='pounds' id='pounds' value="+((length*width*rate)/43560).toFixed(2)+"></div>";
*/
}
</script>

<div class="pure-control-group" id="bed2">
<label for ="beds"> Number of Beds: </label>
<select onchange='addInput3();' name ="beds" id="beds" class="mobile-select">
<option value = 0 selected disabled> Number of Beds </option>
</select>
</div>

<div class="pure-control-group">
<label for="width"> Width of Planted Area: </label>
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

<div class="pure-control-group" id="pounds2">
<label for="total"> Pounds per Bed to Apply: </label>
<input class="textbox25 mobile-input single_table" type="text" readonly name="pounds" id="pounds" value=0>
</div>

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
<?php
   if ($_SESSION['labor']) {
      echo 'var wk = document.getElementById("numW").value;
      if (checkEmpty(wk) || tme<=wk || !isFinite(wk)) {
         showError("Enter a valid number of workers!");
         return false;
      }
      con = con+"Number of workers: " + wk + "\n";
      var tme = document.getElementById("time").value;
      var unit = document.getElementById("timeUnit").value;
      if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
         showError("Enter a valid number of " + unit + "!");
         return false;
      }
      con = con+"Number of " + unit + ": " + tme + "\n";';
   }
?>

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>

<?php
if ($_SESSION['labor']) {
   echo '
   <div class="pure-control-group">
   <label for="numWorkers">Number of workers (optional):</label>
   <input onkeypress= \'stopSubmitOnEnter(event)\'; type = "text" value = 1 name="numW" id="numW" class="textbox2mobile-input single_table">
   </div>

   <div class="pure-control-group">
   <label>Enter time in Hours or Minutes:</label>
   <input onkeypress=\'stopSubmitOnEnter(event);stopTimer();\' type="text" name="time" id="time"
   class="textbox2 mobile-input-half single_table" value="1">
   <select name="timeUnit" id="timeUnit" class=\'mobile-select-half single_table\' onchange="stopTimer();">
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
   </select>
   </div> ';

   include $_SERVER['DOCUMENT_ROOT'].'/timer.php';
}
?>
<div class="pure-control-group">
<label for="comments"> Comments: </label>
<textarea name="comments" id="comments"
cols=30 rows=5>
</textarea>
</div>
<br clear="all"><br>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" class="submitbutton pure-button wide" value="Submit" name="submit" onclick="return show_confirm();">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "fertReport.php?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report"><input type="submit" 
 class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();">
</form>
</div>
</div>
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
   if ($_SESSION['labor']) {
      // Check if given time is in minutes or hours
      $time = escapehtml($_POST['time']);
      if ($_POST['timeUnit'] == "minutes") {
         $hours = $time/60;
      } else if ($_POST['timeUnit'] == "hours") {
         $hours = $time;
      }
      // Check if num workers is filled in
      $numW = escapehtml($_POST['numW']);
      if ($numW != "") {
         $totalHours = $hours * $numW;
      } else {
         $totalHours = $hours;
      }
   } else {
      $totalHours = 0;
   }

   $sql="Insert into fertilizer(username,inputDate,fieldID, fertilizer, crops, rate, numBeds, totalApply, ".
      "comments, hours) values ('".
      $username."','".$_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID."','".$mat.
      "', '".$crops."',".$_POST['rate'].",".$_POST['beds'].",".$_POST['pounds'] * $_POST['beds'].
      ",'".$comments."', ".$totalHours.")";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
}
?>


