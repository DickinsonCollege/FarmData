<div class="pure-control-group" id="fieldID23">
<label for="fieldID">Name of Field:</label>
<select id= "fieldID" name="fieldID" class='mobile-select'>
<option value="%" selected> All </option>
<?php
$result = 0;
$result = $dbcon->query("SELECT distinct fieldID from field_GH where active=1");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>

