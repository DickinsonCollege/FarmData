<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
include $_SERVER['DOCUMENT_ROOT'].'/Soil/clearForm.php';
?>
<center>
<h2> Insect Scouting Input Form </h2>
</center>
<form name='form' class='pure-form pure-form-aligned' id='test' method='POST' enctype="multipart/form-data"
   action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_scout:soil_pest:pest_input">
<div class='pure-control-group'>
<label for='date'>Date: </label>
<?php
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
   $dDay = $_POST['day'];
   $dMonth = $_POST['month'];
   $dYear = $_POST['year'];
}
if (isset($_POST['fieldID'])) {
   $field = escapehtml($_POST['fieldID']);
}
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class='pure-control-group'>
<label for="fieldID">Name of Field: </label>
<select name ="fieldID" id="fieldID" class="mobile-select">
<?php
$result=$dbcon->query("Select fieldID from field_GH where active=1");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= '".$row1[fieldID]."'";
   if (isset($field) && $field == $row1['fieldID']) {
      echo " selected";
   }
   echo ">".$row1[fieldID]."</option>";
}
echo '</select>';
echo '</div>';
?>

<div class='pure-control-group'>
<label for="Pest"> Insect: </label>
<select name="pest" id="pest" class="mobile-select">
<?php
$result=$dbcon->query("Select pestName from pest");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){  
   echo "\n<option value= \"$row1[pestName]\">$row1[pestName]</option>";
}
?>
</select>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>
<br clear="all"/>
<center>
<table id="samples" style="width:auto;" class="pure-table pure-table-bordered">
<thead><tr><th>Insects&nbsp;per&nbsp;Plant</th></tr></thead>
<tbody></tbody>
</table>
</center>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" id="addSample" name="addSample" class="submitbutton pure-button wide" 
 onClick="addSampleRow();"
value="Add Sample">
</div>
<div class="pure-u-1-2">
<input type="button" id="removeSample" name="removeSample" 
class="submitbutton pure-button wide" onClick="removeSampleRow();"
value="Remove Sample">
</div>
</div>
<br clear="all"/>

<input type="hidden" name="numSamples" id="numSamples" value="0">
<script type="text/javascript">
var numSamples = 0;

function calculate() {
   var total=0;
   for (var i = 1; i <= numSamples; i++) {
      var val = document.getElementById("sample" + i).value;
      if (val != "") {
         total += parseFloat(val);
      }
   }
   document.getElementById('average').value= total/numSamples;
}

function addSampleRow() {
  // var table = document.getElementById("samples");
  var table = document.getElementById("samples").getElementsByTagName('tbody')[0];
 
  numSamples++;
  document.getElementById("numSamples").value = numSamples;
  var row = table.insertRow(-1);
  row.id = "sampleRow" + numSamples;

  var cell = row.insertCell(0);
/*
  cell.innerHTML =  '<input type="text" class="textbox mobile-input inside_table" ' +
    'name ="sample' + numSamples + '" id="sample' + numSamples + 
    '" style="width:1000%" value="" oninput="calculate();" ' +
    'onkeypress="stopSubmitOnEnter(event);">';
*/
  cell.innerHTML =  '<input type="text" name ="sample' + numSamples + 
    '" id="sample' + numSamples + 
    '" class="textbox2 mobile-input inside_table" style="width:100%" value="" oninput="calculate();" ' +
    'onkeypress="stopSubmitOnEnter(event);">';
}

addSampleRow();

function removeSampleRow() {
   if (numSamples > 0) {
      var row = document.getElementById("sampleRow" + numSamples);
      row.innerHTML = "";
      numSamples--;
      document.getElementById("numSamples").value = numSamples;
      calculate();
   }
}
</script>

<div class="pure-control-group">
<label for="average">Average Insects Per Plant: </label>
<input  type="text" readonly class ="textbox2 mobile-input" id="average" name="average">
</div>


<div class="pure-control-group" id="filediv">
<label for="file">Picture (optional): </label>
<input type="file" name="fileIn" id="file">
</div>

<div class="pure-control-group">
<label for="clear">Max File Size: 2 MB </label>
<input type="button" value="Clear Picture" onclick="clearForm();">
</div>

<?php
if ($_SESSION['labor']) {
echo '
<div class="pure-control-group">
<label for="numWorkers">Number of workers (optional):</label>
<input onkeypress= \'stopSubmitOnEnter(event)\'; type = "text" value = 1 name="numW" id="numW" 
   class="textbox2 mobile-input single_table">
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
<label >Comments:</label>
<textarea name="comments" id="comments"
cols=30 rows=5>
</textarea>
</div>
<script type="text/javascript">
function show_confirm() {
   var fld = document.getElementById("fieldID").value;
   if (checkEmpty(fld)) {
      alert("Please Select a FieldID");
       return false;
    }
    var con="Field ID: "+ fld + "\n";
    var mth = document.getElementById("month").value;
    con += "Scout Date: " + mth + "-";
    var dy = document.getElementById("day").value;
    con += dy + "-";
    var yr = document.getElementById("year").value;
    con += yr + "\n";
    var pst = document.getElementById("pest").value;
    if (checkEmpty(pst)) {
       alert("Please Select an Insect");
       return false;
     }
     con += "Insect: " + pst + "\n";
     var crops="";
     for (var i = 1; i <= numCropRows; i++) {
        var crp = document.getElementById("crop" + i).value;
        if (checkEmpty(crp)) {
           alert("Please Select a Crop in row " + i);
           return false;
        } else {
           if (crops != "") {
              crops += "; ";
           }
           crops += crp;
        }
     }
     con += "Crops: " + crops + "\n";
     
     var avg = document.getElementById("average").value;
     if (avg == "" || !isFinite(avg) || avg < 0) {
        alert("Please enter at least one valid sample (insect count)");
        return false;
     }
     con += "Average Insects Per Plant: " + avg + "\n";
     var fname = document.getElementById("file").value;
     if (fname != "") {
        var pos = fname.lastIndexOf(".");
        var ext = fname.substring(pos + 1, fname.length).toLowerCase();
        if (ext != "gif" && ext != "png" && ext != "jpg" && ext != "jpeg") {
           alert("Invalid image type: only gif, png, jpg and jpeg allowed.");
           return false;
        }
        con += "Picture: "+ fname + "\n";
     }

<?php
if ($_SESSION['labor']) {
   echo '
   var wk = document.getElementById("numW").value;
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

     var cmt = document.getElementById("comments").value;
     con += "Comments: "+ cmt + "\n";

   return confirm("Confirm Entry:"+"\n"+con);

}
</script>

<br clear="all"/>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" class = "submitbutton pure-button wide" value="Submit" name="submit" onClick="return show_confirm();">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "pestReport.php?tab=soil:soil_scout:soil_pest:pest_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();">
</form>
</div>
</div>

<?php
if (isset($_POST['submit'])) {
   $fieldID = escapehtml($_POST['fieldID']);
   $pest = escapehtml($_POST['pest']);
   $average = escapehtml($_POST['average']);
   $comments = escapehtml($_POST['comments']);
   $crops = "";
   $numCrops = $_POST['numCropRows'];
   for ($i = 1; $i <= $numCrops; $i++) {
      $crp = escapehtml($_POST['crop'.$i]);
      if ($crops != "") {
         $crops .= "; ";
      }
      $crops .= $crp;
   }

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

   include $_SERVER['DOCUMENT_ROOT'].'/Soil/imageUpload.php';

   $sql="Insert into pestScout(sDate,fieldID,crops,pest,avgCount,comments,hours,filename) values ('".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID.
      "','".$crops."','".$pest."','".$average."','".$comments."', ".$totalHours.", ";
   if ($fname == "null") {
      $sql .= "null";
   } else {
      $sql .= ":filename";
   }
   $sql .= ")";
   try {
      $stmt = $dbcon->prepare($sql);
      if ($fname != "null") {
         $stmt->bindParam(':filename', $fname, PDO::PARAM_STR);
      }
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
}
?>

