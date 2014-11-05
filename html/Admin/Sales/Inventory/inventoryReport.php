<?php session_start();?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' method='GET' action='inventoryTable.php?tab=admin:admin_sales:inventory'>
<input type='hidden' name='tab' value='admin:admin_sales:inventory'>

<h3>Inventory Report</h3>

<br clear='all'>
<label>Crop/Product:&nbsp;</label>
<div class='styled-select'>
<select name='crop_product' id='crop_product' class='mobile-select'>
<option value='%'>All</option>
<?php
$sql = "SELECT crop FROM plant WHERE active=1 union SELECT product as crop FROM product ORDER BY crop";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row[0]."'>".$row[0]."</option>";
}
?>
</select></div>

<br clear='all'>
<label>Grade:&nbsp;</label>
<div class='styled-select'>
<select name='grade' id='grade' class='mobile-select'>
<option value='%'>All</option>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
</select></div>

<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" value="Submit" type="submit" name="submit" >


</form>
