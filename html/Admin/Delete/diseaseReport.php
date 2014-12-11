<?php session_start(); ?>
<form name='form' method='GET' action='diseaseTable.php?tab=admin:admin_delete:deletesoil:deletescout:deletediseasescout'>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletescout:deletediseasescout">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<h3 class="hi"> Disease Report </h3>
<br clear="all">
<h1> Disease Date Range </h1>
<label for="from">From:&nbsp;</label>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo '<label for="to"> To: &nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "<br clear=\"all\">";
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<label for="cropDiv"> Crop: &nbsp; </label>
<div id="cropDiv" class="styled-select">
<select id= "crop" name="crop" class='mobile-select'>
<option value="%" selected> All </option>
<?php
$result = mysql_query("SELECT crop from plant");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= '".$row['crop']."'>".$row['crop']."</option>";
}
?>
</select>
</div>

<br clear="all"/>
<label for="disease"> Disease:</label>
<div class ="styled-select" id="diseaseDiv">
<select name="disease" id="disease" class='mobile-select'>
<option value = "%" selected> All </option>
<?php
$result = mysql_query("SELECT diseaseName from disease");
while ($row =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row[diseaseName]\">$row[diseaseName]</option>";
}
?>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton" name="submit" value="Submit">
</form>
