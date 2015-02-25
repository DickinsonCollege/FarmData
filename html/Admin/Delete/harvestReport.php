<?php session_start(); ?>
<form name='form' method='GET' action='harvestTable.php'>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h3 class="hi"> Select Harvest Records: </h3>
<br>
<input type="hidden" name = "tab" value = "admin:admin_delete:deleteharvest">
<?php
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to"> To:&nbsp</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<label for="crop"> Crop:&nbsp;</label>
<div class ="styled-select">
<select id = "crop" name="crop" class='mobile-select'>
<option selected value = "%"> All </option>
<?php
$result = mysql_query("SELECT distinct crop from harvested");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<br clear="all"/>
<script type="text/javascript">
function checkcrop() {
  var crp = document.getElementById("crop").value;
  if (crp == 0) {
     alert("Please select a crop!");
     return false;
  } else {
     return true;
  }
}
</script>
<input class="submitbutton" type="submit" name="submit" value="Submit" onclick= "return checkcrop();">
</form>

