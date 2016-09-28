<?php
$units=array();
$sql = "select unit from extUnits";
$res = $dbcon->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
   $units[] = $row['unit'];
}
?>
<script type="text/javascript">
var numRows = 0;
var units = [];
<?php
for ($i = 0; $i < count($units); $i++) {
   echo 'units.push("'.$units[$i].'");';
}
?>

function addRow(cts) {
  numRows++;
  document.getElementById("numRows").value = numRows;
  var table = document.getElementById("configTable");
  var row = table.insertRow(-1);
  row.id="row" + numRows;
  numCols = cts.length;
  for (var i = 0; i < numCols; i++) {
     var cell = row.insertCell(i);
     var html = '<input onkeypress= "stopSubmitOnEnter(event);" name="cell' + numRows + 'at' + i +
         '" id="cell' + numRows + 'at' + i + '" value="' + cts[i] + '" class="textbox2 mobile-input" ' +
         'type="text" style="width:100%;"';
     if (i < 2 || cts[1] == units[i - 2]) {
        html += ' readonly';
     } 
     html += '>';
     cell.innerHTML = html;
  }
  if (numRows % 10 == 0 && numRows > 0) {
     row = table.insertRow(-1);
     html='<tr><td>Crop</td><td>Default Unit</td>';
     for (var i = 0; i < units.length; i++) {
        html+='<td>' + units[i] + '</td>';
     }
     row.innerHTML = html + "</tr>";
  }
}

function show_confirm() {
   for (i = 1; i <= numRows; i++) {
      for (j = 2; j < units.length + 2; j++) {
         var val = document.getElementById('cell' + i + "at" + j).value;
         if (val != "" && (!isFinite(val) || val <= 0)) {
            alert("Value in row: " + i + ", column: " + (j + 1) + " must be a positive number!");
            return false;
         }
      }
   }
   return true;
}
</script>
<input type="hidden" name="numRows" id="numRows" value=0>
<?php
$file = "unitsConv.txt";
echo '<input class="submitbutton" type="submit" name="add'.substr($file, 0, strlen($file) - 4).
   '" value="Submit All Data to Database"';
echo 'onclick = "return show_confirm();">';
echo '<br clear="all"/>';
echo '<br clear="all"/>';
$myfile = fopen($file, "r") or die("Unable to open file!");
$fc = fread($myfile,filesize($file));
fclose($myfile);
$arr = explode("\n", $fc);
if ($arr[count($arr) - 1] == "") {
   array_pop($arr);
}

echo '<table style="width:99%" id="configTable"> <tr>';
echo "<th>&nbsp;&nbsp;&nbsp;&nbsp;Crop&nbsp;&nbsp;&nbsp;&nbsp;</th>";
echo "<th>Default Unit</th>";
for ($i = 0; $i < count($units); $i++) {
   echo "<th>Conversion from ".$units[$i]." to Default Unit</th>";
}
echo '</tr></table>';
$convs = array();
for ($i = 1; $i < count($arr); $i++) {
  $row = explode(";", $arr[$i]);
  $convs[$row[0]]['FARMDATA_default'] = $row[1];
  $convs[$row[0]][$row[2]] = $row[3];
}
$sql = "select crop, units from plant";
$res = $dbcon->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
   $croprow=array($row['crop'], $row['units']); 
   for ($j = 0; $j < count($units); $j++) {
      if ($row['units'] == $units[$j]) {
         $croprow[] = 1;
      } else if ($row['units'] == $convs[$row['crop']]['FARMDATA_default'] &&
           isset($convs[$row['crop']][$units[$j]])) {
         $croprow[] = $convs[$row['crop']][$units[$j]];
      } else {
         $croprow[] = "";
      }
   }
   echo '<script type="text/javascript">addRow(eval('.json_encode($croprow).'));</script>';
}
?>
</form>
