<?php session_start();?>
<link rel="stylesheet" href="/pure-release-0.5.0/pure-min.css">
<form class="pure-form pure-form-aligned" name='form' method='POST' action='packingTable.php?tab=admin:admin_delete:deletesales:delete_packing'>

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<div class="pure-controls">
	<h3>Packing Record</h3>
</div>
<br clear='all'>
<br clear='all'>

<?php
echo "<div class='pure-control-group'>";
echo "<label for='from'>From:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo"</div>";
echo "<br clear='all'>";
echo "<div class='pure-control-group'>";
echo "<label for='to'>To:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "</div>";
?>

<br clear='all'>
<div class='pure-control-group'>
<label>Crop/Product:&nbsp;</label>
<div class='styled-select'>
<select name='crop_product' id='crop_product' class='mobile-select'>
<option value='%'>All</option>
<?php
$sql = "SELECT crop FROM plant WHERE active=1 union SELECT product FROM product";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
	echo "<option value='".$row[0]."'>".$row[0]."</option>";
}
?>
</select></div>
</div>
<br clear='all'>
<div class='pure-control-group'>
<label>Target:&nbsp;</label>
<div class='styled-select'>
<select name='target' id='target' class='mobile-select'>
<option value='%'>All</option>
<?php
$sql = "SELECT targetName FROM targets";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
	echo "<option value='".$row[0]."'>".$row[0]."</option>";
}
?>
</select><div>
</div>
<br clear='all'>
<div class='pure-control-group'>
<label>Grade:&nbsp;</label>
<div class='styled-select'>
<select name='grade' id='grade' class='mobile-select'>
<option value='%'>All</option>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
</select></div>
</div>
<br clear='all'>
<div class='pure-control-group'>
<label>Bringback:&nbsp;</label>
<div class='styled-select'>
<select name='bringback' id='bringback' class='mobile-select'>
<option value='%'>All</option>
<option value='1'>Bringback</option>
<option value='0'>Non-Bringback</option>
</select></div>
</div>
<br clear='all'>
<div class='pure-controls'>
<input class='submitbutton' type='submit' name='submit' value='Submit'>
</div>
</form>
