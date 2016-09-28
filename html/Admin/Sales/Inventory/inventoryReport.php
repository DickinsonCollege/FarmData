<?php session_start();?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' class = 'pure-form pure-form-aligned' method='GET' action='inventoryTable.php?tab=admin:admin_sales:inventory'>
<input type='hidden' name='tab' value='admin:admin_sales:inventory'>

<center><h2>Inventory Report</h2></center>
<div class = 'pure-control-group'>
<label>Crop/Product:</label>
<select name='crop_product' id='crop_product' class='mobile-select'>
<option value='%'>All</option>
<?php
$sql = "SELECT crop FROM plant WHERE active=1 union ".
       "SELECT product as crop FROM product where active = 1 ORDER BY crop";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value='".$row['crop']."'>".$row['crop']."</option>";
}
?>
</select></div>

<div class = "pure-control-group">
<label>Grade:</label>
<select name='grade' id='grade' class='mobile-select'>
<option value='%'>All</option>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
</select></div>

<br clear="all"/>
<input class="submitbutton pure-button wide" value="Submit" type="submit" name="submit" >


</form>
