<?php session_start();

include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Sales/convert.php';

$sql = "select * from inventory";
$result = mysql_query($sql);
echo mysql_error();
$inventory = array();
$inventory_unit = array();
while ($row = mysql_fetch_array($result)) {
   $crop = $row['crop_product'];
   $amount = $row['amount'];
   $grade = $row['grade'];
   $unit = $row['unit'];
   $convsql = "SELECT conversion FROM units WHERE crop='".$crop."' AND unit='POUND'";
   $convresult = mysql_query($convsql);
   if (mysql_num_rows($convresult) > 0) {
      $convrow = mysql_fetch_array($convresult);
      $conversion = $convrow[0];
      $unit = 'POUND';
      $amount = $amount * $conversion;
   }
   $amount = number_format((float) $amount, 2, '.', '');
   $inventory[$crop][$grade] = $amount;
   $inventory_unit[$crop][$grade] = $unit;
}
?>

<script type="text/javascript">
var inventory = eval(<?php echo json_encode($inventory);?>);
var inventory_unit = eval(<?php echo json_encode($inventory_unit);?>);

function getInventoryAmount(cropProd, grade) {
  if (cropProd in inventory) {
      if (grade in inventory[cropProd]) {
         return inventory[cropProd][grade];
      }
  }
  return 0;
}

function getInventoryUnit(cropProd, grade) {
  if (cropProd in inventory_unit) {
      if (grade in inventory_unit[cropProd]) {
         return inventory_unit[cropProd][grade];
      }
  }
  return default_unit[cropProd];
}
</script>
<h1 style="font-size:52px; margin-top:-30px;"> Distribution Input Table </h1>

<input type="button" class="largesubmitbutton" id="insertRows" name="insertRows" value="Submit Data to Database" onclick="insertAllRows()">
<br clear="all"/>
<br clear="all"/>

<table id="populateEntriesTable">
<caption>Populate Entries From Packing Record</caption>
<tr>
<th style="width:50%;">Packing Date</th>
<th style="width:50%;"></th>
</tr>
<tr>
<td>
<div id="selectPackDateDiv" class="styled-select">
<select id="selectPackDate" name="selectPackDate" class="mobile-select" style="width:100%;">

<?php
$sql = "SELECT distinct packDate from pack where packDate > SUBDATE(CURDATE(), 10) order by packDate desc";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row['packDate']."'>".$row['packDate']."</option>";
}
/*
$sql = "SELECT AUTO_INCREMENT 
   FROM INFORMATION_SCHEMA.TABLES 
   WHERE TABLE_SCHEMA=DATABASE() 
   AND TABLE_NAME='pack'";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$lastPack = $row['AUTO_INCREMENT'];

$count = 0;
$lastDate = 0;

do {
   $sql = "SELECT packDate FROM pack WHERE id=".$lastPack;
   $result = mysql_query($sql);
   $row = mysql_fetch_array($result);
   if ($row != null) {
      if ($row['packDate'] != $lastDate) {
         echo "<option value='".$row['packDate']."'>".$row['packDate']."</option>";
         $count++;
         $lastDate = $row['packDate'];
      }
   }
   $lastPack--;
} while ($count < 3 && $lastPack > 0);
*/
?>
         
</select>
</div>
</td>
<td>
<input type="button" class="largesubmitbutton" id="populateFromPacking" name="populateFromPacking" value="Populate Entries From Packing Record" onclick="populateEntries();">
</td>
</tr>
</table>

<table id="packInterfaceTable">
<caption>Create New Crop/Product Data Set</caption>
<tr>
<th style="width:45%;">Crop/Product Name</th>
<th style="width:20%;">Grade</th>
<th style="width:35%;"></th>
</tr>
<tr>
<td>
<div id="selectCropProdDiv" class="styled-select">
<select id="selectCropProd" name="selectCropProd" class="mobile-select" style="width:100%;" onchange="getGrades();">
<?php
$sql = "SELECT crop FROM plant WHERE active=1 union SELECT product FROM product as crop ORDER BY crop";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   echo "<option value='".$row[0]."'>".$row[0]."</option>";
}
?>
</select>
</div>
</td>
<td>
<div id="selectGradeDiv" class="styled-select">
<select id="selectGrade" name="selectGrade" class="mobile-select" style="width:100%;">
</select>
</div>
</td>
<td>
<input type="button" class="submitbutton" id="createNewTable" name="createNewTable" style="width:100%;" value="Create" onclick="createNewTable();">
</td>
</tr>
</table>
<br clear='all'>
<br clear='all'>

<div id="DistributionTableHeader" style="display:none;">
<label>Distribution Date:&nbsp;</label>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear='all'>
<br clear='all'>
</div>

<script type="text/javascript">
// Actual Amount in Inventory/Packed
var totalTable = [];
//Number of Rows in Table
var numrowsTable = [];
// Name of Crop/Product
var cropprodnameTable = [];
// Grade of Crop/Product
var cropprodgradeTable = [];
// Unit Type
var unittypeTable = [];
// Crop/Product + Grade Tracker (prevent duplicate Crop/Product + Grade) 
var tableCropProd = [];

window.onload = function() {
   getGrades();
}

var alreadyPopulated = false;
var numTables = 0;
var tableSize = 7;

var fields_array = ["distDate", "crop_product", "grade", "target", "amount", "unit", "comments"];
var table_array = ["Row", "Target", "Amount", "Unit", "Comments"];
var numericalFields = ["grade", "amount"];
//var targets_array = ["CSA", "Dining Services", "Market", "Other"];

/*************************************************************************
* Creates a new table                                                    *
*************************************************************************/
function createNewTable() {
   // Return if the select Crop/Product has no Grade selections
   if (document.getElementById("selectGrade").options[0] == null) return;

   if (numTables == 0) {
      var header = document.getElementById("DistributionTableHeader");
      header.style.display = "inline";
   }
   
   xmlhttp = new XMLHttpRequest();

   var cropProd = document.getElementById("selectCropProd").value;
   var eCropProd = escapeHtml(cropProd);

   var grade = document.getElementById("selectGrade").value;

   // Check if Table already exists
   if (!checkIfTableExists(eCropProd, grade)) return;

/*
   xmlhttp.open("GET", "get_crop_product_amounts.php?cropProd=" + 
           encodeURIComponent(cropProd) + "&grade=" + grade, false);
   xmlhttp.send();
   var crop_product_amounts_array = eval(xmlhttp.responseText);
*/
   var crop_product_amounts_array = [getInventoryAmount(cropProd, grade), getInventoryUnit(cropProd, grade)]; 

   // Create new table
   numTables++;
   var tbl = document.createElement("table");
   tbl.id = "table" + numTables;
   tbl.style.width = "99%";
   tbl.style.marginBottom = "30px";

   // Create Caption
   var caption = tbl.createCaption();
   caption.innerHTML = createTableCaption(numTables, crop_product_amounts_array[0], crop_product_amounts_array[1], eCropProd, grade);

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

      // Target
      var cell = row.insertCell(1);
      cell.innerHTML = createTargetInput(targs[i], numTables, numRows);

      // Amount
      var cell = row.insertCell(2);
      cell.innerHTML = createAmountInput(0, numTables, numRows);

      // Unit
      var cell = row.insertCell(3);
      cell.innerHTML = createUnitInput(crop_product_amounts_array[1], numTables, numRows, eCropProd);

      // Comments
      var cell = row.insertCell(4);
      cell.innerHTML = createCommentsInput("", numTables, numRows);

      // Delete Button
      var cell = row.insertCell(5);
      cell.innerHTML = createDeleteButton(numTables, numRows);

      // Copy Button
      var cell = row.insertCell(6);
      cell.innerHTML = createCopyButton(numTables, numRows);
   }

   // Total Amount so far for Table
   createTotalSoFar(tbl, numTables, crop_product_amounts_array[1]);

   // Append table to body of document
   document.body.appendChild(tbl);

   // Create Hidden Inputs
   createHiddenInputs(numTables, numRows, crop_product_amounts_array[0], crop_product_amounts_array[1], eCropProd, grade);
console.log("crop: " + eCropProd);

   // Checks for Crops/Products with inventory values in the negatives
   checkInventoryAmounts(numTables);
}

/************************
* Creates Table Caption *
************************/
function createTableCaption(tableNum, amount, unit, cropProd, grade) {
   var HTMLString = "";

   // Amount Packed
   HTMLString += "<input readonly type='text' style='float:left; width:25%; margin-top:-10px' class='textbox25' value='Inventory: " + amount + " " + unit + "(s)'>";

   // Crop/Product Name + Grade
   HTMLString += cropProd + "&nbsp;&nbsp;&mdash;&nbsp;&nbsp;Grade: " + grade;

   // Delete Table Button
   HTMLString += "<input type='button' style='float:right; height:30px; width:10%; background-color:#FF0000; color:#FFFFFF;' value='Delete Table' onclick='deleteTable("
       + tableNum + ", \"" + escapeHtml(cropProd) + "\", \"" + grade + "\");'>";

   return HTMLString;
}

/***************************
* Creates Header for Table *
***************************/
function createHeader(header) {
   header.style.backgroundColor = "wheat";
   var row = header.insertRow(0);

   // Create Headers
   for (var i = 0; i < table_array.length; i++) {
      var cell = row.insertCell(i);
      cell.innerHTML = table_array[i];

      if (table_array[i] === "Row") {
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

/*********************************
* Creates Target Input for table *
*********************************/
function createTargetInput(data, tableNum, rowNum) {
   var HTMLString = "";

   HTMLString += "<div id='targetTable" + tableNum + "Row" + rowNum + "Div' class='styled-select'>" + 
      "<select style='width:100%;'" + 
      "id='targetTable" + tableNum + "Row" + rowNum + "'>" + 
      "<option value='" + data +"' selected>" + data + "</option>";
      for (var t = 0; t < targs.length; t++) {
         if (data != targs[t]) {
            HTMLString += "<option value='" + targs[t] + "'>" + targs[t] + "</option>";
         }
      }
   HTMLString += "<option value='Loss'>Loss</option>";
   HTMLString += "</select></div>";

   return HTMLString;
}

/*********************************
* Creates Amount Input for table *
*********************************/
function createAmountInput(data, tableNum, rowNum) {
   var HTMLString = "";

   HTMLString += "<div id='amountTable" + tableNum + "Row" + rowNum + "Div'>" + 
      "<input type='text' style='width:100%;'" + 
      "onChange='calculateTotalSoFar(" + tableNum + "); checkInventoryAmounts(" + tableNum + ")'" + 
      "id='amountTable" + tableNum + "Row" + rowNum + "'" + 
      "class='textbox25' value='" + data + "'></div>";

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
   unitxml.open("GET", "get_units.php?cropProd=" + encodeURIComponent(cropProd), false);
   unitxml.send();
   var units = eval(unitxml.responseText);
*/
   var units = getUnits(cropProd);
   var HTMLString = "";

   HTMLString += "<div id='unitTable" + tableNum + "Row" + rowNum + "Div' class='styled-select'>" + 
      "<select style='width:100%;'" + 
      "onChange='calculateTotalSoFar(" + tableNum + "); checkInventoryAmounts(" + tableNum + ")'" + 
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
/**********************************
* Creates Hidden Inputs for table *
**********************************/
function createHiddenInputs(tableNum, rowNum, amount, unit, cropProd, grade) {

   totalTable[tableNum] = amount;
   numrowsTable[tableNum] = rowNum;
   cropprodnameTable[tableNum] = cropProd;
   cropprodgradeTable[tableNum] = grade;
   unittypeTable[tableNum] = unit;
   tableCropProd[cropProd + "Grade" + grade] = 1;

}

/*************************************
* Creates Total So Far Row for table *
*************************************/
function createTotalSoFar(tbl, tableNum, unit) {
   var row = tbl.insertRow(-1);
   row.style.backgroundColor = "#ADD8E6";
   row.id = "table" + numTables + "amountrow";

   row.insertCell(0);

   var cell = row.insertCell(1);
   cell.innerHTML = "<b>Total Amount:</b>";
   var cell = row.insertCell(2);
   cell.innerHTML = "<input readonly type='text' id='currentamountTable" + tableNum + "' class='textbox25' style='width:100%' value='0'>";
   var cell = row.insertCell(3);
   cell.innerHTML = "<b>" + unit + "(s)</br>";

   row.insertCell(4);
   row.insertCell(5);
   row.insertCell(6);
}

/*************************************************************************
* Checks if table with given Crop/Product and Grade already exists       *
*************************************************************************/
function checkIfTableExists(cropProd, grade) {
/*
   var tbl = document.getElementById("tableCropProd" + cropProd + "Grade" + grade);
   if (tbl != null) {
*/
   if (tableCropProd[cropProd + "Grade" + grade] == 1) {
      alert("A Crop/Product Distribution Table with \nCrop/Product: " + escapeescapeHtml(cropProd) + "\nGrade: " + grade + "\nalready exists!");
      return false;
   }
   return true;
}

/*************************************************************************
* Deletes Table and related Hidden Inputs                                *
*************************************************************************/
function deleteTable(tableNum, cropProd, grade) {
   delete cropprodnameTable[tableNum];
   delete totalTable[tableNum];
   delete cropprodgradeTable[tableNum];
   delete unittypeTable[tableNum];
   delete tableCropProd[cropProd + "Grade" + grade];

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
   checkInventoryAmounts(tableNum);
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

   var cropProd = cropprodnameTable[tableNum];

   // Row Number
   var cell = row.insertCell(0);
   cell.innerHTML = numRows;

   // Target
   var cell = row.insertCell(1);
   var elem = document.getElementById("targetTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createTargetInput(elem, tableNum, numRows);

   // Amount
   var cell = row.insertCell(2);
   var elem = document.getElementById("amountTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createAmountInput(parseFloat(elem).toFixed(2), tableNum, numRows);

   // Unit
   var cell = row.insertCell(3);
   var elem = document.getElementById("unitTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createUnitInput(elem, tableNum, numRows, cropProd);

   // Comments
   var cell = row.insertCell(4);
   var elem = document.getElementById("commentsTable" + tableNum + "Row" + rowNum).value;
   cell.innerHTML = createCommentsInput(elem, tableNum, numRows);

   // Delete Button
   var cell = row.insertCell(5);
   cell.innerHTML = createDeleteButton(tableNum, numRows);

   // Copy Button
   var cell = row.insertCell(6);
   cell.innerHTML = createCopyButton(tableNum, numRows);

   calculateTotalSoFar(tableNum);
   checkInventoryAmounts(tableNum);
}

/*************************************************************************
* Populates available grade options when selecting Crop/Product          *
*************************************************************************/
function getGrades() {
   var a = document.getElementById("selectCropProd");
   var cropProd = a.options[a.selectedIndex].value;


/*
   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "get_grades.php?cropProd=" + encodeURIComponent(cropProd), false);
   xmlhttp.send();

   var grades_array = eval(xmlhttp.responseText);
*/
   var grades_array = [1, 2, 3, 4];
   var gradeDiv = document.getElementById("selectGradeDiv");
   var HTMLString = "";

   HTMLString += "<select id='selectGrade' name='selectGrade' class='mobile-select' style='width:100%;'>";
   for (i = 0; i < grades_array.length; i++) {
      HTMLString += "<option value='" + grades_array[i] + "'>" + grades_array[i] + "</option>";
   }
   HTMLString += "</select>";

   gradeDiv.innerHTML = HTMLString;
}

/*************************************************************************
* Sets the table's background color to red if the total amount in the    *
* table exceeds the corresponding pack/inventory amount                  *
*************************************************************************/
function checkInventoryAmounts(tableNum) {
   var amountSoFar = document.getElementById("currentamountTable" + tableNum).value;

   var totalAmount = totalTable[tableNum];

   var tbl = document.getElementById("table" + tableNum);
   // 40% fudge factor for distribution
   if (1.4 * parseFloat(totalAmount) < parseFloat(amountSoFar)) {
      for (var j = 1, row; row = tbl.rows[j]; j++) {
         row.style.backgroundColor = "#DD9999";
      }
      tbl.setAttribute("name", "alertAmount");
   } else {
      for (var j = 1, row; row = tbl.rows[j]; j++) {
         row.style.backgroundColor = "#FFFFFF";
      }
      tbl.setAttribute("name", "");
      document.getElementById("table" + tableNum + "amountrow").style.backgroundColor = "ADD8E6";
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
         var unit = document.getElementById("unitTable" + tableNum + "Row" + i).value;

/*
         xmlhttp.open("GET", "convert_unit.php?cropProdName="+encodeURIComponent(crop_prod) +
            "&unit="+encodeURIComponent(unit), false);
         xmlhttp.send();
         var default_unit_conversion = eval(xmlhttp.responseText);

         xmlhttp.open("GET", "convert_unit.php?cropProdName="+encodeURIComponent(crop_prod)
            + "&unit="+encodeURIComponent(tableUnit), false);
         xmlhttp.send();
         var table_unit_conversion = eval(xmlhttp.responseText);

         amountSoFar += parseFloat((amount/default_unit_conversion[1]) * table_unit_conversion[1]);
*/
         amountSoFar += parseFloat((amount/conversion[crop_prod][unit]) * 
            conversion[crop_prod][tableUnit]);
      }
   }

   var amountBox = document.getElementById("currentamountTable" + tableNum);
   amountBox.value = amountSoFar.toFixed(2);
}

/*************************************************************************
* Populates tables based on the specified Pack Date                      *
*************************************************************************/
function populateEntries() {
   var packDate = document.getElementById("selectPackDate").value;

   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "populate_entries.php?packDate=" + packDate, false);
   xmlhttp.send();
   var packing_array = eval(xmlhttp.responseText);
/*
for (i = 0; i < packing_array.length; i++) {
console.log(packing_array[i]);
}
*/
   var numCropProds = packing_array.length;
   
   var currIndex = 0;
   var currCrop = "";
   var currGrade = 0;
   var numRows = 0;

   if (numTables == 0) {
      var header = document.getElementById("DistributionTableHeader");
      header.style.display = "inline";
   }

   // Check if Table has already been created
   while (tableCropProd[packing_array[currIndex][2] + "Grade" +  packing_array[currIndex][4]] == 1) {
      currIndex++;
      if (currIndex >= numCropProds) {
         return;
      }
   }

   // Sets current crop and curent grade
   currCrop = packing_array[currIndex][2];
   currGrade = packing_array[currIndex][4];

   // Creates a new table
   numTables++;
   var tbl = document.createElement("table");
   tbl.id = "table" + numTables;
   tbl.style.width = "99%";
   tbl.style.marginBottom = "30px";

   // Caption
   var caption = tbl.createCaption();
/*
   xmlhttp.open("GET", "get_inventory.php?cropProd=" + encodeURIComponent(currCrop)
         + "&grade=" + currGrade, false);
   xmlhttp.send();
   var inventory_array = eval(xmlhttp.responseText);
*/
   var inventory_array = [getInventoryAmount(currCrop, currGrade), getInventoryUnit(currCrop, currGrade)];
   caption.innerHTML = createTableCaption(numTables, inventory_array[0], inventory_array[1], packing_array[currIndex][2], packing_array[currIndex][4]);

   // Header
   var header = tbl.createTHead();
   createHeader(header);

   do {
      if (currIndex == numCropProds || currCrop != packing_array[currIndex][2] || currGrade != packing_array[currIndex][4]) {

         // If currCrop or currGrade are not the same as the previous entries crop and grade: Finish creation of table   
         createTotalSoFar(tbl, numTables, packing_array[currIndex-1][3]);
         document.body.appendChild(tbl);
         // createHiddenInputs(numTables, numRows, inventory_array[0], inventory_array[1], packing_array[currIndex-1][2], packing_array[currIndex-1][4]);
         createHiddenInputs(numTables, numRows, inventory_array[0], inventory_array[1], packing_array[currIndex-1][2], packing_array[currIndex-1][4]);
console.log("crop in pop from pack: " + packing_array[currIndex-1][2]);
         calculateTotalSoFar(numTables);
         checkInventoryAmounts(numTables);

         // End of Packing List?
         if (currIndex == numCropProds) return;

         // Check if Table has already been created
         while (tableCropProd[packing_array[currIndex][2] + "Grade" + packing_array[currIndex][4]] == 1) {
            currIndex++;
            if (currIndex >= numCropProds) {
               return;
            }
         }

         // Create new Table
         numTables++;
         tbl = document.createElement("table");
         tbl.id = "table" + numTables;
         tbl.style.width = "99%";
         tbl.style.marginBottom = "30px";

         // Create Caption
         var caption = tbl.createCaption();
/*
         xmlhttp.open("GET", "get_inventory.php?cropProd=" + encodeURIComponent(packing_array[currIndex][2])
              + "&grade=" + packing_array[currIndex][4], false);
         xmlhttp.send();
         var inventory_array = eval(xmlhttp.responseText);
*/
         currCrop = packing_array[currIndex][2];
         currGrade = packing_array[currIndex][4];
         var inventory_array = [getInventoryAmount(currCrop, currGrade), getInventoryUnit(currCrop, currGrade)];

         caption.innerHTML = createTableCaption(numTables, inventory_array[0], inventory_array[1],  packing_array[currIndex][2], packing_array[currIndex][4]);

         // Header
         var header = tbl.createTHead();
         createHeader(header);

         numRows = 0;

      } else {

         // Else, add a row to the current table
         numRows++;
         var row = tbl.insertRow(-1);
         row.style.backgroundColor = "white";
         row.id = "row" + numRows + "table" + numTables;
         
         // Row Number
         var cell = row.insertCell(0);
         cell.innerHTML = numRows;
   
         // Target
         var cell = row.insertCell(1);
         cell.innerHTML = createTargetInput(packing_array[currIndex][0], numTables, numRows);
   
         // Amount
         var cell = row.insertCell(2);
         cell.innerHTML = createAmountInput(parseFloat(packing_array[currIndex][1]).toFixed(2), numTables, numRows);
   
         // Unit
         var cell = row.insertCell(3);
         cell.innerHTML = createUnitInput(packing_array[currIndex][3], numTables, numRows, packing_array[currIndex][2]);
   
         // Comments
         var cell = row.insertCell(4);
         cell.innerHTML = createCommentsInput("", numTables, numRows);
   
         // Delete Button
         var cell = row.insertCell(5);
         cell.innerHTML = createDeleteButton(numTables, numRows);
   
         // Copy Button
         var cell = row.insertCell(6);
         cell.innerHTML = createCopyButton(numTables, numRows);

         currIndex++;
      }
   } while (true);
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

   // Create value for distDate
   var month = document.getElementById("month").value;
   var day = document.getElementById("day").value;
   var year = document.getElementById("year").value;
   var theDate = year+"-"+month+"-"+day;
   var allvalues = [];
   var ind = 0;

   for (var i = 1; i <= numTables; i++) {

      // Check that table exists
      if (document.getElementById("table" + i) != null) {

         var numRows = numrowsTable[i];
         var crop_prod = cropprodnameTable[i];
         var grade = cropprodgradeTable[i];
         if (document.getElementById("table" + i).getAttribute("name") === "alertAmount") {
            var amountsMessage = true;
         } else {
            var amountsMessage = false;
         }

         for (var j = 1; j <= numRows; j++) {

            //Check that row exists
            if (document.getElementById("row" + j + "table" + i) != null) {

               var values = [];
               values[0] = theDate;
               values[1] = crop_prod;
               values[2] = grade;

               for (var k = 3; k < tableSize; k++) {
                  var elem = document.getElementById(fields_array[k] + "Table" + i + "Row" + j);
                  elem = elem.value;
                  values[k] = elem;

                  if (fields_array[k] === "unit") unit = elem;
                  if (fields_array[k] === "amount") amount = elem;
               }

               // Adds a warning in comments if value in distribution exceeds inventory amount
               if (amountsMessage) {
                  values[6] += "\nDistributed amount exceeded amount that was in inventory";
               }

               // Performs unit conversion
/*
               xmlhttp.open("GET", "convert_unit.php?cropProdName="+encodeURIComponent(crop_prod)
                  + "&unit="+encodeURIComponent(unit), false);
               xmlhttp.send();
               default_unit_conversion = eval(xmlhttp.responseText);
               values[fields_array.indexOf("amount")] = amount/default_unit_conversion[1];
               values[fields_array.indexOf("unit")] = default_unit_conversion[0];
*/
/*
               values[fields_array.indexOf("amount")] = amount/conversion[crop_prod][default_unit[crop_prod]];
               values[fields_array.indexOf("unit")] = default_unit[crop_prod];
*/
               values[fields_array.indexOf("amount")] = amount/conversion[crop_prod][unit];
               values[fields_array.indexOf("unit")] = default_unit[crop_prod];
               if (values[fields_array.indexOf("amount")] > 0) {
                  allvalues[ind] = values;
                  ind++;
               }

/*
               // Convert Javascript arrays to PHP arrays
               values_array_json = JSON.stringify(values);
               fields_array_json = JSON.stringify(fields_array);

               // XMLHTTP request to insert row into distribution
               xmlhttp.open("GET", "insert_row.php?values_array="+encodeURIComponent(values_array_json) 
                 +" &fields_array="+fields_array_json+"&tableSize="+tableSize, false);
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
   // Convert Javascript arrays to PHP arrays
   values_array_json = JSON.stringify(allvalues);
   fields_array_json = JSON.stringify(fields_array);

   // XMLHTTP request to insert row into distribution
   xmlhttp.open("GET", "insert_all_rows.php?values_array="+encodeURIComponent(values_array_json) 
     +" &fields_array="+fields_array_json+"&tableSize="+tableSize, false);
   xmlhttp.send();
   if (xmlhttp.responseText != "") {
      alert("ERROR in MySQL inserting row into table:\n" + xmlhttp.responseText);
      return false;
   }
   alert("Successfully entered data into table!");
   location.reload(true);
}

/*************************************************************************
* If there are tables in the distribution input form that don't match    *
* the inventory amounts, alert the user and request confirmation         *
*************************************************************************/
function alert_amounts() {
   for (var i = 1; i <= numTables; i++) {

      // Check that table exists
      if (document.getElementById("table" + i) != null) {

         var tbl = document.getElementById("table" + i);
         if (tbl.getAttribute("name") === "alertAmount") {
            return confirm("There are tables in this Distribution Record that exceed the amount of crops/products in inventory\n" + 
               "Are you sure you would like to insert these records into the Distribution Table?");
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
         var tableGrade = cropprodgradeTable[i];

         for (var j = 1; j <= numRows; j++) {

            // Check that row exists
            if (document.getElementById("row" + j + "table" + i) != null) {

               for (var k = 3; k < tableSize; k++) {
                  var elem = document.getElementById(fields_array[k] + "Table" + i + "Row" + j);

                  if (fields_array[k] != "comments" && fields_array[k] != "amount") {
                     if (checkEmpty(elem.value) || elem.value === "" || elem.value === "undefined" || elem.value === "null") {
                        alert("Check Table " + escapeescapeHtml(tableName) + " - Grade: " + tableGrade + "\n" + 
                              "Row " + j + ": " + fields_array[k] + "\n" + 
                              "There is no value in the input field");
                        return false;
                     }
                  }

                  if (numericalFields.indexOf(fields_array[k]) > -1) {
                     if (isNaN(elem.value)) {
                        alert("Check Table " + escapeescapeHtml(tableName) + " - Grade: " + tableGrade + "\n" + 
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

