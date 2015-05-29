<?php session_start();?>

<form name='form' class = 'pure-form pure-form-aligned' method='GET' action='packingTable.php'>
<input type="hidden" name="tab" value='admin:admin_sales:packing:packing_report'>

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<center><h2>Packing Report</h2></center>

<?php
echo "<div class = 'pure-control-group'>";
echo "<label for='from'>From:</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '</div>';

echo "<div class = 'pure-control-group'>";
echo "<label for='to'>To:</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '</div>';
?>

<div class = 'pure-control-group'>
<label>Crop/Product:</label>
<select name='crop_product' id='crop_product' class='mobile-select'>
<option value='%'>All</option>
<?php
$sql = "SELECT crop FROM (select crop from plant WHERE active=1 union SELECT product as crop FROM product where active=1) as tmp order by crop";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
	echo "<option value='".escapeHTML($row[0])."'>".$row[0]."</option>";
}
?>
</select></div>

<div class = 'pure-control-group'>
<label>Target:</label>
<select name='target' id='target' class='mobile-select'>
<option value='%'>All</option>
<?php
$sql = "SELECT targetName FROM targets";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
	echo "<option value='".escapeHTML($row[0])."'>".$row[0]."</option>";
}
?>
</select></div>

<div class = 'pure-control-group'>
<label>Grade:</label>
<select name='grade' id='grade' class='mobile-select'>
<option value='%'>All</option>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
</select></div>

<div class = 'pure-control-group'>
<label>Bringback:</label>
<select name='bringback' id='bringback' class='mobile-select'>
<option value='%'>All</option>
<option value='1'>Bringback</option>
<option value='0'>Non-Bringback</option>
</select></div>

<br clear='all'>
<input class='submitbutton pure-button wide' type='submit' name='submit' value='Submit'>
</form>
