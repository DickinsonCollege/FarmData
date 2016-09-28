<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' class='pure-form pure-form-aligned' method='GET' action="transTable.php">
<input type="hidden" name="tab" value="seeding:transplant:transplant_report">
<center>
<h2 class="hi"> Transplanted Crops Report </h2>
</center>
<fieldset>
<div class='pure-control-group'>
<?php
echo "<label for='from'>From:</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "</div>";
echo "<div class='pure-control-group'>";
echo "<label for='to'>To:</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "</div>";
?>
<div class='pure-control-group'>
<label for="crop">Crop:</label>
<select name="transferredCrop" class='mobile-select'>
<option value = "%"> All </option>
<?php
$result = $dbcon->query("SELECT distinct crop from transferred_to");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
<div class='pure-control-group'>
<label for="fieldID">Name of Field:</label>
<select name='fieldID' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = $dbcon->query("SELECT distinct fieldID from transferred_to");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>

<?php
if ($_SESSION['gens']) {
echo "<div class='pure-control-group'>";
echo '<label for="genSel">Succession #:</label> ';
echo '<select name="genSel">';
echo '<option value = "%" selected="selected"> All </option>';
$result = $dbcon->query("SELECT distinct gen from transferred_to order by gen");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
echo "\n<option value= \"$row1[gen]\">$row1[gen]</option>";
}
echo '</select>';
echo '</div>';
} else {
echo '<input type="hidden" name="genSel" value="%">';
}
echo "<br clear=\"all\">";
echo "<br clear=\"all\">";
echo'<input class="submitbutton pure-button wide" type="submit" name="submitTrans" value="Submit">';
?>
</fieldset>
</form>
