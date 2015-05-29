<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' id='test' class='pure-form pure-form-aligned'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_scout:soil_disease:disease_input">
<center>
<h2> Disease Scouting Input Form </h2>
</center>

<div class="pure-control-group">
<label for='date'> Date: </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<input type="hidden" name="hid" id="hid">

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

<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>

<br clear="all"/>
<center>
<table name="fieldTable" id="fieldTable"  
 class="pure-table pure-table-bordered">
<thead>
   <tr><th>Disease</th><th>Infestation</th><th>Stage</th></tr>  
</thead>
<tbody>
</tbody>
</table>
</center>
<script type="text/javascript">
var numRows=0;
function addRow(){
   numRows++;
   var table = document.getElementById("fieldTable").getElementsByTagName('tbody')[0];
   var row = table.insertRow(-1);
   row.id = "row" + numRows;
   row.name = "row" + numRows;
   var cell0 = row.insertCell(0);                        
   cell0.innerHTML = '<div class="styled-select" id="speciesDiv'+numRows+'"> <select class="wide" name="species'+numRows+'" id="species'+numRows+'">'+
                     '<option value = 0 selected disabled>Disease</option>'+
                     '<?php
                        $sql = 'Select diseaseName from disease';
                        $result = mysql_query($sql);            
                        while ($row1 =  mysql_fetch_array($result)){              
                           echo '<option value="'.$row1['diseaseName'].'">'.$row1['diseaseName'].'</option>';
                        }
                      ?>' + '</select></div>';
   var cell1 = row.insertCell(1);                               
   cell1.innerHTML = '<div class="styled-select" id="infestDiv'+numRows+'"><select class="wide" name="infest'+numRows+'" id="infest'+numRows+'"><option value = 0 selected disabled> Infestation</option> <option>0</option> <option>1</option> <option>2</option> <option>3</option><option>4</option></select></div>';
   var cell2 = row.insertCell(2);   
   cell2.innerHTML = '<div class="styled-select" id="stageDiv'+numRows+'"><select class="wide" name="stage'+numRows+'" id="stage'+numRows+'">'+
                     '<option value = 0 selected disabled>Stage</option>'+
               '<?php
                  $result = mysql_query("select stage from stage");
                  while($row1 = mysql_fetch_array($result)){
                     echo '<option value="'.$row1['stage'].'">'.$row1['stage'].'</option>';
                  }   
               ?>'+'</select></div>';
}
addRow();
function removeRow() {
   if (numRows > 0) {
      var species=document.getElementById('species' + numRows);
      //var speciesDiv=document.getElementById('speciesDiv' + numRows);
      //speciesDiv.removeChild(species);
      species.parentNode.removeChild(species);
      var infest=document.getElementById('infest' + numRows);
      //var infestDiv=document.getElementById('infestDiv' + numRows);
      //infestDiv.removeChild(infest);
      infest.parentNode.removeChild(infest);
      var stage=document.getElementById('stage' + numRows);
      //var stageDiv=document.getElementById('stageDiv' + numRows);
      //stageDiv.removeChild(stage);
      stage.parentNode.removeChild(stage);
      var table = document.getElementById("fieldTable");
      table.deleteRow(numRows);
      numRows--;
   }
}
</script>

<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" class = "submitbutton pure-button wide" 
  id="add" value="Add Species" onclick="addRow();"/>
</div>
<div class="pure-u-1-2">
<input type="button" id="remove" class = "submitbutton pure-button wide" value="Remove Species" onClick="removeRow();"/>
</div>
</div>
<br clear="all">
<br clear="all">
<div class="pure-control-group">
<label >Comments: </label>
<textarea name="comments" id="comments"
cols=30 rows=5>
</textarea>
</div>
<script type="text/javascript">
function show_confirm() {
   var hid = document.getElementById("hid");
   hid.value = numRows;  
   var mth = document.getElementById("month").value;
   var con="Scout Date: " + mth + "-";
   var dy = document.getElementById("day").value;
   con += dy + "-";
   var yr = document.getElementById("year").value;
   con += yr + "\n";
   var fld = document.getElementById("fieldID").value;
   if (checkEmpty(fld)) {
      alert("Please Select a FieldID");
      return false;
   }
   con += "Field ID: "+ fld + "\n";
   var crops = "";
   var numCrops = document.getElementById("numCropRows").value;
   for (var i = 1; i <= numCrops; i++) {
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
   con += "Crops: "+ crops + "\n";
   if (numRows == 0) {
      alert("No disease entered.");
      return false;
   }
   var a=1;
   var alldisease = [];
   while (a <= numRows) {
      var i = document.getElementById("species"+a);
      var disease = i.value;
      alldisease[a - 1] = disease;
      if (checkEmpty(disease)) {
         alert("Please Select a Disease in box: "+a);
         return false;
      }
      con=con+"\nDisease "+a+": "+disease+"\n";
      var i = document.getElementById("stage"+a);
      var stage = i.value;
      if (checkEmpty(stage)) {
         alert("Please Stage in box: "+a);
         return false;
      }
      con=con+"Stage "+a+": "+stage+"\n";
      var i = document.getElementById("infest"+a);
      var infest = i.value;
      if(checkEmpty(infest) && infest != 0) {
         alert("Please Select a Infestation level in box: "+a);
         return false;
      }
      con=con+"Infestation Level "+a+": "+infest+"\n";
      a++;
   }       
   alldisease.sort();
   for (i = 0; i < alldisease.length - 1; i++) {
      if (alldisease[i] == alldisease[i + 1]) {
         alert("Error: same disease entered twice!");
         return false;
      }
   }

   var i = document.getElementById("comments").value;
   var con=con+"Comments: "+ i+ "\n";

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>

<br clear="all"/>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" class="submitbutton pure-button wide" name="submit" 
       value="Submit" onclick="return show_confirm();">

<?php
echo "</form>";
echo "</div>";
echo '<div class="pure-u-1-2">';
echo '<form method="POST" action = "diseaseReport.php?tab=soil:soil_scout:soil_disease:disease_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table"></form>';
echo "</div>";
echo "</div>";
$var=$_POST['hid'];
if (isset($_POST['submit'])) {
   $success = true;
   $comments = escapehtml( $_POST['comments']);
   $fieldID = escapehtml( $_POST['fieldID']);
   $numCrops = $_POST['numCropRows'];
   $crops = "";
   for ($i = 1; $i <= $numCrops; $i++) {
      $crp = escapehtml( $_POST['crop'.$i]);
      if ($crops != "") {
         $crops .= "; ";
      }
      $crops .= $crp;
   }
   while ($var>0) {
      $species = escapehtml( $_POST['species'.$var]);
      $infest = escapehtml( $_POST['infest'.$var]);
      $stage = escapehtml( $_POST['stage'.$var]);
      $sql = "Insert into diseaseScout(sDate, fieldID, crops, disease, infest, stage, comments) values ('".
         $_POST['year']."-".$_POST['month']."-".$_POST['day']."','".
         $fieldID."','".$crops."','".$species."','".$infest."','".$stage.
         "','".$comments."')";
      $result=mysql_query($sql);

      if(!$result) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
         $success = false;
      }
      $var--;
   }
   if ($success) {
      echo "<script>showAlert('Entered Data Successfully!');</script>";
   }
}

?>
