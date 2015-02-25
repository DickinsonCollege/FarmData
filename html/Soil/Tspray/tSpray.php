<?php session_start(); ?>
<?php 
//include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];

?>
<form name='form' method='POST'>
<h3>Tractor Spray Input</h3>
<br>
<label for="date">Date:&nbsp;</label>
<?php include $_SERVER['DOCUMENT_ROOT'].'/date.php'?>
<br clear="all">
<label for="status">Status:&nbsp;</label>
<div class="styled-select" id="statusDiv"> <select class="mobile-select" id="status" name="status">
<option value=1 selected>Completed</option>
<option value=0>Queued</option>
</select>
</div>
<br clear="all">
<br clear="all">
<table name="fieldTable" id="fieldTable">
<tr>
	<th>Field</th>
	<th>Num Beds Sprayed</th>
	<th>Acreage Sprayed</th>
</tr>


</table>
<br clear="all"/>
<input type="button" value="Add Field" class="submitbutton"  name="Add Field Spray" onclick="addRow()"/>
&nbsp;&nbsp;&nbsp;
<input type="button" value="Remove Field" class="submitbutton"  name="Remove Field Spray" onclick="removeRow()"/>
<br clear="all"/>
<br clear="all"/>
<table name="materialTable" id="materialTable">
<tr>
	<th>Material Sprayed</th>
	<th>Rate (in units per acre)</th>
	<th>Unit</th>
	<th>Suggested Total Material</th>
	<th>Actual Total Material</th>
	<th>Personal&nbsp;Protective Equipment</th>
	<th>Restricted Entry Interval (Hours)</th>
</tr>
</table>
<br clear="all"/>
<input type="button" value="Add Material" class="submitbutton" name="Add Material Spray" onclick="addRowMat()"/>
&nbsp;&nbsp;&nbsp;
<input type="button" value="Remove Material" class="submitbutton" name="Delete Material Spray" onclick="removeRowMat()"/>
<br clear="all"/>
<br clear="all"/>
<table>
<tr>
	<th>Water (Gallons) Used Per Acre</th>
	<th>Total Gallons of Water Used </th>

</tr>
<tr><td><center><input class='textbox4 mobile-input single_table' type="text" name="waterPerAcre" id="waterPerAcre" value=
<?php
if ($farm == 'wahlst_spiralpath') {
   echo 90;
} else {
   echo 0;
}
?>
  onkeyup="calculateWater();"></center></td>
<td><center><input type="text" class='textbox4 mobile-input single_table' name="totalWater" id="totalWater" value=0></center></td></tr>
</table>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Soil/crop.php';
?>
<script type="text/javascript">
var table = document.getElementById("cropTable");
table.style = <?php 
  if ($_SESSION['mobile']) {
      echo '"width: 99%";';
  } else {
      echo '"width:1000px";';
  } ?>
</script>

<br clear="all"/>
<table>
<tr>
<th>
Reason For Spray & Comments</th></tr>

<tr><td><textarea style="width: 980px;" name="textarea" rows="4" cols="50"></textarea></td></tr>
</table>
<br clear="all"/>
<input type="submit" value = 'Submit' class='submitbutton' name="submit" onclick="return show_confirm();  ">
<?php
// pass values back through on post
echo '<input type="hidden" name = "numField" id="numField">';
echo '<input type="hidden" name = "numMaterial" id="numMaterial" >';
?>
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Soil/Tspray/functions.php';
?>
</form>


<?php
if(!empty($_POST['submit'])) {
$comSanitized=escapehtml($_POST['textarea']);
$waterPerAcre=escapehtml($_POST['waterPerAcre']);
$username=escapehtml($_SESSION['username']);
$numField = escapehtml($_POST['numField']);
$numCrops = $_POST['numCropRows'];
$crops = "";
for ($i = 1; $i <= $numCrops; $i++) {
   if ($crops != "") {
      $crops .= "; ";
   }
   $crops .= escapehtml($_POST['crop'.$i]);
}
$numMaterial = escapehtml($_POST['numMaterial']);
$sqlM="INSERT INTO tSprayMaster(sprayDate,noField,noMaterial,waterPerAcre,crops, "
   ."comment, user, complete, initials) VALUES ('"
   .$_POST['year']."-".$_POST['month']."-".$_POST['day']."' , ".$numField." , ".
   $numMaterial." , ".$waterPerAcre." , '".$crops."' , '".$comSanitized.
   "' , '".$username. "', ".$_POST['status'].", '')";
$rusultM=mysql_query($sqlM);
//echo $sqlM;
echo mysql_error();
$currentID= mysql_insert_id();

$fieldInd=1;
while($fieldInd<= $_POST['numField']){
   $field = escapehtml($_POST['field'.$fieldInd]);
   $bed = escapehtml($_POST['maxBed2'.$fieldInd]);
   $sqlF="INSERT INTO tSprayField VALUES(".$currentID." , '". $field."' , ".$bed.");";
   mysql_query($sqlF);
   //echo $sqlF;
	echo mysql_error();
   $fieldInd++;
}


$materialInd=1;

while($materialInd<= $_POST['numMaterial']){
   $material = escapehtml($_POST['material2'.$materialInd]);
   $rate = escapehtml($_POST['rate2'.$materialInd]);
   $total = escapehtml($_POST['actuarialTotal'.$materialInd]);
   $sqlW="INSERT INTO tSprayWater VALUES(".$currentID." , '". $material."', ".
      $rate." , ".$total."  );";
   mysql_query($sqlW);
   //echo $sqlW;
	echo mysql_error();
   $materialInd++;
}
}
if(!empty($_POST['submit'])) {
   echo "<script> showAlert('Entered Data Succesfully!'); </script>";
}

echo "</form>";
echo '<form method="POST" action = "reportChooseDate.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton" value = "View Table"></form>';
?>
<body id="soil">
</html>
