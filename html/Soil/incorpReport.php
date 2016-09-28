<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="soil">
<form name='form' class='pure-form pure-form-aligned' method='get' action="incorpTable.php">
<input type="hidden" name="tab" value="soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report">
<center>
<h2 class="hi"> Incorporation Report</h2>
</center>
<div class="pure-control-group">
<label for="from">From:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<div class="pure-control-group">
<label for="to">To:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
</div>

<div class="pure-control-group">
<label> Name of Field: </label>
<select name='fieldID' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = $dbcon->query("SELECT distinct fieldID from coverSeed_master");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton pure-button wide" name="submit" value="Submit">
