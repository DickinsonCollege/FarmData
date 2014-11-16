<?php session_start();

include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Sales/convert.php';
$sql = "select crop from plant union select product as crop from product";
$res = mysql_query($sql);
echo mysql_error();
$crops = array();
while ($row = mysql_fetch_array($res)) {
   array_push($crops, $row['crop']);
}
$units = array();
$sql = "select crop from plant where active=1";
$res = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($res)) {
  $sql2 = "select unit from units where crop='".$row['crop']."'";
  $ures = mysql_query($sql2);
  echo mysql_error();
  $unit = array();
  $i = 0;
  while ($urow = mysql_fetch_array($ures)) {
     $unit[$i] = $urow['unit'];
     if ($unit[$i] == 'POUND' && $i > 0) {
        $tem = $unit[0];
        $unit[0] = 'POUND';
        $unit[$i] = $tem;
     } 
     $i++;
  }
  $units[$row['crop']] = $unit;
}
$sql = "select product, unit from product";
$res = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($res)) {
   $units[$row['product']] = array($row['unit']);
}
?>
<script type="text/javascript">
var crops = eval(<?php echo json_encode($crops); ?>);
var units = eval(<?php echo json_encode($units); ?>);
var rowNum = 0;


/* change text color when clicked */
function changeTextColor(cell) {
   document.getElementById(cell).style.color="red";
}

/* change background color when pack exceeds harvest by more than 40% */
function changeColor(rowNum) {
   var avail = document.getElementById("avail" + rowNum).value;
   if (avail != "") {
      var row = document.getElementById("row" + rowNum);
      var total = document.getElementById("total" + rowNum).value;
      if (total > avail * 1.4) {
         row.style.backgroundColor = "#DD9999";
      } else {
         row.style.backgroundColor = "white";
      }
   }
}

function getUnit(row) {
   var crop = document.getElementById("crop" + row).value;
   var unit = document.getElementById("unit" + row);
   var unitarr = units[escapeHtml(crop)];
   var htm = "<div class='styled-select' id='unit" + row + "div' name='unit" + row+ "div'>" +
     "<select  style='width:100%' name='unit" + row + "' id='unit" + row + "'>";
   for (var i = 0; i < unitarr.length; i++) {
       htm += "<option value='" + unitarr[i] + "'>" + unitarr[i] + "</option>";
   }
   htm += "</select></div>";
   unit.innerHTML = htm;
   changeColor(row);
}

function getAmt(row) {
   var crop = document.getElementById("crop" + row).value;
   var unit = document.getElementById("unit" + row);
   var month = document.getElementById("month").value;
   var day = document.getElementById("day").value;
   var year = document.getElementById("year").value;
   var theDate = year+"-"+month+"-"+day;

   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "get_crop_amounts.php?crop=" +
       encodeURIComponent(crop) + "&harvestDate=" + theDate + "&unit="+encodeURIComponent(unit.value), false);
   xmlhttp.send();
   var crop_amts = eval(xmlhttp.responseText);
   var avail = document.getElementById("avail" + row);
   if (crop_amts[0] >= 0) {
      avail.value = crop_amts[0];
   } else {
      avail.value = "";
   }
   unit.selected = crop_amts[1];
   changeColor(row);
}

function addall(row){
   var sum = 0;
   for (var i = 0; i < targs.length; i++) {
      sum += Number(document.getElementById(targs[i].replace(/ /g, "_") + row).value);
   }
   document.getElementById('total' + row).value = sum;
   changeColor(row);
}

function deleteButton(rowNum) {
   var rowName = document.getElementById("row" + rowNum);
   rowName.innerHTML = "";
}

function addRow(crp, crpArr) {
   var table = document.getElementById("packTable");
   rowNum++;
   document.getElementById("rowNum").value = rowNum;
   var row = table.insertRow(-1);
   row.id = "row" + rowNum;

   cell = row.insertCell(0);
   var htm = "<div class='styled-select' id='crop" + rowNum + "div' name='crop" + rowNum+ "div'>" +
     "<select onchange='getUnit(" + rowNum + ");getAmt(" + rowNum + ");' style='width:100%' name='crop" + 
    rowNum + "' id='crop" + rowNum + "'>";
   for (var i = 0; i < crops.length; i++) {
       htm += "<option value='" + crops[i] + "'";
       if (crops[i] == crp) {
          htm += " selected ";
       }
       htm += ">" + crops[i] + "</option>";
   }
   htm += "</select></div>";
   cell.innerHTML = htm;
   cell = row.insertCell(1);
   var htm = "<div class='styled-select' id='grade" + rowNum + "div' name='grade" + rowNum+ "div'>" +
     "<select  style='width:100%' name='grade" + rowNum + "' id='grade" + rowNum + "'>";
   for (var i = 1; i < 5; i++) {
       htm += "<option value='" + i + "'>" + i + "</option>";
   }
   htm += "</select></div>";
   cell.innerHTML = htm;
   cell = row.insertCell(2);
   var htm = "<div class='styled-select' id='unit" + rowNum + "div' name='unit" + rowNum+ "div'>" +
     "<select  style='width:100%' name='unit" + rowNum + "' id='unit" + rowNum + 
     "' onchange='getAmt(" + rowNum + ");'>";
   if (crp != "") {
      //var unitarr = units[escapeHtml(crop)];
      var unitarr = units[crp];
      for (var i = 0; i < unitarr.length; i++) {
          htm += "<option value='" + unitarr[i] + "'";
          if (unitarr[i] == crpArr['FARMDATA_unit']) {
             htm += " selected ";
          }
          htm += ">" + unitarr[i] + "</option>";
      }
   }
   htm += "</select></div>";
   cell.innerHTML = htm;
   cell = row.insertCell(3);
   htm = "<div id='avail" + rowNum + "div' name='avail" + rowNum + "div'>" +
        " <input type='text' style='width:100%;'";
   if (crp != "") {
      if (crpArr['FARMDATA_yield']) {
         htm += " value = '" + crpArr['FARMDATA_yield'] + "' ";
      } else {
         htm += " value = '0' ";
      }
   }
   htm += " name='avail" + rowNum + "' id='avail" + rowNum + "'" +" class='textbox25' readonly></div>";
   cell.innerHTML = htm;

   for (var i = 0; i < targs.length; i++) {
      cell = row.insertCell(i + 4);
      var targ = targs[i].replace(/ /g, "_");
      htm = "<div id='" + targ + rowNum + "div' name='" + targ + rowNum + "div'>" +
        " <input type='text' style='width:100%;'";
      if (crp != "") {
         if (crpArr[targs[i]]) {
            htm += " value = '" + crpArr[targs[i]] + "' ";
         } else {
            htm += " value = '0' ";
         }
      }
      htm += " oninput='stopSubmitOnEnter(event);addall(" + rowNum + ");'" +
        "onclick=\"changeTextColor('" + targ + rowNum + "');\"" +
        " name='" + targ + rowNum + "' id='" + targ + rowNum + "'" +" class='textbox25'></div>";
      cell.innerHTML = htm;
   }
   var col = targs.length + 4;
   cell = row.insertCell(col);
   htm = "<div id='total" + rowNum + "div' name='total" + rowNum + "div'>" +
        " <input type='text' style='width:100%;'";
   if (crp != "") {
      if (crpArr['FARMDATA_total']) {
         htm += " value = '" + crpArr['FARMDATA_total'] + "' ";
      } else {
         htm += " value = '0' ";
      }
   }
   htm += " name='total" + rowNum + "' id='total" + rowNum + "'" +" class='textbox25' readonly></div>";
   cell.innerHTML = htm;
   changeColor(rowNum);
   col++;
   cell = row.insertCell(col);
   cell.innerHTML = "<input type='button' class='deletebutton' value='Delete'" +
      "onclick='deleteButton(" + rowNum + ");'>";

   var input = document.createElement("input");
   var nm = 'comments' + rowNum;
   input.setAttribute("type", "hidden");
   input.setAttribute("name", nm);
   input.setAttribute("id", nm);
   input.setAttribute("value", "");
   table.appendChild(input);

   if (crp == "") {
     getUnit(rowNum);
     getAmt(rowNum);
   } 
}

var alreadyPopulated = false;

/*************************************************************************
* Populates tables based on the selected harvest list                    *
*************************************************************************/
function populateEntries() {
   if (alreadyPopulated) {
      alert("Tables have already been populated from the Harvest List once -\n" + 
            "You can refresh the page if you'd like to start over");
      return;
   }

   var id = document.getElementById('selectList').value;
   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "populate_entries.php?id="+id, false);
   xmlhttp.send();
   // var harvest_list_array = eval(xmlhttp.responseText);
   var harvest_list_array = JSON.parse(xmlhttp.responseText);
   alreadyPopulated = true;
   for (var crp in harvest_list_array) {
      addRow(crp, harvest_list_array[crp]);
   }
}

function hasCrop(crop, grade, exists) {
   var tg = crop + grade;
   for (var i = 0; i < exists.length; i++) {
      if (exists[i] == tg) {
         alert("Rows " + (i + 1) + " and " + (exists.length + 1) + " are both for Grade " + grade +
            " " + crop + "!");
         return true;
      }
   }
   return false;
}

/*************************************************************************
* Check if all fields have data in them (except comments)                *
*************************************************************************/
function show_confirm() {

   var tooMuch = false;
   var row = 0;
   var exists = [];
   var table = document.getElementById("packTable");
   for (var j = 1; j <= rowNum; j++) {
      // Check that row exists
      if (document.getElementById("row" + j) != null && document.getElementById("row" + j).innerHTML != "") {
         var crop = document.getElementById('crop' + j).value;
         var grade = document.getElementById('grade' + j).value;
         if (hasCrop(crop, grade, exists)) {
            return false;
         }
         exists[row] = crop + grade;
         row++;
         for (var i = 0; i < targs.length; i++) {
            var targ = targs[i].replace(/ /g, "_");
            var val = document.getElementById(targ + j).value;
            if (!isFinite(val) || val < 0) {
                alert("Invalid value for " + targs[i] + " in row " + row + "!");
                return false;
            }
         }
         var avail = document.getElementById('avail' + j).value;
         var total = document.getElementById('total' + j).value;
         var input = document.getElementById('comments' + j);
         if (avail != "" && total > 1.4 * avail) {
            tooMuch = true;
            input.setAttribute("value", "Packing record quantity exceeds harvest by more than 40%.");
         }
      }
   }
   if (tooMuch) {
     return confirm("There are rows in this packing table in which the total is more than 40% greater than the harvested quantity.\n" + 
               "Are you sure that you want to insert these records?");
   } else {
      return confirm("Are you sure you want to input this data into the database?");
    }
}

</script>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<input type="hidden" name="rowNum" id="rowNum" value=0>
<h3> Packing Input Table </h3>
<br clear='all'/>
<label for="selectListDiv"> Harvest List Date:&nbsp; </label>
<div id="selectListDiv" class="styled-select">
<select id="selectList" name="selectList" class="mobile-select" >
<?php
$sql = "SELECT id, harDate FROM harvestList WHERE harDate > SUBDATE(CURDATE(), 30) order by harDate desc";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row['id']."'>".$row['harDate']."</option>";
}
?>
</select>
</div>
<br clear='all'/>
<input type="button" class="largesubmitbutton" id="populateFromHarvestList" name="populateFromHarvestList" value="Populate Entries From Harvest List" onclick="populateEntries();">
<br clear='all'/>
<br clear='all'>
<label>Packing Date:&nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<label>&nbsp;&nbsp;&nbsp;Bring Back?:&nbsp;</label>
<input type='checkbox' id='bringBack' name='bringBack' class='largecheckbox'>
<br clear='all'>
<br clear="all"/>
<table id="packTable">
<thead> 
<tr><th>Crop/Prod</th><th>Grade</th><th>&nbsp;&nbsp;Unit&nbsp;&nbsp;</th><th>Avail</th>
<?php
for ($i = 0; $i < count($targs); $i++) {
   echo "<th>".$targs[$i]."</th>";
}
?>
<th>Total</th><th>Delete</th></tr>
</thead>
</table>
<br clear="all"/>
<input type="button" class = "largeSubmitButton" value = "Add Row" onclick="addRow('', '');">
<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton" id="insertRows" name="insertRows" 
 value="Submit Data to Database" onclick="return show_confirm();">
</form>

<?php
if (isset($_POST['insertRows'])) {
   $rowNum = $_POST['rowNum'];
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $date = $year.'-'.$month.'-'.$day;
   $result = 1;
   for ($i = 1; $i <= $rowNum; $i++) {
      if (isset($_POST['crop'.$i])) {
         $crop = escapehtml($_POST['crop'.$i]);
         $unit = escapehtml($_POST['unit'.$i]);
         $comments = escapehtml($_POST['comments'.$i]);
         $grade = $_POST['grade'.$i];
         $bring = 0;
         if (!empty($_POST['bringBack'])) {
            $bring = 1; 
         }
         for ($j = 0; $j < count($targs); $j++) {
            $targ = $targs[$j];
            $targr = str_replace(" ", "_", $targ);
            $val = $_POST[$targr.$i];
            if ($val > 0) {
               $sql = "insert into pack(packDate, crop_product, grade, amount, unit, comments, bringBack,".
                  " Target) values('".$date."', '".$crop."', ".$grade.", ".$val.", '".$unit."', '".
                  $comments."', ".$bring.", '".$targ."')";
               $result = $result && mysql_query($sql);
            }
         }
      }
   }
   if($result){
      echo "<script>alert(\"Entered data successfully!\");</script> \n";
   } else {
     echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error().
        "\");</script>\n";
   }
}
?>

<!--
<h1 style="font-size:52px; margin-top:-30px;"> Packing Input Table </h1>

<input type="button" class="largesubmitbutton" id="insertRows" name="insertRows" value="Submit Data to Database" onclick="insertAllRows();">
<br clear='all'/>
<br clear='all'/>
<label for="selectListDiv"> Harvest List Date:&nbsp; </label>
<div id="selectListDiv" class="styled-select">
<select id="selectList" name="selectList" class="mobile-select" >
<?php
$sql = "SELECT id, harDate FROM harvestList WHERE harDate > SUBDATE(CURDATE(), 60) order by harDate desc";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row['id']."'>".$row['harDate']."</option>";
}
?>
</select>
</div>
<br clear='all'/>
<input type="button" class="largesubmitbutton" id="populateFromHarvestList" name="populateFromHarvestList" value="Populate Entries From Harvest List" onclick="populateEntries();">
<br clear='all'/>
<br clear='all'/>

<table id="cropInterfaceTable">
<caption>Create New Crop Row</caption>
<tr>
<th style="width:33.3%;">Crop Name</th>
<th style="width:33.3%;">Harvest Date</th>
<th style="width:33.3%;"></th>
</tr>
<tr>
<td>
<div id="selectCropDiv" class="styled-select">
<select id="selectCrop" name="selectCrop" class="mobile-select" style="width:100%;" onchange="getHarvestDates()">
<?php
$sql = "SELECT crop FROM plant WHERE active=1";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row[0]."'>".$row[0]."</option>";
}
?>
</select>
</div>
</td>
<td>
<div id="selectHarvestDateDiv" class="styled-select">
<select id="selectHarvestDate" name="selectHarvestDate" class="mobile-select" style="width:100%;">
</select>
</div>
</td>
<td>
<input type="button" class="submitbutton" id="createNewTable" name="createNewTable" style="width:100%;" value="Create" onclick="createNewTable('cropTable');">
</td>
</tr>
</table>

<table id="productInterfaceTable">
<caption>Create New Product Row</caption>
<tr>
<th style="width:50%;">Product Name</th>
<th style="width:50%;"></th>
</tr>
<tr>
<td>
<div id="selectProductDiv" class="styled-select">
<select id="selectProduct" name="selectProduct" class="mobile-select" style="width:100%;">
<?php
$sql = "SELECT product FROM product";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row[0]."'>".$row[0]."</option>";
}
?>
</select>
</div>
</td>
<td>
<input type="button" class="submitbutton" id="createNewTable" name="createNewTable" style="width:100%;" value="Create" onclick="createNewTable('productTable');">
</td>
</tr>
</table>
<br clear='all'>
<br clear='all'>

<div id="PackingTableHeader" style="display:none;">
<label>Packing Date:&nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<label>&nbsp;&nbsp;&nbsp;Bring Back?:&nbsp;</label>
<input type='checkbox' id='bringBack' class='largecheckbox'>
<br clear='all'>
<br clear='all'>
</div>

<script type="text/javascript">

   //global arrays
   // Actual Amount Harvested
   var totalTable = [];
   // Number of Rows in Table
   var numrowsTable = [];
   // Name of Crop
   var cropprodnameTable = [];
   // Unit Type
   var unittypeTable = [];
   // Crop + Harvest Date tracker (prevent duplicate Crop + Harvest Dates)
   var tableCrop = []; 
   // product tracker (prevent duplicates)
   var tableProduct = [];
   // Type of table (cropTable)
   var tabletypeTable = [];

window.onload = function() {
   getHarvestDates();
}

var alreadyPopulated = false;
var numTables = 0;
var tableSize = 8;

var fields_array = ["packDate", "crop_product", "grade", "target", "amount", "unit", "comments", "bringBack"];
var table_array = ["Row", "Grade", "Target", "Amount", "Unit", "Comments"];
var numericalFields = ["grade", "amount"];
// var targets_array = ["CSA", "Dining Services", "Market", "Other"];

/*************************************************************************
* Creates a new table                                                    *
*************************************************************************/
function createNewTable(tableName) {
   if (numTables == 0) {
      var header = document.getElementById("PackingTableHeader");
      header.style.display = "inline";
   }   

   xmlhttp = new XMLHttpRequest();

   if (tableName === "cropTable") {
      var crop = document.getElementById("selectCrop").value;
      var ecrop = escapeHtml(crop);
   
      var harvestDate = document.getElementById("selectHarvestDate").value;

      // Checks if Table already exists
      if (!checkIfCropTableExists(ecrop, harvestDate)) return;
   
      xmlhttp.open("GET", "get_crop_amounts.php?crop=" +
          encodeURIComponent(crop) + "&harvestDate=" + harvestDate, false);
      xmlhttp.send();
   } else {
      var product = document.getElementById("selectProduct").value;
      var eproduct = escapeHtml(product);

      // Check if Table already exists
      if (!checkIfProductTableExists(eproduct)) return;

      xmlhttp.open("GET", "get_product_amounts.php?product=" + 
         encodeURIComponent(product), false);
      xmlhttp.send();
   }

   numTables++;

   // Create new table
   var crop_product_amounts_array = eval(xmlhttp.responseText);
   var tbl = document.createElement("table");
   tbl.id = "table" + numTables;
   tbl.style.width = "99%";
   tbl.style.marginBottom = "30px";

   // Create Caption
   var caption = tbl.createCaption();
   if (tableName === "cropTable") {
      caption.innerHTML = createCropTableCaption(numTables, crop_product_amounts_array[0], crop_product_amounts_array[1], crop, harvestDate);
   } else if (tableName === "productTable") {
      caption.innerHTML = createProductTableCaption(numTables, crop_product_amounts_array[0], crop_product_amounts_array[1], product);
      crop_product_amounts_array[0] = 0;
   }
   
   // Create Header
   var header = tbl.createTHead();
   createHeader(header);

   // Create Body
   var numRows = 0;
   for (var i = 0; i < targs.length; i++) {
      numRows++;
      var row = tbl.insertRow(-1);
      row.style.backgroundColor = "white";
      row.id = "row" + numRows + "table" + numTables;
      
      // Row Number
      var cell = row.insertCell(0);
      cell.innerHTML = numRows;

      // Grade
      var cell = row.insertCell(1);
      cell.innerHTML = createGradeInput("1", numTables, numRows);

      // Target
      var cell = row.insertCell(2);
      cell.innerHTML = createTargetInput(targs[i], numTables, numRows);

      // Amount
      var cell = row.insertCell(3);
      cell.innerHTML = createAmountInput(crop_product_amounts_array[0]/targs.length, numTables, numRows);

      // Unit
      var cell = row.insertCell(4);
      if (tableName === "cropTable") {
         cell.innerHTML = createUnitInput(crop_product_amounts_array[1], numTables, numRows, crop);
      } else if (tableName === "productTable") {
         cell.innerHTML = createUnitInput(crop_product_amounts_array[1], numTables, numRows, product);
      }

      // Comments
      var cell = row.insertCell(5);
      cell.innerHTML = createCommentsInput("", numTables, numRows);

      // Delete Button
      var cell = row.insertCell(6);
      cell.innerHTML = createDeleteButton(numTables, numRows);

      // Copy Button
      var cell = row.insertCell(7);
      cell.innerHTML = createCopyButton(numTables, numRows);
   }

   // Total Amount so far for Table
   createTotalSoFar(tbl, numTables, crop_product_amounts_array[1]);
   
   // Append table to body of document   
   document.body.appendChild(tbl);

   // Create Hidden Inputs
   if (tableName === "cropTable") {
      createCropHiddenInputs(numTables, numRows, crop_product_amounts_array[0], crop_product_amounts_array[1], ecrop, harvestDate);
   } else if (tableName === "productTable") {
      createProductHiddenInputs(numTables, numRows, crop_product_amounts_array[0], crop_product_amounts_array[1], eproduct);
   }

   calculateTotalSoFar(numTables);
}

/*****************************
* Creates Crop Table Caption *
*****************************/
function createCropTableCaption(tableNum, amount, unit, crop, harvestDate) {
   var HTMLString = "";
      
   // Amount Harvested
   HTMLString += "<input readonly type='text' style='float:left; width:25%; margin-top:-10px;' class='textbox25' value='Harvested: " + +parseFloat(amount).toFixed(2) + " " + unit + "(s)'>";
   // Suggested Range
   HTMLString += "<input readonly type='text' style='float:left; width:25%; margin-top:-10px;' class='textbox25' value='Suggested Range: " + 
   +((parseFloat(amount) - (parseFloat(amount * .4)))).toFixed(2) + "-" + 
   +((parseFloat(amount) + (parseFloat(amount * .4)))).toFixed(2) + "'>";

   // Crop Name + Harvest Date
    HTMLString += crop + "&nbsp;&nbsp;&mdash;&nbsp;&nbsp;" + harvestDate;

   // Delete Table Button
   HTMLString += "<input type='button' style='float:right; height:30px; width:10%; background-color:#FF0000; color:#FFFFFF;' value='Delete Table' onclick='deleteCropTable(" + tableNum + ", \"" + 
      escapeHtml(escapeHtml(crop)) + "\", \"" + harvestDate + "\");'>";

   return HTMLString;
}

/********************************
* Creates Product Table Caption *
********************************/
function createProductTableCaption(tableNum, amount, unit, product) {   
   var HTMLString = "";

   // Units Per Case
   HTMLString += "<input readonly type='text' style='float:left; width:25%; margin-top:-10px;' class='textbox25' value='Units Per Case: " + amount + " " + unit + "(s)'>";

   // Product Name
   HTMLString += product;

   // Delete Table Button
   HTMLString += "<input type='button' style='float:right; height:30px; width:10%; background-color:#FF0000; color:#FFFFFF' value='Delete Table' onclick='deleteProductTable(" + tableNum + ", \"" + 
     escapeHtml(escapeHtml(product)) + "\");'>";

   return HTMLString
}
/***************************
* Creates Header for Table *
***************************/
function createHeader(header) {
   header.style.backgroundColor = "wheat";
   var row = header.insertRow(0);

   // Data Fields
   for (var i = 0; i < table_array.length; i++) {
      var cell = row.insertCell(i);
      cell.innerHTML = table_array[i];

      if (table_array[i] === "Row") {
         cell.style.width = "5%";
      }
      if (table_array[i] === "Grade") {
         cell.style.width = "5%";
      }
      if (table_array[i] === "Comments") {
         cell.style.width = "30%";
      }
   }

   // Delete/Copy Buttons
   var cell = row.insertCell(table_array.length);
   cell.innerHTML = "Delete";
   cell.style.width = "5%";
   var cell = row.insertCell(table_array.length + 1);
   cell.innerHTML = "Copy";
   cell.style.width = "5%";
}

/********************************
* Creates Grade Input for table *
********************************/
function createGradeInput(data, tableNum, rowNum) {
   var HTMLString = "";
   HTMLString += "<div id='gradeTable" + tableNum + "Row" + rowNum + "Div' class='styled-select'>" + 
      "<select style='width:100%;'" + 
      "id='gradeTable" + tableNum + "Row" + rowNum + "'>" + 
      "<option value='" + data + "' selected>" + data +"</option>";
      for (var t = 1; t <= 4; t++) {
         if (data != t) {
            HTMLString += "<option value='" + t + "'>" + t + "</option>";
         }
      }
   HTMLString += "</select></div>";
   return HTMLString;
}

/*********************************
* Creates Target Input for table *
*********************************/
function createTargetInput(data, tableNum, rowNum) {
   var HTMLString = "";
   HTMLString += "<div id='targetTable" + tableNum + "Row" + rowNum + "Div' class='styled-select'>" + 
      "<select style='width:100%;'" + 
      "id='targetTable" + tableNum + "Row" + rowNum + "'>" + 
      "<option value='" + data + "' selected>" + data + "</option>";
      for (var t = 0; t < targs.length; t++) {
         if (data != targs[t]) {
            HTMLString += "<option value='" + targs[t] + "'>" + targs[t] + "</option>";
         }
      }
   HTMLString += "<option value='Loss'>Loss</option>";
   HTMLString += "</select></div>";
   return HTMLString;
}
}

/*********************************
* Creates Amount Input for table *
*********************************/
function createAmountInput(data, tableNum, rowNum) {
   var HTMLString = "";
   HTMLString += "<div id='amountTable" + tableNum + "Row" + rowNum + "Div'>" + 
      "<input type='text' style='width:100%;'" + 
      "onChange='calculateTotalSoFar(" + tableNum + "); checkHarvestAmounts(" + tableNum + ")'" + 
      "id='amountTable" + tableNum + "Row" + rowNum + "'" + 
      "onclick=\"changeTextColor('amountTable" + tableNum + "Row" + rowNum + "');\"" +
      "class='textbox25' value='" + +parseFloat(data).toFixed(2) + "'></div>";
   return HTMLString;
}

/* fetch all units for a crop or product */
function getUnits(cropProd) {
  var ar = conversion[cropProd];
  var units = [];
  var i = 0;
  for (var un in ar) {
     units[i] = un;
     i++;
  }
  
  return units;
}

/*******************************
* Creates Unit Input for table *
*******************************/
function createUnitInput(data, tableNum, rowNum, cropProd) {
   /*
   var unitxml = new XMLHttpRequest();
   unitxml.open("GET", "get_units.php?cropProd=" + 
      encodeURIComponent(cropProd), false);
   unitxml.send();
   var units = eval(unitxml.responseText);
   */
   var units = getUnits(cropProd);

   var HTMLString = "";
   HTMLString += "<div id='unitTable" + tableNum + "Row" + rowNum + "Div' class='styled-select'>" + 
      "<select style='width:100%;'" + 
      "onChange='calculateTotalSoFar(" + tableNum + "); checkHarvestAmounts(" + tableNum + ")'" + 
      "id='unitTable" + tableNum + "Row" + rowNum + "'>" + 
      "<option value='" + data + "' selected>" + data + "</option>";
      for (var u = 0; u < units.length; u++) {
         if (units[u] != data) {
            HTMLString += "<option value='" + units[u] + "'>" + units[u] + "</option>";
         }
      }
   HTMLString += "</select></div>";
   return HTMLString;
}

/***********************************
* Creates Comments Input for table *
***********************************/
function createCommentsInput(data, tableNum, rowNum) {
   var HTMLString = "";
   HTMLString += "<div id='commentsTable" + tableNum + "Row" + rowNum + "Div'>" + 
      "<input type='text' style='width:100%;'" + 
      "id='commentsTable" + tableNum + "Row" + rowNum + "'" + 
      "class='textbox25' value='" + data + "'></div>";
   return HTMLString;
}

/**********************************
* Creates Delete Button for table *
**********************************/
function createDeleteButton(tableNum, rowNum) {
   var HTMLString = "";
   HTMLString += "<input type='button' class='deletebutton' value='Delete'" + 
      "onclick='deleteRow(" + tableNum + ", " + rowNum + ");'>";
   return HTMLString;
}

/********************************
* Creates Copy Button for table *
********************************/
function createCopyButton(tableNum, rowNum) {
   var HTMLString = "";
   HTMLString += "<input type='button' class='addbutton' value='Copy'" + 
      "onclick='copyRow(" + tableNum + ", " + rowNum + ");'>";
   return HTMLString;
}

/***************************************
* stores values for crop table *
***************************************/
function createCropHiddenInputs(tableNum, rowNum, amount, unit, crop, harvestDate) {
   // Actual Amount Harvested
   totalTable[tableNum] = amount;
   // Number of Rows in Table
   numrowsTable[tableNum] = rowNum;
   // Name of Crop
   cropprodnameTable[tableNum] = crop;
   // Unit Type
   unittypeTable[tableNum] = unit;
   // Crop + Harvest Date tracker (prevent duplicate Crop + Harvest Dates)
   tableCrop[crop + "Date" + harvestDate] = 1;
   // Type of table (cropTable)
   tabletypeTable[tableNum] = 'cropTable';
}

/******************************************
* Creates Hidden Inputs for product table *
******************************************/
function createProductHiddenInputs(tableNum, rowNum, amount, unit, product) {
   // Actual amount;
   totalTable[tableNum] = amount;
   // Number of Rows in Table
   numrowsTable[tableNum] = rowNum;
   // Name of Product
   cropprodnameTable[tableNum] = product;
   // Unit Type
   unittypeTable[tableNum] = unit;
   // Product tracker (prevent duplicate Products)
   tableProduct[product] = 1;
   // Type of table (productTable)
   tabletypeTable[tableNum] = 'productTable';
}

/*************************************
* Creates Total So Far Row for table *
*************************************/
function createTotalSoFar(tbl, tableNum, unit) {
   var row = tbl.insertRow(-1);
   row.style.backgroundColor = "#ADD8E6";
   row.id = "table" + numTables + "amountrow";

   row.insertCell(0);
   row.insertCell(1);

   var cell = row.insertCell(2);
   cell.innerHTML = "<b>Total Amount:</b>";
   var cell = row.insertCell(3);
   cell.innerHTML = "<input readonly type='text' id='currentamountTable" + tableNum + "' class='textbox25' style='width:100%' value='0'>";
   var cell = row.insertCell(4);
   cell.innerHTML = "<b>" + unit + "(s)</b>";

   row.insertCell(5);
   row.insertCell(6);
   row.insertCell(7);
}

/*************************************************************************
* Checks if a crop table with given crop and harvestDate has already     *
* been created                                                           *
*************************************************************************/
function checkIfCropTableExists(crop, harvestDate) {
   if (tableCrop[crop+"Date"+harvestDate] == 1) {
      alert("A Crop Packing Table with \nCrop: " + escapeescapeHtml(crop) + "\nDate: " + harvestDate + "\nalready exists!");
      return false;
   }
   return true;
}

/*************************************************************************
* Checks if a product table with given product has already been created  *
*************************************************************************/
function checkIfProductTableExists(product) {
   if (tableProduct[product] == 1) {
      alert("A Product Packing Table with \nProduct: " + escapeescapeHtml(product) + "\nalready exists!");
      return false;
   }
   return true;
}

/*************************************************************************
* Deletes Crop Table and related Hidden Inputs                           *
*************************************************************************/
function deleteCropTable(tableNum, crop, harvestDate) {
   delete totalTable[tableNum];
   delete numrowsTable[tableNum];
   delete cropprodnameTable[tableNum];
   delete tableCrop[crop + "Date" + harvestDate];
   delete tabletypeTable[tableNum];
   
   var tbl = document.getElementById("table" + tableNum);
   tbl.parentNode.removeChild(tbl);
}

/*************************************************************************
* Deletes Product Table and related Hidden Inputs                        *
*************************************************************************/
function deleteProductTable(tableNum, product) {
   delete totalTable[tableNum];
   delete numrowsTable[tableNum];
   delete cropprodnameTable[tableNum];
   delete tableProduct[product];
   delete tabletypeTable[tableNum];

   var tbl = document.getElementById("table" + tableNum);
   tbl.parentNode.removeChild(tbl);
}
/*************************************************************************
* Deletes a row from a table                                             *
*************************************************************************/
function deleteRow(tableNum, rowNum) {
   var row = document.getElementById("row" + rowNum + "table" + tableNum);
   row.parentNode.removeChild(row);

   calculateTotalSoFar(tableNum);
   checkHarvestAmounts(tableNum);
}

/*************************************************************************
* Copies a row in a table                                                *
*************************************************************************/
function copyRow(tableNum, rowNum) {
   var tbl = document.getElementById("table" + tableNum);
   numrowsTable[tableNum]++;
   var numRows = numrowsTable[tableNum];

   var target = document.getElementById("table" + tableNum + "amountrow");
   var row = document.createElement("tr");
   row.id = "row" + numRows + "table" + tableNum;
   row.style.backgroundColor = "white";
   target.parentNode.insertBefore(row, target);

   var crop = cropprodnameTable[tableNum];
   
   // Row Number
   var cell = row.insertCell(0);
   cell.innerHTML = numRows;   

   // Grade
   var cell = row.insertCell(1);
   var elem = document.getElementById("gradeTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createGradeInput(elem, tableNum, numRows);

   // Target
   var cell = row.insertCell(2);
   var elem = document.getElementById("targetTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createTargetInput(elem, tableNum, numRows);

   // Amount
   var cell = row.insertCell(3);
   var elem = document.getElementById("amountTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createAmountInput(elem, tableNum, numRows);

   // Unit
   var cell = row.insertCell(4);
   var elem = document.getElementById("unitTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createUnitInput(elem, tableNum, numRows, crop);

   // Comments
   var cell = row.insertCell(5);
   var elem = document.getElementById("commentsTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createCommentsInput(elem, tableNum, numRows);

   // Delete Button
   var cell = row.insertCell(6);
   cell.innerHTML = createDeleteButton(tableNum, numRows);

   // Copy Button
   var cell = row.insertCell(7);
   cell.innerHTML = createCopyButton(tableNum, numRows);

   calculateTotalSoFar(tableNum);
   checkHarvestAmounts(tableNum);
}

/*************************************************************************
* Populates the last 3 Harvest Dates when selecting Crop                 *
* in the Create New Crop Data Set table                                  *
*************************************************************************/
function getHarvestDates() {
   var a = document.getElementById("selectCrop");
   var crop = a.options[a.selectedIndex].value;

   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "get_harvest_dates.php?crop=" + 
      encodeURIComponent(crop), false);
   xmlhttp.send();

   var harvest_dates_array = eval(xmlhttp.responseText);
   var harvestDateDiv = document.getElementById("selectHarvestDateDiv");
   var HTMLString = "";
   
   HTMLString += "<select id='selectHarvestDate' name='selectHarvestDate' class='mobile-select' style='width:100%;'>";
   for (i = 0; i < harvest_dates_array.length; i++) {
      HTMLString += "<option value='" + harvest_dates_array[i] + "'>" + harvest_dates_array[i] + "</option>";
   }
   HTMLString += "</select>";

   harvestDateDiv.innerHTML = HTMLString;
}

/*************************************************************************
* Sets the table's background color to red if the total amount in the    *
* table isn't within 40% of it's projected value                         *
*************************************************************************/
function checkHarvestAmounts(tableNum) {
   // Only check amounts for crop table
   if (tabletypeTable[tableNum] != "cropTable") {
      return;
   }

   // Current amount calculated
   var a = document.getElementById("currentamountTable" + tableNum);
   var amountSoFar = a.value;

   // Actual harvested amount
   var totalAmount = totalTable[tableNum];

   var tbl = document.getElementById("table" + tableNum);
/*
   if ((parseFloat(totalAmount) + (parseFloat(totalAmount * .2))) < amountSoFar 
      || (parseFloat(totalAmount) - (parseFloat(totalAmount * .2))) > amountSoFar) {
*/
   if ((parseFloat(totalAmount) + (parseFloat(totalAmount * .4))) < amountSoFar) {
      for (var j = 1, row; row = tbl.rows[j]; j++) {
         row.style.backgroundColor = "#DD9999";
      }
      tbl.setAttribute("name", "alertAmount");
   } else {
      for (var j = 1, row; row = tbl.rows[j]; j++) {
         row.style.backgroundColor = "#FFFFFF";
      }
      tbl.setAttribute("name", "");
      document.getElementById("table" + tableNum + "amountrow").style.backgroundColor = "#ADD8E6";
   }
}

/*************************************************************************
* Calculates the total amount of all rows of table                       *
*************************************************************************/
function calculateTotalSoFar(tableNum) {
   var xmlhttp = new XMLHttpRequest();

   var numRows = numrowsTable[tableNum];

   var tableUnit = unittypeTable[tableNum];

   var crop_prod = cropprodnameTable[tableNum];

   var amountSoFar = 0;
   for (var i = 1; i <= numRows; i++) {
      var row = document.getElementById("row" + i + "table" + tableNum);
      if (row != null) {
         var amount = document.getElementById("amountTable" + tableNum + "Row" + i).value;
         var unit = escapeHtml(document.getElementById("unitTable" + tableNum + "Row" + i).value);

/*
         xmlhttp.open("GET", "convert_unit.php?cropProdName="+
            encodeURIComponent(crop_prod)+"&unit="+
            encodeURIComponent(unit), false);
         xmlhttp.send();
         var default_unit_conversion = eval(xmlhttp.responseText);

         xmlhttp.open("GET", "convert_unit.php?cropProdName="+
            encodeURIComponent(crop_prod) +"&unit="+
            encodeURIComponent(tableUnit), false);
         xmlhttp.send();
         var table_unit_conversion = eval(xmlhttp.responseText);

         amountSoFar += parseFloat((amount/default_unit_conversion[1]) * table_unit_conversion[1]);
*/
         amountSoFar += parseFloat((amount/conversion[crop_prod][unit]) * 
             conversion[crop_prod][tableUnit]);
      }
   }

   var amountBox = document.getElementById("currentamountTable" + tableNum);
   amountBox.value = +amountSoFar.toFixed(2);
}


/*************************************************************************
* Populates tables based on the selected harvest list                    *
*************************************************************************/
function populateEntries() {
   if (alreadyPopulated) {
      alert("Tables have already been populated from the Harvest List once -\n" + 
            "You can refresh the page if you'd like to start over");
      return;
   }

   var id = document.getElementById('selectList').value;
   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "populate_entries.php?id="+id, false);
   xmlhttp.send();
//console.log(xmlhttp.responseText);
   // var harvest_list_array = eval(xmlhttp.responseText);
   var harvest_list_array = JSON.parse(xmlhttp.responseText);

   if (numTables == 0) {
      var header = document.getElementById("PackingTableHeader");
      header.style.display = "inline";
   }

   for (var crop in harvest_list_array) {
      var vals = harvest_list_array[crop];
      // note: test is opposite of intuitive direction!
      if (checkIfCropTableExists(crop, vals['FARMDATA_date'])) {
         // Create a new table
         numTables++;
         var tbl = document.createElement("table");
         tbl.id = "table" + numTables;
         tbl.style.width = "99%";
         tbl.style.marginBottom = "30px";

         // Caption
         var caption = tbl.createCaption();
         caption.innerHTML = createCropTableCaption(numTables, vals['FARMDATA_yield'], 
            vals['FARMDATA_unit'], crop, vals['FARMDATA_date']);
      
         // Header
         var header = tbl.createTHead();
         createHeader(header);

         var numRows = 0;
         for (var i = 0; i < targs.length; i++) {
            if (targs[i] in vals) {
               var targ = targs[i];
               // Add a row to the table
               numRows++;
               var row = tbl.insertRow(-1);
               row.style.backgroundColor = "white";
               row.id = "row" + numRows + "table" + numTables;

               // Row Number
               var cell = row.insertCell(0);
               cell.innerHTML = numRows;

               // Grade
               var cell = row.insertCell(1);
               cell.innerHTML = createGradeInput("1", numTables, numRows);
      
               // Target
               var cell = row.insertCell(2);
               cell.innerHTML = createTargetInput(targ, numTables, numRows);
      
               // Amount
               var cell = row.insertCell(3);
               cell.innerHTML = createAmountInput(vals[targ], numTables, numRows);
            
               // Unit
               var cell = row.insertCell(4);
               cell.innerHTML = createUnitInput(vals['FARMDATA_unit'], numTables, numRows, crop);

               // Comments
               var cell = row.insertCell(5);
               cell.innerHTML = createCommentsInput("", numTables, numRows);

               // Delete Button
               var cell = row.insertCell(6);
               cell.innerHTML = createDeleteButton(numTables, numRows);
      
               // Copy Button
               var cell = row.insertCell(7);
               cell.innerHTML = createCopyButton(numTables, numRows);
            }
         }
         createTotalSoFar(tbl, numTables, vals['FARMDATA_unit']);
         document.body.appendChild(tbl);
         createCropHiddenInputs(numTables, numRows, vals['FARMDATA_yield'], vals['FARMDATA_unit'], crop,
            vals['FARMDATA_date']);
         calculateTotalSoFar(numTables);
         checkHarvestAmounts(numTables);
      }
   }
   alreadyPopulated=true;
}

/*************************************************************************
* Inserts all the tables into the database                               *
*************************************************************************/
function insertAllRows() {
   if (!show_confirm()) {
      return;
   }
   if (!alert_amounts()) {
      return;
   }

   var xmlhttp = new XMLHttpRequest();

   // Unit conversion
   var unit;
   var amount;

   // Create value for packDate
   var month = document.getElementById("month").value;
   var day = document.getElementById("day").value;
   var year = document.getElementById("year").value;
   var theDate = year+"-"+month+"-"+day;

   // Create value for bringBack;
   var bringBack = document.getElementById("bringBack");
   bringBack = bringBack.checked;
   var allvalues = [];
   var ind = 0;

   for (var i = 1; i <= numTables; i++) {

      // Check that table exists
      if (document.getElementById("table" + i) != null) {

         var numRows = numrowsTable[i];
         var crop_prod = cropprodnameTable[i];
         if (document.getElementById("table" + i).getAttribute("name") === "alertAmount") {
            var amountsMessage = true;
         } else {
            var amountsMessage = false;
         }

         for (var j = 1; j <= numRows; j++) {

            // Check that row exists
            if (document.getElementById("row" + j + "table" + i) != null) {

               var values = [];
               values[0] = theDate;
               values[1] = crop_prod;
               values[7] = bringBack;
   
               for (var k = 2; k < tableSize-1; k++) {
                  var elem = document.getElementById(fields_array[k] + "Table" + i + "Row" + j);
                  elem = elem.value;
                  values[k] = elem;

                  if (fields_array[k] === "unit") unit = elem;
                  if (fields_array[k] === "amount") amount = elem;
               }

               // Adds a warning in comments if packing record differed from harvest list record by more than 40%
               if (amountsMessage) {
                  values[6] += "\nPacking Record differs from Harvest Records by more than 40%";
               }

               // Performs unit conversion
/*
               xmlhttp.open("GET", "convert_unit.php?cropProdName="+
                  encodeURIComponent(crop_prod)+"&unit="+
                  encodeURIComponent(unit), false);
               xmlhttp.send();
               default_unit_conversion = eval(xmlhttp.responseText);
               values[fields_array.indexOf("amount")] = amount/default_unit_conversion[1];
               values[fields_array.indexOf("unit")] = default_unit_conversion[0];
*/
               values[fields_array.indexOf("amount")] = amount/conversion[crop_prod][unit];
               values[fields_array.indexOf("unit")] = default_unit[crop_prod];
               allvalues[ind] = values;
               ind++;
   
/*
               // Convert javascript arrays to PHP arrays
               values_array_json = JSON.stringify(values);
               fields_array_json = JSON.stringify(fields_array);
   
               // XMLHTTP request to insert row into pack
               xmlhttp.open("GET", "insert_row.php?values_array="+
                  encodeURIComponent(values_array_json)+
                  "&fields_array="+fields_array_json+"&tableSize="+
                  tableSize, false);
               xmlhttp.send();
               if (xmlhttp.responseText != "") {
                  alert("ERROR in MySQL inserting row into table:\n" + xmlhttp.responseText);
                  return false;
               }
*/
            }
         }
      }
   }
   values_array_json = JSON.stringify(allvalues);
   fields_array_json = JSON.stringify(fields_array);

   // XMLHTTP request to insert row into pack
   xmlhttp.open("GET", "insert_all_rows.php?values_array="+
      encodeURIComponent(values_array_json)+
      "&fields_array="+fields_array_json+"&tableSize="+
      tableSize, false);
   xmlhttp.send();
   if (xmlhttp.responseText != "") {
      alert("ERROR in MySQL inserting row into table:\n" + xmlhttp.responseText);
      return false;
   }
   alert("Successfully entered data into table!");
   location.reload(true);
}

/*************************************************************************
* If there are tables in the packing input form that don't match the     *
* harvest record amounts, alert the user and request confirmation        *
*************************************************************************/
function alert_amounts() {
   for (var i = 1; i <= numTables; i++) {

      // Check that table exists
      if (document.getElementById("table" + i) != null) {

         var tbl = document.getElementById("table" + i);
         if (tbl.getAttribute("name") === "alertAmount") {
            return confirm("There are tables in this Packing Record that do not match the harvested quantity within 40%\n" + 
               "Are you sure you would like to insert these records into the Packing Table?");
         }
      }
   }
   return true;
}

/*************************************************************************
* Check if all fields have data in them (except comments)                *
*************************************************************************/
function show_confirm() {
   for (var i = 1; i <= numTables; i++) {

      // Check that table exists
      if (document.getElementById("table" + i) != null) {

         var numRows = numrowsTable[i];
         var tableName = cropprodnameTable[i];

         for (var j = 1; j <= numRows; j++) {
   
            // Check that row exists
            if (document.getElementById("row" + j + "table" + i) != null) {
   
               for (var k = 2; k < tableSize-1; k++) {
                  var elem = document.getElementById(fields_array[k] + "Table" + i + "Row" + j);

                  if (fields_array[k] != "comments") {
                     if (checkEmpty(elem.value) || elem.value === "" || elem.value === "undefined" || elem.value === "null") {
                        alert("Check Table " + tableName + "\n" + 
                              "Row " + j + ": " + fields_array[k] + "\n" + 
                              "There is no value in the input field");
                        return false;
                     }
                  }

                  if (numericalFields.indexOf(fields_array[k]) > -1) {
                     if (isNaN(elem.value)) {
                        alert("Check Table " + tableName + "\n" + 
                              "Row " + j + ": " + fields_array[k] + "\n" + 
                              "Value must be a number");
                        return false;
                     }
                  }
               }
            }
         }
      }
   }
   return confirm("Are you sure you want to input this data into the database?");
}

</script>
-->
