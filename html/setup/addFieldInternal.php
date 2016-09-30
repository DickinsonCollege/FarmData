<script type="text/javascript">

function updateSize(row) {
  var len = parseFloat(document.getElementById('length' + row).value);
  var beds = parseFloat(document.getElementById('beds' + row).value);
  var bspace = parseFloat(document.getElementById('bspace' + row).value);
  var size = len * beds /(43560 / (bspace / 12));
  if (isNaN(size)) { size = 0; }
  document.getElementById('size' + row).value = size.toFixed(2);
}

function updateBeds(row) {
  var len = parseFloat(document.getElementById('length' + row).value);
  var bspace = parseFloat(document.getElementById('bspace' + row).value);
  var size = parseFloat(document.getElementById('size' + row).value);
  var cons = 43560 / (bspace / 12);
  var beds = cons * size / len;
  if (isNaN(beds)) { beds  = 0; }
  document.getElementById('beds' + row).value = beds.toFixed(2);
}


function show_confirm() {
  var n = 0;
  var flds = [];
  for (i = 1; i <= numFields; i++) {
     var row = document.getElementById("row" + i);
     if (row != null && row.innerHTHML != "" && document.getElementById('fieldID' + i) != null) {
        var fld = document.getElementById('fieldID' + i).value;
        flds[n] = fld;
        n++;
        var len = document.getElementById('length' + i).value;
        var beds = document.getElementById('beds' + i).value;
        var bspace = document.getElementById('bspace' + i).value;
        if (checkEmpty(fld)) {
           alert("Enter a field identifier in row " + n + "!");
           return false;
        } else if (checkEmpty(len) || !isFinite(len) || len <= 0) {
           alert("Enter a valid length in row " + n + "!");
           return false;
        } else if (checkEmpty(beds) || !isFinite(beds) || beds <= 0) {
           alert("Enter a valid number of beds in row " + n + "!");
           return false;
        } else if (checkEmpty(bspace) || !isFinite(bspace) || bspace <= 0) {
           alert("Enter a valid bed spacing in row " + n + "!");
           return false;
        }
     }
  }
  if (n == 0) {
     alert("Enter at least one field!");
     return false;
  }
  flds.sort();
  for (i = 0; i < flds.length - 1; i++) {
    if (flds[i].toUpperCase() == flds[i + 1].toUpperCase()) {
       alert("Error: duplicate field name: " + flds[i].toUpperCase());
       return false;
    }
  }
  return true;
}

function deleteRow(row) {
   var row = document.getElementById("row" + row);
   row.innerHTML="";
}

var numFields = 0;
function addField() {
   var table = document.getElementById("fieldTable");
   numFields++;
   var numF = document.getElementById("numFields");
   numF.value=numFields;
   var row = table.insertRow(-1);
   row.id="row" + numFields;
   var cell = row.insertCell(0);
   cell.style.width="18%";
   cell.innerHTML = '<input onkeypress= "stopSubmitOnEnter(event)"; name="fieldID' + numFields + 
     '" id="fieldID' + numFields + '" type="text" class="textbox2 mobile-input" style="width:100%">';
   cell = row.insertCell(1);
   cell.style.width="18%";
   cell.innerHTML = '<input onkeypress= "stopSubmitOnEnter(event)"; name="length' + numFields + 
     '" id="length' + numFields + '" class="textbox2 mobile-input" type="text" onkeyup="updateSize(' +
     numFields + ');updateBeds(' + numFields + ');" style="width:100%">';
   cell = row.insertCell(2);
   cell.style.width="18%";
   cell.innerHTML = '<input onkeypress= "stopSubmitOnEnter(event)"; name="size' + numFields + 
     '" id="size' + numFields + '" class="textbox2 mobile-input" type="text" onkeyup = "updateBeds(' +
     numFields + ');" style="width:100%">';
   cell = row.insertCell(3);
   cell.style.width="18%";
   cell.innerHTML = '<input onkeypress= "stopSubmitOnEnter(event)"; name="beds' + numFields + 
     '" id="beds' + numFields + '" class="textbox2 mobile-input" type="text" onkeyup="updateSize(' +
     numFields + ');" style="width:100%">';
   cell = row.insertCell(4);
   cell.style.width="18%";
   cell.innerHTML = '<input onkeypress= "stopSubmitOnEnter(event)"; name="bspace' + numFields +
     '" id="bspace' + numFields + '" value = 60 onkeyup = "updateSize(' + numFields + 
     ');updateBeds(' + numFields + ');" class="textbox2 mobile-input" type="text" style="width:100%">';
   cell = row.insertCell(5);
   cell.innerHTML = "<input type='button' class='deletebutton' value='Delete'" +"onclick='deleteRow(" +
       numFields + ");'>";
  
}
</script>


<input class="submitbutton" type="submit" name="addfields" value="Submit All Fields to Database" 
   onclick = "return show_confirm();">
<br clear="all"/>
<br clear="all"/>
<input type="hidden" name="numFields" id="numFields" value=0>

<table style="width:99%" id="fieldTable">
<tr><th>FieldID</th><th>Length (feet)</th><th>Size (acres)</th><th>Number of Beds</th>
   <th>Bed Spacing on Center (inches)</th><th>Delete</th></tr>
</table>
<br clear="all"/>
<input type="button" class="submitbutton" id="newField" name="newField" value="New Field"
  onclick="addField();">
<script type="text/javascript">
window.onload=function(){addField();}
</script>
</form>
