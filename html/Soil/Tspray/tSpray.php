<?php session_start(); ?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];

?>
<form name='form' class='pure-form pure-form-aligned' method='POST'>
<center>
<h2>Tractor Spray Input</h2>
</center>

<div class="pure-control-group">
<label for="date">Date:</label>
<?php include $_SERVER['DOCUMENT_ROOT'].'/date.php'?>
</div>

<div class="pure-control-group">
<label for="status">Status:</label>
<select class="mobile-select" id="status" name="status" onchange="updateHeader();">
<option value=1 selected>Completed</option>
<option value=0>Queued</option>
</select>
</div>

<br clear="all">
<br clear="all">
<table name="fieldTable" id="fieldTable" class="pure-table pure-table-bordered">
<thead><tr>
	<th><center>Field</center></th>
	<th><center>Num Beds Sprayed</center></th>
	<th><center>Acreage Sprayed</center></th>
	<th><center>Selected Crop</center></th>
</tr></thead>
<tbody></tbody>

</table>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" value="Add Field" class="submitbutton pure-button wide"  name="Add Field Spray" onclick="addRow()"/>
</div>
<div class="pure-u-1-2">
<input type="button" value="Remove Field" class="submitbutton pure-button wide"  name="Remove Field Spray" onclick="removeRow()"/>
</div>
</div>
<br clear="all"/>
<br clear="all"/>
<table name="materialTable" id="materialTable" class="pure-table pure-table-bordered">
<thead><tr>
	<th>Material Sprayed</th>
	<th>Rate (in units per acre)</th>
	<th>Unit</th>
	<th>Suggested Total Material</th>
	<th>Actual Total Material</th>
	<th>Personal Protective Equipment</th>
	<th>Restricted Entry Interval (Hours)</th>
</tr></thead>
<tbody></tbody>
</table>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" value="Add Material" class="submitbutton pure-button wide" name="Add Material Spray" onclick="addRowMat()"/>
</div>
<div class="pure-u-1-2">
<input type="button" value="Remove Material" class="submitbutton pure-button wide" name="Delete Material Spray" onclick="removeRowMat()"/>
</div>
</div>
<br clear="all"/>
<br clear="all"/>
<table class="pure-table pure-table-bordered">
<thead><tr>
	<th>Water (Gallons) Used Per Acre</th>
	<th>Total Gallons of Water Used </th>

</tr></thead>
<tr><td><center><input class='textbox4 mobile-input single_table' type="text" name="waterPerAcre" id="waterPerAcre" value=
<?php
if ($farm == 'wahlst_spiralpath') {
   echo 72;
} else {
   echo 0;
}
?>
  onkeyup="calculateWater();"></center></td>
<td><center><input type="text" class='textbox4 mobile-input single_table' name="totalWater" id="totalWater" value=0></center></td></tr>
</table>


<br clear = "all">
<br clear = "all">
<div class="pure-control-group">
<label id="reasonlabel">Reason for Spray & Comments:</label>
<textarea name="textarea" rows=5 cols=30></textarea>
</div>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" value = 'Submit' class='submitbutton pure-button wide' name="submit" onclick="return show_confirm();  ">
<input type = 'hidden' name = 'numCropRows' id = 'numCropRows'>

<?php
// pass values back through on post
echo '<input type="hidden" name = "numField" id="numField">';
echo '<input type="hidden" name = "numMaterial" id="numMaterial" >';
echo "</form>";
echo '</div>';
echo '<div class="pure-u-1-2">';
echo '<form method="POST" action = "reportChooseDate.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table"></form>';
echo '</div>';
echo '</div>';
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
$numCropRows = $_POST['numCropRows'];
echo '<br>';

$numMaterial = escapehtml($_POST['numMaterial']);
$sqlM="INSERT INTO tSprayMaster(sprayDate,noField,noMaterial,waterPerAcre, "
   ."comment, user, complete, initials) VALUES ('"
   .$_POST['year']."-".$_POST['month']."-".$_POST['day']."' , ".$numField." , ".
   $numMaterial." , ".$waterPerAcre." , '".$comSanitized.
   "' , '".$username. "', ".$_POST['status'].", '')";
$rusultM=mysql_query($sqlM);
echo mysql_error();
$currentID= mysql_insert_id();

$fieldInd=1;
$crop_array = JSON_decode($numCropRows);
while($fieldInd<= $_POST['numField']){
   $field = escapehtml($_POST['field'.$fieldInd]);
   $bed = escapehtml($_POST['maxBed2'.$fieldInd]);
$crops = "";
for ($i = 1; $i <= $crop_array[$fieldInd]; $i++) {
   if ($crops != "") {
      $crops .= "; ";
   }
   $crops .= escapehtml($_POST['crop_'.$fieldInd.'_'.$i]);
}
   $sqlF="INSERT INTO tSprayField VALUES(".$currentID." , '". $field."' , ".$bed.",'".$crops."')";

   mysql_query($sqlF);
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

?>
<body id="soil">
</html>
