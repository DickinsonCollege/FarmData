<?php
if ($_SESSION['mobile']) echo "<br clear='all'/>";
?>
<label for="fieldID"> Field ID:&nbsp; </label>
<div id="fieldID23" class="styled-select">
<select id= "fieldID" name="fieldID" class='mobile-select'>
<option value="%" selected> All </option>
<?php
$result = 0;
if ($active=='active'){
	$result = mysql_query("SELECT distinct fieldID from field_GH where active=1");
} else {
	$result = mysql_query("SELECT distinct fieldID from field_GH");
}
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<br clear="all">

