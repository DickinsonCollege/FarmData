<?php session_start(); ?>
<form name='form' method='GET' class='pure-form pure-form-aligned' action='liquidFertTable.php'>
<input type="hidden" name="tab" 
  value="soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<center>
<h2 class="hi"> Liquid Fertilizer Report </h2>
</center>

<div class="pure-control-group">
<label for="from">From:</label>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "</div>";

echo '<div class="pure-control-group">';
echo '<label for="to"> To: </label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "</div>";

include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<div class="pure-control-group">
<label for="material"> Material:</label>
<select name="material" id="material" class="mobile-select">
<option value = "%" selected> All </option>
<?php
$result = mysql_query("SELECT fertilizerName from liquidFertilizerReference");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row[fertilizerName]\">$row[fertilizerName]</option>";
}
?>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton pure-button wide" name="submit" value="Submit">
</form>
