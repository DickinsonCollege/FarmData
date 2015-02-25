<?php session_start(); ?>
<form name='form' method='GET' action='weedTable.php'>
<input type="hidden" name="tab" value="admin:admin_delete:deletesoil:deletescout:deleteweedscout">
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h3 class="hi"> Select Weed Scout Records: </h3>
<br>
<?php
echo '<label for="from">From:&nbsp;</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo '<label for="to"> To:&nbsp</label> ';
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<label for="species"> Species:&nbsp;</label>
<div class ="styled-select">
<select id = "species" name="species" class='mobile-select'>
<option selected value = "%"> All </option>
<?php
$result = mysql_query("SELECT distinct weed from weedScout");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[weed]\">$row1[weed]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>

<script type="text/javascript">
function checkcrop() {
  var crp = document.getElementById("weed").value;
  if (crp == 0) {
     alert("Please select a weed species!");
     return false;
  } else if(fieldID == 0) {
     alert("Please select a fieldID!"); 
     return false;
  } else {
     return true;
  }
}
</script>
<br clear = "all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit" onclick= "return checkcrop();">
</form>
