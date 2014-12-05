<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' method='POST' action="orderTable.php?tab=seeding:ordert:ordert_report">
<h3 class="hi"> Seed Order Report </h3>
<br clear="all"/>
<label for='year'>Year:&nbsp;</label>
<div class='styled-select'>
<select name='year' id='year' class='mobile-select'>
<option value="%">All</option>
<?php
$curYear = strftime("%Y");
for ($i = $curYear - 10; $i <= $curYear + 1; $i++) {
   echo '<option value="'.$i.'"';
   if ($i == $curYear) {
      echo ' selected';
   }
   echo '>'.$i.'</option>';
}
?>
</select></div>
<br clear="all">
<label for="crop">Crop:&nbsp;</label>
<div class="styled-select">
<select name="crop" class='mobile-select'>
<option value = "%"> All </option>
<?php
$result = mysql_query("SELECT distinct crop from plant");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= '".$row1['crop']."'>".$row1['crop']."</option>";
}
?>
</select>
</div>
<br clear="all"/>
<label for="source">Source:&nbsp;</label>
<div class="styled-select">
<select name='source' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$result = mysql_query("SELECT distinct source from source order by source");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= '".$row1['source']."'>".$row1['source']."</option>";
}
?>
</select>
</div>

<br clear="all"/>
<label for="status">Order Status:&nbsp;</label>
<div class="styled-select">
<select name='status' class='mobile-select'>
<option value = "%" selected="selected"> All </option>
<?php
$statusArray = array('PENDING', 'ORDERED', 'ARRIVED');
for ($i = 0; $i < count($statusArray); $i++) {
  echo "\n<option value= '".$statusArray[$i]."'>".$statusArray[$i]."</option>";
}
?>
</select>
</div>

<br clear="all"/>
<label for="order">Order Result By:&nbsp;</label>
<div class="styled-select">
<select name='order' class='mobile-select'>
<option value = "crop" selected="selected"> Crop </option>
<option value = "organic"> Organic Status </option>
</select>
</div>

<br clear="all">
<br clear="all">
<input class="submitbutton" type="submit" name="submitOrder" value="Submit">
