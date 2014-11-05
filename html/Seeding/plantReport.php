<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' method='POST' action='dir_table.php?tab=seeding:direct:direct_report'>
<h3 class="hi">Direct Seeding Report </h3>
<br clear="all"/>
<label for='from'>From:&nbsp;</label>
<?php
if ($_SESSION['mobile']) echo "<br clear='all'/>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php'; 
echo "<br clear=\"all\">";
echo "<label for='to'>To:&nbsp;</label>";
if ($_SESSION['mobile']) echo "<br clear='all'/>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo "<br clear=\"all\">";
echo'<label for="crop"><b>Crop:</b>&nbsp;</label>';
?>
<div class="styled-select">
<select name='crop' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = mysql_query("SELECT distinct  crop from dir_planted");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<label for="fieldID"><b>Field ID:</b>&nbsp;</label>
<div class="styled-select">
<select name='fieldID' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = mysql_query("SELECT distinct fieldID from dir_planted");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<br clear="all"/>
<input class='submitbutton' type="submit" name="submit" value="Submit">
