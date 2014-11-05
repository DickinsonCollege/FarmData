<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' method='POST' action='gh_table.php?tab=seeding:flats:flats_report'>
<h3> Flats Seeding Report</h3>
<br clear="all"/>
<?php
echo "<label for='from'>From:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
echo "<label for='to'>To:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "<br clear=\"all\">";
echo'<label for="crop">Crop:&nbsp;</label>';
?>
<div class="styled-select">
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
<br clear="all"/>
<br clear="all"/>
<input class='submitbutton' type="submit" name="submit" value="Submit">
