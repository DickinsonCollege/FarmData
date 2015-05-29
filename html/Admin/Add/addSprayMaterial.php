<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<script type="text/javascript">
function show_confirm() {
	var spraymaterial = document.getElementById('spraymaterial').value;
	var trateunits = document.getElementById('trateunits').value;
	var tratemin = document.getElementById('tratemin').value;
	var tratemax = document.getElementById('tratemax').value;
	var tratedefault = document.getElementById('tratedefault').value;
	var brateunits = document.getElementById('brateunits').value;
	var bratemin = document.getElementById('bratemin').value;
	var bratemax = document.getElementById('bratemax').value;
	var bratedefault = document.getElementById('bratedefault').value;
	var rei = document.getElementById('rei').value;
	var ppe = document.getElementById('ppe').value;
	// var active = document.getElementById('active').value;

	if (spraymaterial ===  "") {
		alert("Enter a Spray Material Name!");
		return false;
	} else if (trateunits === "") {
		alert("Enter a Tractor Rate Unit!");
		return false;
	} else if (checkEmpty(tratemin) || !isFinite(tratemin) || tratemin <= 0) {
		alert("Enter a valid Minimum Tractor Rate!");
		return false;
	} else if (checkEmpty(tratemax) || !isFinite(tratemax) || tratemax <= 0) {
		alert("Enter a valid Maximum Tractor Rate!");
		return false;
	} else if (checkEmpty(tratedefault) || !isFinite(tratedefault) || tratedefault <= 0) {
		alert("Enter a valid Default Tractor Rate!");
		return false;
	} else if (brateunits === "") {
		alert("Enter a Backpack Rate Unit!");
		return false;
	} else if (checkEmpty(bratemin) || !isFinite(bratemin) || bratemin <= 0) {
		alert("Enter a valid Minimum Backpack Rate!");
		return false;
	} else if (checkEmpty(bratemax) || !isFinite(bratemax) || bratemax <= 0) {
		alert("Enter a valid Maximum Backpack Rate!");
		return false;
	} else if (checkEmpty(bratedefault) || !isFinite(bratedefault) || bratedefault <= 0) {
		alert("Enter a valid Default Backpack Rate!");
		return false;
	} else if (checkEmpty(rei)) {
		alert("Enter a valid Restricted Entry Interval!");
		return false;
	} else if (ppe === "") {
		alert("Enter Personal Protection Equipment!");
		return false;
	} else {
		return true;
	}
}
</script>

<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<center><h2 class="hi"><b>Add Spray Material</b></h2></center>

<div class = "pure-form pure-form-aligned">
<div class = "pure-control-group">
<label for="spraymaterial">Spray Material Name:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox3 mobile-input" type="text" name="spraymaterial" id="spraymaterial">
</div>

<div class = "pure-control-group">
<label for="trateunits">Tractor Rate Units:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox25 mobile-input" type="text" name="trateunits" id="trateunits">
</div>

<div class = "pure-control-group">
<label for="tratemin">Minimum Tractor Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox2 mobile-input" type="text" name="tratemin" id="tratemin">
</div>

<div class = "pure-control-group">
<label for="tratemax">Maximum Tractor Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox2 mobile-input" type="text" name="tratemax" id="tratemax">
</div>

<div class = "pure-control-group">
<label for="tratedefault">Default Tractor Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox2 mobile-input" type="text" name="tratedefault" id="tratedefault">
</div>

<div class = "pure-control-group">
<label for="brateunits">Backpack Rate Units:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox25 mobile-input" type="text" name="brateunits" id="brateunits">
</div>

<div class = "pure-control-group">
<label for="bratemin">Minimum Backpack Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox2 mobile-input" type="text" name="bratemin" id="bratemin">
</div>

<div class = "pure-control-group">
<label for="bratemax">Maximum Backpack Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox2 mobile-input" type="text" name="bratemax" id="bratemax">
</div>

<div class = "pure-control-group">
<label for="bratedefault">Default Backpack Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox2 mobile-input" type="text" name="bratedefault" id="bratedefault">
</div>

<div class = "pure-control-group">
<label for="rei">Restricted Entry Interval:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox2 mobile-input" type="text" name="rei" id="rei">
</div>

<div class = "pure-control-group">
<label for="ppe">Personal Protection Equipment:</label>
<input onkeypress='stopSubmitOnEnter(event)' class="textbox25 mobile-input" type="text" name="ppe" id="ppe">
</div>

<!--
<br clear="all">

<label for="active">Active Status:</label>
<div id="activediv" class="styled-select">
<select name="active" id="active" class='mobile-select'>
<option value="1" selected>Active</option>
<option value="0">Inactive</option>
<select>
</div>
-->
</div>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="add" id="add" value="Add" onclick="return show_confirm();">
<br clear="all"/>
<br clear="all"/>
<?php
if (isset($_POST['add'])) {
	$spraymaterial = escapehtml(strtoupper($_POST['spraymaterial']));
	$trateunits = escapehtml(strtoupper($_POST['trateunits']));
	$tratemin = escapehtml($_POST['tratemin']);
	$tratemax = escapehtml($_POST['tratemax']);
	$tratedefault = escapehtml($_POST['tratedefault']);
	$brateunits = escapehtml(strtoupper($_POST['brateunits']));
	$bratemin = escapehtml($_POST['bratemin']);
	$bratemax = escapehtml($_POST['bratemax']);
	$bratedefault = escapehtml($_POST['bratedefault']);
	$rei = escapehtml($_POST['rei']);
	$ppe = escapehtml($_POST['ppe']);
	// $active = escapehtml($_POST['active']);
	$active = 1;

   $sql="INSERT into tSprayMaterials
		(sprayMaterial, TRateUnits, TRateMin, TRateMax, TRateDefault, 
		BRateUnits, BRateMin, BRateMax, BRateDefault, REI_HRS, PPE, active)
		VALUES 
		('".$spraymaterial."', 
		'".$trateunits."', ".$tratemin.", ".$tratemax.", ".$tratedefault.", 
		'".$brateunits."', ".$bratemin.", ".$bratemax.", ".$bratedefault.", 
		'".$rei."', '".$ppe."', ".$active.")";
 
	$result = mysql_query($sql);
   if (!$result) {
      echo "<script>alert(\"Could not add spray material: Please try again!\\n".mysql_error()."\");</script> \n";
   } else {
      echo "<script>showAlert(\"Added spray material successfully!\");</script> \n";
   }
}

