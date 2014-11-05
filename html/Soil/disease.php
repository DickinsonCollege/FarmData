<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_scout:soil_disease:disease_input">
<h3> Disease Scouting Input Form </h3>
<br clear="all"/>
<label for='date'> Date: &nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<input type="hidden" name="hid" id="hid">
<label for="fieldID"> Field ID: &nbsp; </label>
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
<br clear="all">
<label for="Crop"> Crop Group:&nbsp; </label>
<div class="styled-select">
<select name="cropGroup" id="cropGroup" class="mobile-select">
<option value = 0 disabled selected="selected"> Crop Group</option>
<?php
$result=mysql_query("select cropGroup from cropGroupReference");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[cropGroup]\">$row1[cropGroup]</option>";
}
?>
</select>
</div>
<br clear="all"><br>
<!--
<label for="species">Add a new Species of Disease </label>

<br clear="all"/>
-->
<br clear="all" />
<table name="fieldTable" id="fieldTable">
   <tr><th>Disease</th><th>Infestation</th><th>Stage</th></tr>  
</table>
<script type="text/javascript">
var numRows=0;
function addRow(){
   numRows++;
   var table = document.getElementById("fieldTable");
   var row = table.insertRow(numRows);
   row.id = "row" + numRows;
   row.name = "row" + numRows;
   var cell0 = row.insertCell(0);                        
   cell0.innerHTML = '<div class="styled-select" id="speciesDiv'+numRows+'"> <select class="mobile-select" name="species'+numRows+'" id="species'+numRows+'">'+
                     '<option value = 0 selected disabled>Disease</option>'+
                     '<?php
                        $sql = 'Select diseaseName from disease';
                        $result = mysql_query($sql);            
                        while ($row1 =  mysql_fetch_array($result)){              
                           echo '<option value="'.$row1['diseaseName'].'">'.$row1['diseaseName'].'</option>';
                        }
                      ?>' + '</select></div>';
   var cell1 = row.insertCell(1);                               
   cell1.innerHTML = '<div class="styled-select" id="infestDiv'+numRows+'"><select class="mobile-select" name="infest'+numRows+'" id="infest'+numRows+'"><option value = 0 selected disabled> Infestation</option> <option>0</option> <option>1</option> <option>2</option> <option>3</option><option>4</option></select></div>';
   var cell2 = row.insertCell(2);   
	cell2.innerHTML = '<div class="styled-select" id="stageDiv'+numRows+'"><select class="mobile-select" name="stage'+numRows+'" id="stage'+numRows+'">'+
							'<option value = 0 selected disabled>Stage</option>'+
					'<?php
						$result = mysql_query("select stage from stage");
						while($row1 = mysql_fetch_array($result)){
							echo '<option value="'.$row1['stage'].'">'.$row1['stage'].'</option>';
						}	
					?>'+'</select></div>';
	console.log(numRows);
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

<div id="container"></div>
<br clear="all"/>

<input type="button" class = "genericbutton" 
  id="add" value="Add Species" onclick="addRow();"/>

<input type="button" id="remove" class = "genericbutton" value="Remove Species" onClick="removeRow();"/>
<br clear="all">
<br clear="all">
<label for="average">Comments:&nbsp; </label>
<br clear="all">
<textarea name="comments" id="comments"
cols=30 rows=10>
</textarea>
<script type="text/javascript">
function show_confirm() {
	var hid = document.getElementById("hid");
	hid.value = numRows;  
	var i = document.getElementById("month");
	var strUser3 = i.options[i.selectedIndex].text;
	var con="Scout Date: "+strUser3+"-";
	var i = document.getElementById("day");
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+strUser3+"-";
	var i = document.getElementById("year");
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+strUser3+"\n";
	var i = document.getElementById("fieldID");
	var strUser3 = i.value;
	if(checkEmpty(strUser3)) {
		alert("Please Select a FieldID");
		return false;
	}
	con+="Field ID: "+ strUser3+ "\n";
	var grp = document.getElementById("cropGroup").value;
	if(checkEmpty(grp)) {
		alert("Please Select a Crop Group");
		return false;
	}
	con+="Crop Group: "+ grp + "\n";
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
<br><br>
<input type="submit" class="submitbutton" name="submit" 
       value="Submit" onclick="return show_confirm();">



<?php
echo "</form>";
echo '<form method="POST" action = "diseaseReport.php?tab=soil:soil_scout:soil_disease:disease_report"><input type="submit" class="submitbutton" value = "View Table"></form>';
$var=$_POST['hid'];
if (isset($_POST['submit'])) {
   $success = true;
   $comments = escapehtml( $_POST['comments']);
   $fieldID = escapehtml( $_POST['fieldID']);
   $cropGroup = escapehtml( $_POST['cropGroup']);
   while ($var>0) {
      $species = escapehtml( $_POST['species'.$var]);
      $infest = escapehtml( $_POST['infest'.$var]);
      $stage = escapehtml( $_POST['stage'.$var]);
		$sql = "Insert into diseaseScout(sDate, fieldID, cropGroup, disease, infest, stage, comments) values ('".
         $_POST['year']."-".$_POST['month']."-".$_POST['day']."','".
         $fieldID."','".$cropGroup."','".$species."','".$infest."','".$stage.
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
