<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' class='pure-form pure-form-aligned' method='GET' action='gh_table.php'>
<input type="hidden" name="tab" value="seeding:flats:flats_report">
<center>
<h2> Tray Seeding Report</h2>
</center>
<fieldset>
<div class="pure-control-group">
<?php
echo "<label for='from'>From:</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "</div>";
echo '<div class="pure-control-group">';
echo "<label for='to'>To:</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "</div>";
?>
<div class="pure-control-group">
<label for="crop">Crop:</label>
<select name='crop' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = mysql_query("SELECT distinct  crop from gh_seeding");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
<?php
if ($_SESSION['gens']) {
   echo '<div class="pure-control-group">';
   echo '<label for="genSel">Succession #:</label> ';
   echo '<select name="genSel" class="mobile-select">';
   echo '<option value = "%" selected="selected"> All </option>';
   $result = mysql_query("SELECT distinct gen from gh_seeding order by gen");
   while ($row1 =  mysql_fetch_array($result)){
      echo "\n<option value= \"$row1[gen]\">$row1[gen]</option>";
   }
   echo '</select>';
   echo '</div>';
} else {
   echo '<input type="hidden" name="genSel" value="%">';
}
?>
</fieldset>
<br clear="all"/>
<br clear="all"/>
<input class='submitbutton pure-button wide' type="submit" name="submit" value="Submit">
