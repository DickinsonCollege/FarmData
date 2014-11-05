<script type="text/javascript">
var numRows = 0;
var numCols = 0;
var numeric = eval(<?php echo json_encode($numeric);?>);
var dropdown = eval(<?php echo json_encode($dropdown);?>);

function makeUnitSelect(i, opts, def) {
   var res='<div class="styled-select"><select name="cell' + numRows + 'at' + i +
      '" id="cell' + numRows + 'at' + i + '" style="width:100%">';
   for (var j = 0; j < opts.length; j++) {
      var un = opts[j];
      res += '<option value="' + un + '"';
      if (un == def || (un=='TRUE' && def == 1) || (un=='FALSE' && def == 0)) {
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
  var width = 90 / cts.length;
  for (var i = 0; i < cts.length; i++) {
     var cell = row.insertCell(i);
     cell.style.width=width + "%";
     if (dropdown[i].length > 0) {
        cell.innerHTML=makeUnitSelect(i, dropdown[i], cts[i]);
     } else {
        cell.innerHTML='<input onkeypress= "stopSubmitOnEnter(event);" name="cell' + numRows + 'at' + i +
         '" id="cell' + numRows + 'at' + i + '" value="' + cts[i] + '" class="textbox2 mobile-input" ' +
         'type="text" style="width:100%">';
     }
  }
  i = cts.length;
  var cell = row.insertCell(i);
  numCols = i;
  cell.innerHTML = "<input type='button' class='deletebutton' value='Delete'" +"onclick='deleteRow(" +
      numRows + ");' style='width:100%'>";
}

function addBlankRow(cols) {
  numRows++;
  document.getElementById("numRows").value = numRows;
  var table = document.getElementById("configTable");
  var row = table.insertRow(-1);
  row.id="row" + numRows;
  var width = 90 / cols;
  for (var i = 0; i < cols; i++) {
     var cell = row.insertCell(i);
     cell.style.width=width + "%";
     if (dropdown[i].length > 0) {
        cell.innerHTML=makeUnitSelect(i, dropdown[i], "");
     } else {
        cell.innerHTML='<input onkeypress= "stopSubmitOnEnter(event);" name="cell' + numRows + 'at' + i +
         '" id="cell' + numRows + 'at' + i + '" class="textbox2 mobile-input" ' +
         'type="text" style="width:100%">';
     }
  }
  i = cols;
  var cell = row.insertCell(i);
  numCols = cols;
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
         while (document.getElementById('cell' + i + "at" + j) != null) {
            var val = document.getElementById('cell' + i + "at" + j).value;
            if (numeric[j] == 2 && !isFinite(val)) {
               alert("Value in row: " + n + ", column: " + (j + 1) + " must be a number!");
               return false;
            } else if (numeric[j] == 1 && (!isFinite(val) || val <= 0)) {
               alert("Value in row: " + n + ", column: " + (j + 1) + " must be a positive number!");
               return false;
            } else if (checkEmpty(val) && val != 0) {
               alert("Enter value in row: " + n + ", column: " + (j + 1) + "!");
               return false;
            }
            j++;
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
echo '<input class="submitbutton" type="submit" name="add'.substr($file, 0, strlen($file) - 4).
   '" value="Submit All Data to Database"';
echo 'onclick = "return show_confirm();">';
echo '<br clear="all"/>';
echo '<br clear="all"/>';
// $file = "test.txt";
$myfile = fopen($file, "r") or die("Unable to open file!");
$fc = fread($myfile,filesize($file));
fclose($myfile);
$arr = explode("\n", $fc);
if ($arr[count($arr) - 1] == "") {
   array_pop($arr);
}
$head = explode(";", $arr[0]);
echo '<table style="width:99%" id="configTable"> <tr>';
for ($i = 0; $i < count($head); $i++) {
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
