<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3> Insect Scouting Input Form </h3>
<br clear="all"/>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_scout:soil_pest:pest_input">
<label for='date'>Date: &nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="fieldID"> Field ID: </label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID" class="mobile-select">
<option value = 0 selected disabled> FieldID</option>
<?php
$result=mysql_query("Select fieldID from field_GH where active=1");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="Pest"> Insect:&nbsp; </label>
<div class="styled-select">
<select name="pest" id="pest" class="mobile-select">
<option  value = 0 selected disabled > Insect </option>
<?php
$result=mysql_query("Select pestName from pest");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[pestName]\">$row1[pestName]</option>";
}
?>
</select>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>
<br clear="all"/>
<?php
echo '<table id="samples"';
if (!$_SESSION['mobile']) {
   echo ' style="width:10%"';
}
echo '>';
?>
<tr><th>Insects&nbsp;per&nbsp;Plant</th></tr>
</table>
<br clear="all"/>
<input type="button" id="addSample" name="addSample" class="submitbutton" 
 onClick="addSampleRow();"
value="Add Sample">
&nbsp;&nbsp;&nbsp;
<input type="button" id="removeSample" name="removeSample" 
class="submitbutton" onClick="removeSampleRow();"
value="Remove Sample">
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
  var table = document.getElementById("samples");
  numSamples++;
  document.getElementById("numSamples").value = numSamples;
  var row = table.insertRow(numSamples);
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
<br clear="all"/>
<label for="average">Average Insects Per Plant:&nbsp; </label>
<input  type="text" class ="textbox2 mobile-input" id="average" name="average">

<br clear="all"/>
<label for="average">Comments:&nbsp; </label>
<br clear="all"/>
<textarea name="comments" id="comments"
cols=30 rows=10>
</textarea>
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
     var cmt = document.getElementById("comments").value;
     con += "Comments: "+ cmt + "\n";

   return confirm("Confirm Entry:"+"\n"+con);

}
</script>

<br clear="all"/>
<br clear="all"/>
<input type="submit" class = "submitbutton" value="Submit" name="submit" onClick="return show_confirm();">
</form>

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
   $sql="Insert into pestScout(sDate,fieldID,crops,pest,avgCount,comments) values ('".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID.
      "','".$crops."','".$pest."','".$average."','".$comments."')";
   $result=mysql_query($sql);
   if (!$result) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>

<form method="POST" action = "pestReport.php?tab=soil:soil_scout:soil_pest:pest_report"><input type="submit" class="submitbutton" value = "View Table">
</form>
