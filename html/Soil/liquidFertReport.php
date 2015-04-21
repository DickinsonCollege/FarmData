<?php session_start(); ?>
<form name='form' method='GET' action='liquidFertTable.php'>
<input type="hidden" name="tab" 
  value="soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<h3 class="hi"> Liquid Fertilizer Report </h3>
<br clear="all">
<h1> Fertilizer Application Date Range </h1>
<label for="from">From:&nbsp;</label>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To: &nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';

echo "<br clear=\"all\">";
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<label for="material"> Material:</label>
<div class ="styled-select">
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
<input type="submit" class="submitbutton" name="submit" value="Submit">
</form>
