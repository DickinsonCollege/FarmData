<script type="text/javascript">
var numRows = 0;
var numeric = [0, 0, 1, 0];
var units = [];
<?php
$sql = "select unit from extUnits";
$res = $dbcon->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
   echo 'units.push("'.$row['unit'].'");';
}
if (isset($_POST['invoicey'])) {
   echo 'var invoice = true;';
   echo 'var numCols = 4;';
} else {
   echo 'var invoice = false;';
   echo 'var numCols = 2;';
}
?>
function makeUnitSelect(i, def) {
  var res='<div class="styled-select"><select name="cell' + numRows + 'at' + i +
     '" id="cell' + numRows + 'at' + i + '" style="width:100%">';
  for (var j = 0; j < units.length; j++) {
      var un = units[j];
      res += '<option value="' + un + '"';
      if (un == def) {
         res += ' selected';
      }
      res += '>' + un + '</option>';
  }
  res += '</select></div>';
  return res;
}
function addRow(cts) {
  numRows++;
  document.getElementById("numRows").value = numRows;
  var table = document.getElementById("configTable");
  var row = table.insertRow(-1);
  row.id="row" + numRows;
  var width = 90 / numCols;
  for (var i = 0; i < numCols; i++) {
     var cell = row.insertCell(i);
     cell.style.width=width + "%";
     if (i == 1 || i == 3) {
        cell.innerHTML=makeUnitSelect(i, cts[i]);
     } else {
        cell.innerHTML='<input onkeypress= "stopSubmitOnEnter(event);" name="cell' + numRows + 'at' + i +
         '" id="cell' + numRows + 'at' + i + '" value="' + cts[i] + '" class="textbox2 mobile-input" ' +
         'type="text" style="width:100%">';
     }
  }
  i = numCols;
  var cell = row.insertCell(i);
  cell.innerHTML = "<input type='button' class='deletebutton' value='Delete'" +"onclick='deleteRow(" +
      numRows + ");' style='width:100%'>";
}

function addBlankRow() {
  numRows++;
  document.getElementById("numRows").value = numRows;
  var table = document.getElementById("configTable");
  var row = table.insertRow(-1);
  row.id="row" + numRows;
  var width = 90 / numCols;
  for (var i = 0; i < numCols; i++) {
     var cell = row.insertCell(i);
     cell.style.width=width + "%";
     if (i == 1 || i == 3) {
        cell.innerHTML=makeUnitSelect(i, "");
     } else {
        cell.innerHTML='<input onkeypress= "stopSubmitOnEnter(event);" name="cell' + numRows + 'at' + i +
         '" id="cell' + numRows + 'at' + i + '" class="textbox2 mobile-input" ' +
         'type="text" style="width:100%">';
     }
  }
  i = numCols;
  var cell = row.insertCell(i);
  cell.innerHTML = "<input type='button' class='deletebutton' value='Delete'" +"onclick='deleteRow(" +
      numRows + ");' style='width:100%'>";
}

function deleteRow(row) {
   var row = document.getElementById("row" + row);
   row.innerHTML="";
}

function show_confirm() {
   var n = 0;
   for (i = 1; i <= numRows; i++) {
      var row = document.getElementById("row" + i);
      if (row != null && row.innerHTHML != "" && document.getElementById('cell' + i + "at0") != null) {
         n++;
         j = 0;
         for (var j = 0; j < numCols; j++) {
            var val = document.getElementById('cell' + i + "at" + j).value;
            if (checkEmpty(val)) {
               alert("Enter value in row: " + n + ", column: " + (j + 1) + "!");
               return false;
            } else if (numeric[j] == 1 && (!isFinite(val) || val <= 0)) {
               alert("Value in row: " + n + ", column: " + (j + 1) + " must be a positive number!");
               return false;
            }
            j++;
         }
         if (!invoice) {
            var form = document.getElementById("configTable");
            var input = document.createElement("input");
            var nm = 'cell' + i + "at2";
            input.setAttribute("type", "hidden");
            input.setAttribute("name", nm);
            input.setAttribute("id", nm);
            input.setAttribute("value", 1);
            form.appendChild(input);
            input = document.createElement("input");
            nm = 'cell' + i + "at3";
            input.setAttribute("type", "hidden");
            input.setAttribute("name", nm);
            input.setAttribute("id", nm);
            input.setAttribute("value", document.getElementById('cell' + i + "at1").value);
            form.appendChild(input);
         }
      }
   }
   if (n <= 0) {
      alert("Enter at least one row!");
      return false;
   }
   return true;
}
</script>
<input type="hidden" name="numRows" id="numRows" value=0>
<?php
if (isset($_POST['invoicey'])) {
  echo '<input type="hidden" name="invoice" value=1>';
  $cols = 4;
} else {
  echo '<input type="hidden" name="invoice" value=0>';
  $cols = 2;
}
$file = "plant.txt";
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
$head = explode(";", $arr[0]);
echo '<table style="width:99%" id="configTable"> <tr>';
for ($i = 0; $i < $cols; $i++) {
   echo "<th>".$head[$i]."</th>";
}
echo "<th>".Delete."</th>";
echo '</tr></table>';
for ($i = 1; $i < count($arr); $i++) {
   echo '<script type="text/javascript">addRow(eval('.json_encode(explode(";", $arr[$i])).'));</script>';
}
echo '<br clear="all"/>';
echo '<input type="button" class="submitbutton" id="newRow" name="newRow" value="New Row"';
echo ' onclick="addBlankRow('.count($head).');">';
?>
</form>
