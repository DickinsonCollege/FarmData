<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
$crop = $_GET['crop'];
$cover = $_GET['cover'];
$year = $_GET['year'];
$calc_SEEDS = $_GET['calc_SEEDS'];
if (!isset($calc_SEEDS) || $calc_SEEDS == "") {
  $calc_SEEDS = 1000;
}
if (isset($crop) && $crop != "") {
   $isCover = false;
} else {
   $isCover = true;
}
?>

<h3> Seed Order and Inventory </h3>
<br clear="all"/>

<script type="text/javascript">
function submitform(isCov) {
   document.getElementById("isCover").value=isCov;
   document.forms["seedform"].submit();
}
</script>

<form name='form' id = 'seedform' method='POST' action='handleOrder.php'>
<input type="hidden" id = "isCover" name="isCover">
<?php
if ($_SESSION['cover']) {
   echo '<table>';
   echo '<tr><th>Vegetable</th><th>Cover Crop</th></tr><td>';
}
?>
<label for="crop">Crop:&nbsp;</label>
<div id='cropdiv' class='styled-select'>
<select name='crop' id='crop' class='mobile-select' 
 onchange="submitform(false);">
<?php
$sql = "select crop from plant";
$res = mysql_query($sql);
echo mysql_error();
echo "<option value='Crop' disabled";
if (!isset($crop) || $crop == "") {
   echo " selected";
}
echo ">Crop</option>";
while ($row = mysql_fetch_array($res)) {
   echo "<option value='".$row['crop']."'";
   if ($row['crop'] == $crop) {
      echo " selected";
   }
   echo ">".$row['crop']."</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>

<?php
function printYear($name) {
   global $year;
   if ($name == "coverYear") {
      $isCov = "true";
   } else {
      $isCov = "false";
   }
   $curYear = strftime("%Y");
   if (isset($year)) {
      $curYear = $year;
   }
   echo '<label for="'.$name.'">Planting Year:&nbsp;</label>';
   echo "<div id='".$name."div' class='styled-select'>";
   echo "<select name='".$name."' id='".$name."' class='mobile-select' ";
   echo "onchange='submitform(".$isCov.")';>";
   for ($y = $curYear - 3; $y < $curYear + 5; $y++) {
      echo "<option value='".$y."'";
      if ($y == $curYear) {
         echo " selected";
      }
      echo ">".$y."</option>";
   }
   echo '</select>';
   echo '</div>';
}
printYear("year");
?>

<br clear="all"/>
<!--
<br clear="all"/>
<input type="submit" name="submitCrop" class = "submitbutton" value="Choose Crop and Year" >
-->
<?php 
if ($_SESSION['cover']) {
   echo '</td><td>';
   echo '<label for="cover">Crop:&nbsp;</label>';
   echo "<div id='covercropdiv' class='styled-select'>";
   echo "<select name='cover' id='cover' class='mobile-select' ";
   echo "onchange='submitform(true);'>";
   $sql = "select crop from coverCrop";
   $res = mysql_query($sql);
   echo mysql_error();
   echo "<option value='Cover Crop' disabled";
   if (!isset($cover) || $cover == "") {
      echo " selected";
   }
   echo ">Crop</option>";
   while ($row = mysql_fetch_array($res)) {
      echo "<option value='".$row['crop']."'";
      if ($row['crop'] == $cover) {
         echo " selected";
      }
      echo ">".$row['crop']."</option>";
   }
   echo "</select></div>";
   echo "<br clear='all'/>";
   printYear("coverYear");
   echo '<br clear="all"/>';
//   echo '<br clear="all"/>';
//   echo '<input type="submit" name="submitCoverCrop" class = "submitbutton" value="Choose Crop and Year" >';
   echo '</td></tr></table>';
}
?>
<br clear="all"/>
<?php
$seeds = "";
$rowft = "";
$defUnit = "";
$acres = "";
$rate = "";
if (isset($crop) && $crop != "") {
   $sql = "select * from seedInfo where crop='".$crop."'";
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $seeds = $row['seedsGram'];
      $rowft = $row['seedsRowFt'];
      $defUnit = $row['defUnit'];
   }
   $sql = "select rowFt from toOrder where crop='".$crop."' and year = ".$year;
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $rowftToPlant = $row['rowFt'];
   }
} else if (isset($cover) && $cover != "") {
   $sql = "select * from coverSeedInfo where crop='".$cover."'";
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $rate = $row['rate'];
   }
   $sql = "select * from coverToOrder where crop='".$cover."' and year=".$year;
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $acres = $row['acres'];
   }
}

function convertFromGram($unit, $seeds) {
   if ($unit == "GRAM") {
      $res = $seeds;
   } else if ($seeds == 0) {
      $res = 0;
   } else if ($unit == "OUNCE") {
      $res = $seeds * 28.3495;
   } else if ($unit == "POUND") {
      $res = $seeds * (28.3495 * 16);
   } else {
      $res = 0;
   }
   return $res;
}

   $units = array('GRAM', 'OUNCE', 'POUND');
if (isset($crop) && $crop != "") {
   echo '<label for="rowft">Seeds per row foot:&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rowft"';
   echo 'id="rowft" value="'.$rowft.'">';
   echo '<br clear="all"/>';
   echo '<input class="textbox25 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" ';
   echo 'name ="seedsIn" id="seedsIn" value="'.
     number_format((float) convertFromGram($defUnit, $seeds), 1, '.','').'"> ';
   echo '<label for="seedsIn">&nbsp; seeds per&nbsp;</label>';
   echo "<div id='defUnitdiv' class='styled-select'>";
   echo "<select name='defUnit' id='defUnit' class='mobile-select'>";
   for ($i = 0; $i < count($units); $i++) {
      echo "<option value='".$units[$i]."'";
      if ($units[$i] == $defUnit) {
         echo " selected";
      }
      echo ">".$units[$i]."</option>";
   }
   echo '</select>';
   echo '</div>';
   echo '<br clear="all"/>';
   echo '<label for="rowftToPlant">Total row feet';
   if (isset($crop)) { 
    echo " of ".$crop;
   }
   echo ' to plant';
   if (isset($year)) {
     echo " in ".$year;
   }
   echo ':&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rowftToPlant"';
     echo 'id="rowftToPlant" value="'.$rowftToPlant.'">';
   echo '<br clear="all"/>';
   echo '<input type="submit" name="updateSeedInfo" class = "submitbutton" value="Update Seeding Information" >';
} else if (isset($cover) && $cover != "") {
   echo '<label for="acres">Acres of '.$cover.' to plant in '.$year.':&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="acres"';
   echo 'id="acres" value="'.$acres.'">';
   echo '<br clear="all"/>';
   echo '<label for="rate">Seeding rate for '.$cover.' (lbs/acre):&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rate"';
   echo 'id="rate" value="'.$rate.'">';
   echo '<br clear="all"/>';
   echo '<input type="submit" name="updateSeedInfo" class = "submitbutton" value="Update Seeding Information" >';
}
echo '<br clear="all"/>';
?>
<input type="hidden" id="invRows" name="invRows" value="0">
<input type="hidden" id="orderRows" name="orderRows" value="0">
<script type="text/javascript">
<?php
  if ($isCover) {
     echo "var isCover = true;";
  } else {
     echo "var isCover = false;";
  }
?>

function update_seeds(unit, sdsGram) {
   var sds=0;
   var seeds = document.getElementById('calc_SEEDS');
   var gram = document.getElementById('calc_GRAM');
   var ounce = document.getElementById('calc_OUNCE');
   var pound = document.getElementById('calc_POUND');
   if (unit == "SEEDS") {
      sds = seeds.value;
   } else {
      var val = document.getElementById('calc_' + unit).value;
      if (unit == "GRAM") {
         var gv = gram.value;
         sds = gv * sdsGram;    
      } else if (unit == "OUNCE") {
         var ov = ounce.value;
         sds = ov * sdsGram * 28.3495;     
      } else if (unit == "POUND") {
         var pv = pound.value;
         sds = pv * sdsGram * 28.3495 * 16;      
      } else {
         alert("Illegal unit!");
      }
   }
   if (unit != "SEEDS") {
      seeds.value = sds.toFixed(1);
   }
   if (unit != "GRAM") {
      gram.value = (sds / sdsGram).toFixed(1);
   }
   if (unit != "OUNCE") {
      ounce.value = (sds / (sdsGram * 28.3495)).toFixed(2);
   }
   if (unit != "POUND") {
      pound.value = (sds / (sdsGram * 28.3495 * 16)).toFixed(3);
   }
}

var invRows = 0;
var crop = "<?php echo $crop;?>";
<?php
if (isset($year)) {
   echo 'var year = '.$year.';';
}
if ($seeds != "") {
   echo 'var seeds = '.$seeds.';';
}
if ($rowft != "") {
   echo 'var rowft = '.$rowft.';';
}
?>

function fromGram(unit, sdsGram) {
   var res = 0;
   if (unit == "GRAM") {
       res = sdsGram;
   } else if (unit == "OUNCE") {
       res = sdsGram * 28.3495;
   } else if (unit == "POUND") {
       res = sdsGram * (28.3495 * 16);
   }
   return res;
}

/*
function toGram(unit, sdsGram) {
   var res = 0;
   if (unit == "GRAM") {
       res = sdsGram;
   } else if (unit == "OUNCE") {
       res = sdsGram / 28.3495;
   } else if (unit == "POUND") {
       res = sdsGram / (28.3495 * 16);
   }
   return res;
}
*/

function update_rowft(rowNum) {
   var rowftV = document.getElementById('rowft' + rowNum).value;
   var toplant = document.getElementById('toplant' + rowNum);
   if (isCover) {
      toplant.value = (parseFloat(rowftV) * parseFloat(document.getElementById('rate').value)).toFixed(2);
   } else {
      var defUnit = document.getElementById('unit' + rowNum).value;
      toplant.value = (rowft * parseFloat(rowftV) / fromGram(defUnit, seeds)).toFixed(2);
   }
}

function update_tobuy(rowNum) {
   var toplant = document.getElementById('toplant' + rowNum).value;
   var inven = document.getElementById('inven' + rowNum).value;
   var tobuy = document.getElementById('tobuy' + rowNum);
   tobuy.value= (toplant - inven).toFixed(2);
   update_totals();
}

function update_totals() {
  var totRowFt = 0;
  var totToPlant = 0;
  var totInven = 0;
  var totToBuy = 0;
  for (var i = 1; i <= invRows; i++) {
     if (document.getElementById("invRow" + i) != null && 
         document.getElementById("invRow" + i).innerHTML != "") {
         totRowFt += parseFloat(document.getElementById("rowft" + i).value);
         totToPlant += parseFloat(document.getElementById("toplant" + i).value);
         totToBuy += parseFloat(document.getElementById("tobuy" + i).value);
         totInven += parseFloat(document.getElementById("inven" + i).value);
     }
  }
  document.getElementById("totRowFt").value = totRowFt.toFixed(1);
  document.getElementById("totToPlant").value = totToPlant.toFixed(2);
  document.getElementById("totToBuy").value = totToBuy.toFixed(2);
  document.getElementById("totInven").value = totInven.toFixed(2);
}

function deleteInvButton(rowNum) {
   var rowName = document.getElementById("invRow" + rowNum);
   rowName.innerHTML = "";
   update_totals();
}

function deleteOrderButton(rowNum) {
   var rowName = document.getElementById("orderRow" + rowNum);
   rowName.innerHTML = "";
   update_totalPrice();
   updateUnits();
}

var colorArray= ['FFE5CC','FFCC99', 'FFB266', 'FF9933', 'FF8000', 'CC6600', '994C00'];

function setColor(rowNum, sYear, inven) {
   var table = document.getElementById('inven');
   var row = table.rows[rowNum];
   var inv = document.getElementById('inven' + rowNum);
   if (inv != null) {
      inven = inv.value;
   }
   if (inven == 0) {
      row.style.backgroundColor = "red";
   } else {
      var age = year - sYear;
      if (age < 0) age = 0;
      if (age > 3) age = 3;
      row.style.backgroundColor = colorArray[2 * age];
   }
}

function addRowInven(code, variety, rowft, defUnit, toPlant, inInventory, sYear) {
   var table = document.getElementById('inven');
   invRows++;
   var ir = document.getElementById('invRows');
   ir.value = invRows;
   var numRows = table.rows.length;
   var row = table.insertRow(numRows - 1);
   row.id = "invRow" + invRows;
   setColor(invRows, sYear, inInventory);

   var cell = row.insertCell(0);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="code' + invRows + '" id="code' +
      invRows + '" style="width:100%" readonly onkeypress="stopSubmitOnEnter(event);" value="' + code + '">';
   cell.innerHTML=htm;
  
   cell = row.insertCell(1);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="variety' + invRows + '" id="variety' +
      invRows + '" style="width:100%" readonly onkeypress="stopSubmitOnEnter(event);" value="' + variety + '">';
   cell.innerHTML=htm;

   cell = row.insertCell(2);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="sYear' + invRows + '" id="sYear' +
      invRows + '" style="width:100%" readonly onkeypress="stopSubmitOnEnter(event);" value="' + sYear + '">';
   cell.innerHTML=htm;
  
   cell = row.insertCell(3);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="rowft' + invRows + '" id="rowft' +
      invRows + '" style="width:100%" value="' + rowft.toFixed(1) + 
      '" onkeypress="stopSubmitOnEnter(event);" oninput="update_rowft(' + invRows + ');update_tobuy(' + 
      invRows + ');">';
   cell.innerHTML=htm;

   var col = 4;

   if (!isCover) {
      cell = row.insertCell(col);
      var htm = '<input class="textbox2 mobile-input" type="text" name ="unit' + invRows + '" id="unit' +
         invRows + '" style="width:100%" readonly onkeypress="stopSubmitOnEnter(event);" value="' + defUnit+ '">';
      cell.innerHTML=htm;
      col++;
   }
  
   cell = row.insertCell(col);
   col++;
   var htm = '<input class="textbox2 mobile-input" type="text" name ="toplant' + invRows + '" id="toplant' +
      invRows + '" style="width:100%" value="' + toPlant.toFixed(2) + 
      '" readonly onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;
  
   cell = row.insertCell(col);
   col++;
   var htm = '<input class="textbox2 mobile-input" type="text" name ="inven' + invRows + '" id="inven' +
      invRows + '" style="width:100%" value="' + inInventory.toFixed(2) + '" oninput="update_tobuy(' + 
      invRows + ');setColor(' + invRows + ', ' + sYear + ', ' + inInventory + ');" ' +
      'onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;
  
   cell = row.insertCell(col);
   col++;
   var htm = '<input class="textbox2 mobile-input" type="text" name ="tobuy' + invRows + '" id="tobuy' +
      invRows + '" style="width:100%" value="' + (toPlant - inInventory).toFixed(2) + 
      '" readonly onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;
  
   cell = row.insertCell(col);
   col++;
   cell.innerHTML = "<input type='button' class='deletebutton' value='Delete'" +
     "onclick='deleteInvButton(" + invRows + ");'>";
   update_totals();
}

function add_inven(defUnit) {
   if (show_add_inven_confirm(defUnit)) {
      var variety = document.getElementById("var").value;
      var srowft = parseFloat(document.getElementById("varRowFt").value);
      var inven = parseFloat(document.getElementById("varInven").value);
      var sYear = parseInt(document.getElementById("varYear").value);
      var org = document.getElementById("varOrg").value;
      var sCrop = "";
      if (isCover) {
         sCrop = document.getElementById("cover").value;
      } else {
         sCrop = crop;
      }
      var xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "get_code.php?crop="+sCrop+"&org="+org+"&sYear="+sYear+"&isCover="+isCover, false);
      xmlhttp.send();
      var code = xmlhttp.responseText;
      var amt = 0;
      if (isCover) {
         var rate = document.getElementById("rate").value;
         amt = srowft * rate;
      } else {
         amt = (srowft * rowft) / fromGram(defUnit, seeds);
      }
      addRowInven(code, variety, srowft, defUnit, amt, inven, sYear);
   }
}

function show_var_confirm() {
   var variety = document.getElementById("newVar").value;
   if (variety == "") {
      alert("Enter new variety.");
      return false;
   } else {
      return confirm("Confirm Entry:\nNew Variety: " + variety);
   }
}

function show_source_confirm() {
   var source = document.getElementById("newSource").value;
   if (source == "") {
      alert("Enter new source.");
      return false;
   } else {
      return confirm("Confirm Entry:\nNew Source: " + source);
   }
}

function show_add_inven_confirm(defUnit) {
   var con = "Confirm Entry:\n";
   var variety = document.getElementById("var").value;
   if (variety == 0) {
      alert("Please select variety.");
      return false;
   } else {
      con += "Variety: " + variety + "\n";
   }
   var rowft = document.getElementById("varRowFt").value;
   var rf = "Row Feet";
   if (isCover) {
     rf = "Acres";
   }
   if (!isFinite(rowft) || rowft < 0 || rowft == "") {
      alert(rf + " must be 0 or a positive number.");
      return false;
   } else {
      con += rf + ": " + rowft + "\n";
   }
   var inven = document.getElementById("varInven").value;
   if (!isFinite(inven) || inven < 0 || inven == "") {
      alert("Inventory must be 0 or a positive number.");
      return false;
   } else {
      con += "Inventory: " + inven + " " + defUnit +"(S)\n";
   }
   var year = document.getElementById("varYear").value;
   con += "Purchase Year: " + year + "\n";
   var org = document.getElementById("varOrg").value;
   con += "Organic: ";
   if (org == 1) {
      con += "OG";
   } else {
      con += "UT";
   }
   return confirm(con);
}

function show_inven_confirm() {
   var table = document.getElementById("inven");
   var count = 1;
   for (var j = 1; j <= invRows; j++ ) {
      if (document.getElementById("invRow" + j) != null &&
          document.getElementById("invRow"+j).innerHTML != "") {
          var rowft = document.getElementById("rowft" + j).value;
          var variety = document.getElementById("variety" + j).value;
          if (!isFinite(rowft) || rowft < 0) {
             alert("Invalid row feet for " + variety + " in row " + count + "!");
             return false;
          }
          var inven = document.getElementById("inven" + j).value;
          if (!isFinite(inven) || inven < 0) {
             alert("Invalid inventory for " + variety + " in row " + count + "!");
             return false;
          }
          count++;
      }
   }
   return true;
}

var orderRows = 0;
var statusArray = ['PENDING', 'ORDERED'];
var monthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

function addSource(cell, cellDate, row, num, source, sdate) {
   var htm = "<div id='source" + num + "div" + row + "' class='styled-select'> <select name='searchSource"
     + num + "_" + row + "' id='searchSource" + num + "_" + row + "' class='mobile-select'><option value='0' disabled";
   if (source == "") {
       htm += " selected";
   }
   htm += ">Source</option>";
<?php
$sql = "select source from source";
$res = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($res)) {
   echo "htm += \"<option value='".$row['source']."'\";";
   echo "if ('".$row['source']."' == source) {";
   echo "   htm += ' selected';";
   echo "}";
   echo "htm += \">".$row['source']."</option>\";";
}
?>
   htm += "</select> </div>";
   cell.innerHTML=htm;

   var day, month, year;
   if (sdate == "") {
      var dt = new Date();
      day = dt.getDate();
      month = dt.getMonth() + 1;
      year = dt.getFullYear();
   } else {
      var dt = sdate.split("-");
      day = parseInt(dt[2]);
      month = parseInt(dt[1]);
      year = parseInt(dt[0]);
   }
   htm = '<div class="styled-select"><select name="month' + num + '_' + row + '" id="month' +
    num + '_' + row + '" class="mobile-select">';
   for (var i = 1; i <= 12; i++) {
      htm += '<option value=' + i;
      if (i == month) {
         htm += ' selected';
      }
      htm += '>' + monthArray[i - 1] + ' </option>';
   }
   htm += '</select></div>';
   htm += '<div class="styled-select"><select name="day' + num + '_' + row + '" id="day' +
    num + '_' + row + '" class="mobile-select">';
   for (var i = 1; i <= 31; i++) {
      htm += '<option value=' + i;
      if (i == day) {
         htm += ' selected';
      }
      htm += '>' + i + ' </option>';
   }
   htm += '</select></div>';
   htm += '<div class="styled-select"><select name="year' + num + '_' + row + '" id="year' +
    num + '_' + row + '" class="mobile-select">'
   for (var i = year - 4; i <= year + 3; i++) {
      htm += '<option value=' + i;
      if (i == year) {
         htm +=  ' selected';
      }
      htm += '>' + i + ' </option>';
   }
   htm += '</select></div>';
   cellDate.innerHTML = htm;
}

function update_totalPrice() {
  var totPrice = 0;
  for (var i = 1; i <= orderRows; i++) {
     if (document.getElementById("orderRow" + i) != null && 
         document.getElementById("orderRow" + i).innerHTML != "") {
         totPrice += parseFloat(document.getElementById("totPrice" + i).value);
     }
  }
  document.getElementById("totPrice").value = totPrice.toFixed(2);
}

function update_price(row) {
   var price = parseFloat(document.getElementById("price" + row).value);
   var units = parseFloat(document.getElementById("catUnitsOrdered" + row).value);
   document.getElementById("totPrice" + row).value = (price * units).toFixed(2);
   update_totalPrice();
}

function updateUnits() {
  var totUnits = 0;
  for (var i = 1; i <= orderRows; i++) {
     if (document.getElementById("orderRow" + i) != null && 
         document.getElementById("orderRow" + i).innerHTML != "") {
         totUnits += parseFloat(document.getElementById("defUnitsOrdered" + i).value);
     }
  }
  document.getElementById("totUnits").value = totUnits.toFixed(3);
}

function update_def_units(row) {
   var units = parseFloat(document.getElementById("catUnitsOrdered" + row).value);
   var unitsPer = parseFloat(document.getElementById("unitsPerCatUnit" + row).value);
   document.getElementById("defUnitsOrdered" + row).value = (unitsPer * units).toFixed(3);
   updateUnits();
}

function load_order(row) {
   var variety = document.getElementById("varOrder" + row).value;
   var xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "load_order.php?crop=" + encodeURIComponent(crop)
     + "&variety=" + encodeURIComponent(variety), false);
   xmlhttp.send();
   if (xmlhttp.responseText != "") {
      var resp = JSON.parse(xmlhttp.responseText);
      document.getElementById("catalogOrder" + row).value = 
         resp['catalogOrder'];
      document.getElementById("catalogUnit" + row).value = 
         resp['catalogUnit'];
      document.getElementById("price" + row).value = 
         resp['price'];
      document.getElementById("unitsPerCatUnit" + row).value = 
         resp['unitsPerCatUnit'];
      document.getElementById("catUnitsOrdered" + row).value = 
         resp['catUnitsOrdered'];
      document.getElementById("varSource" + row).value = 
         resp['source'];
      if (resp['organic'] == 'OG') {
         document.getElementById("organic" + row).value = 1;
      } else {
         document.getElementById("organic" + row).value = 0;
      }
      update_def_units(row);
      update_price(row);
   } 
}

function addRowOrder(variety, source, catalogOrder, organic, catalogUnit, price, unitsPerCatUnit,
   catUnitsOrdered, status, defUnit, source1, sdate1, source2, sdate2, source3, sdate3) {
   var table = document.getElementById("order");
   orderRows++;
   var or = document.getElementById('orderRows');
   or.value = orderRows;
   var numRows = table.rows.length;
   var row = table.insertRow(numRows - 1);
   row.id = "orderRow" + orderRows;

   var input = document.createElement("input");
   input.setAttribute("type", "hidden");
   input.setAttribute("name", "orderId" + orderRows);
   input.setAttribute("id", "orderId" + orderRows);
   input.setAttribute("value", orderRows);

   //append to form element that you want .
   document.getElementById("seedform").appendChild(input);



   var cell = row.insertCell(0);
   var htm = "<div id='vardiv' class='styled-select'> <select name='varOrder" + orderRows + 
       "' id='varOrder" + orderRows + "' onChange='load_order(" + 
       orderRows + ");' class='mobile-select'><option value='0' disabled";
   if (variety == "") {
       htm += " selected";
   }
   htm += ">Variety</option>";
<?php
if ($isCover) {
   $sql = "select variety from coverVariety where crop = '".$cover."'";
} else {
   $sql = "select variety from variety where crop = '".$crop."'";
}
$res = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($res)) {
   echo "htm += \"<option value='".$row['variety']."'\";";
   echo "if ('".$row['variety']."' == variety) {";
   echo "   htm += ' selected';";
   echo "}";
   echo "htm += \">".$row['variety']."</option>\";";
}
?>
   htm += "</select> </div>";
   cell.innerHTML=htm;

   var cell = row.insertCell(1);
   var htm = "<div id='sourcediv' class='styled-select'> <select name='varSource" + orderRows + 
       "' id='varSource" + orderRows + "' class='mobile-select'><option value='0' disabled";
   if (source == "") {
       htm += " selected";
   }
   htm += ">Source</option>";
<?php
$sql = "select source from source";
$res = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($res)) {
   echo "htm += \"<option value='".$row['source']."'\";";
   echo "if ('".$row['source']."' == source) {";
   echo "   htm += ' selected';";
   echo "}";
   echo "htm += \">".$row['source']."</option>\";";
}
?>
   htm += "</select> </div>";
   cell.innerHTML=htm;

   var cell = row.insertCell(2);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="catalogOrder' + orderRows + 
      '" id="catalogOrder' +
      orderRows + '" style="width:100%" value="' + catalogOrder + '" onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;

   var cell = row.insertCell(3);
   var htm = "<div id='orgdiv' class='styled-select'> <select name='organic" + orderRows + 
       "' id='organic" + orderRows + "' class='mobile-select'><option value='' disabled";
   if (organic == "") {
       htm += " selected";
   }
   htm += ">Organic</option><option value='1'";
   if (organic == 1) {
      htm += " selected";
   }
   htm += ">OG</option><option value='0'";
   if (organic == 0) {
      htm += " selected";
   }
   htm += ">UT</option></select></div>";
   cell.innerHTML = htm;

   var cell = row.insertCell(4);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="catalogUnit' + orderRows + 
      '" id="catalogUnit' +
      orderRows + '" style="width:100%" value="' + catalogUnit + '" onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;

   var cell = row.insertCell(5);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="price' + orderRows + 
      '" id="price' +
      orderRows + '" style="width:100%" value="' + price + '" oninput="update_price(' + orderRows + 
      ');" onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;

   var cell = row.insertCell(6);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="unitsPerCatUnit' + orderRows + 
      '" id="unitsPerCatUnit' +
      orderRows + '" style="width:100%" value="' + unitsPerCatUnit.toFixed(3) + 
      '" oninput="update_def_units(' + orderRows + ');update_price(' + orderRows + 
      ');" onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;

   var cell = row.insertCell(7);
   var htm = '<input class="textbox2 mobile-input" type="text" name ="catUnitsOrdered' + orderRows + 
      '" id="catUnitsOrdered' +
      orderRows + '" style="width:100%" value="' + catUnitsOrdered.toFixed(0) + 
      '" oninput="update_def_units(' + orderRows + ');update_price(' + orderRows + 
      ');" onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;

   var cell = row.insertCell(8);
   var unitsOrdered = 0;
   if (unitsPerCatUnit != "" && catUnitsOrdered != "") {
      unitsOrdered = parseFloat(unitsPerCatUnit) * parseFloat(catUnitsOrdered);
   }
   var htm = '<input class="textbox2 mobile-input" type="text" name ="defUnitsOrdered' + orderRows + 
      '" id="defUnitsOrdered' +
      orderRows + '" style="width:100%" value="' + unitsOrdered.toFixed(3) + 
      '" readonly onchange="update_price(' + orderRows + ');" onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;

   var cell = row.insertCell(9);
   var totPrice = 0;
   if (price != "" && catUnitsOrdered != "") {
      totPrice = parseFloat(price) * parseFloat(catUnitsOrdered);
   }
   var htm = '<input class="textbox2 mobile-input" type="text" name ="totPrice' + orderRows + 
      '" id="totPrice' +
      orderRows + '" style="width:100%" value="' + totPrice.toFixed(2) + 
      '" readonly onkeypress="stopSubmitOnEnter(event);">';
   cell.innerHTML=htm;

   var cell = row.insertCell(10);
   var htm = "<div id='statusdiv' class='styled-select'> <select name='status" + orderRows + 
       "' id='status" + orderRows + "' class='mobile-select'><option value='0' disabled";
   if (status == "") {
       htm += " selected";
   }
   htm += ">Status</option>";
   for (i = 0; i < statusArray.length; i++) {
      var stat = statusArray[i];
      htm += "<option value='" + stat + "'";
      if (status == stat) {
         htm += " selected";
      }
      htm += ">" + stat + "</option>";
   }
   htm += "</select></div>";
   cell.innerHTML = htm;

   var cell = row.insertCell(11);
   cell.innerHTML = "<input type='button' class='deletebutton' value='Delete'" +
     "onclick='deleteOrderButton(" + orderRows + ");'>";

   var cell = row.insertCell(12);
   cell.innerHTML = "<input type='submit' class='submitbutton' value='Add'" +
     "onclick='return confirm_order_row(" + orderRows + ", 0);' name='add_inventory" + orderRows + "'>";

   var cell = row.insertCell(13);
   var cellDate = row.insertCell(14);
   addSource(cell, cellDate, orderRows, 1, source1, sdate1);
   var cell = row.insertCell(15);
   var cellDate = row.insertCell(16);
   addSource(cell, cellDate, orderRows, 2, source2, sdate2);
   var cell = row.insertCell(17);
   var cellDate = row.insertCell(18);
   addSource(cell, cellDate, orderRows, 3, source3, sdate3);
   update_totalPrice();
   updateUnits();
}

function confirm_order_row(j, count) {
   var ct = "";
   if (count > 0) {
     ct = " in row: " + count;
   }
   var status = document.getElementById("status" + j).value;
   if (status == 0) {
      alert("Please select a status" + ct);
      return false;
   } else if (status == "PENDING" && count == 0) {
      alert("An order row can only added to inventory if it has status " +
            "ORDERED");
      return false;
   }
   var variety = document.getElementById("varOrder" + j).value;
   if (variety == 0) {
      alert("Please select a variety" + ct);
      return false;
   }
   var source = document.getElementById("varSource" + j).value;
   if (source == 0) {
      alert("Please select a source" + ct);
      return false;
   }
   var catalogOrder = document.getElementById("catalogOrder" + j).value;
   if (catalogOrder == "") {
      alert("Please enter catalog order number" + ct);
      return false;
   }
   var catalogUnit = document.getElementById("catalogUnit" + j).value;
   if (catalogUnit == "") {
      alert("Please enter catalog unit" + ct);
      return false;
   }
   var price = document.getElementById("price" + j).value;
   if (price == "" || !isFinite(price) || price <= 0) {
      alert("Price must be a positive number" + ct);
      return false;
   }
   var unitsPerCatUnit = document.getElementById("unitsPerCatUnit" + j).value;
   if (unitsPerCatUnit == "" || !isFinite(unitsPerCatUnit) || unitsPerCatUnit <= 0) {
      var unt = "Units";
      if (isCover) {
          unt = "Pounds";
      }
      alert(unt + " per catalog unit must be a positive number" + ct);
      return false;
   }
   var catUnitsOrdered = document.getElementById("catUnitsOrdered" + j).value;
   if (catUnitsOrdered == "" || !isFinite(catUnitsOrdered) || catUnitsOrdered <= 0) {
      alert("Catalog units ordered must be a positive number" + ct);
      return false;
   }
   return true;
}

function show_order_confirm() {
   var count = 1;
   for (var j = 1; j <= orderRows; j++ ) {
      if (document.getElementById("orderRow" + j) != null &&
          document.getElementById("orderRow"+j).innerHTML != "") {
          if (!confirm_order_row(j, count)) {
             return false;
          }
          count++;
      }
   }
   return true;
}
</script>

<?php

function convertToGram($amt, $unit) {
   if ($unit == "GRAM") {
      $res = $amt;
   } else if ($unit == "OUNCE") {
      $res = $amt * 0.035274;
   } else if ($unit == "POUND") {
      $res = $amt * 0.00220462;
   } else {
      $res = 0;
   }
   return $res;
}

if (isset($crop) && $crop != "" && $seeds != "") {
   echo '<br clear="all"/>';
   echo "<h3>Seed Calculator</h3>";
   echo '<br clear="all"/>';
   echo "<table>";
   echo "<tr><th>Unit</th><th>".$crop." Seeds Per Unit</th><th>Row Feet Per Unit</th><th>";
   echo "Calculator: Enter A Number In Any Row To Convert To Other Units</th></tr>";
   echo "<tr><td>SEEDS</td><td>1</td><td>0</td><td>";
   echo '<input class="textbox2 mobile-input" type="text"';
   echo ' name ="calc_SEEDS" id="calc_SEEDS" value="'.$calc_SEEDS.
        '" style="width:100%" onkeypress="stopSubmitOnEnter(event);" oninput="update_seeds(\'SEEDS\','.
        $seeds.');">';
   echo "</td></tr>";
   for ($i = 0; $i < count($units); $i++) {
       $sds = number_format((float) convertFromGram($units[$i], $seeds), 1, '.', '');
       echo "<tr><td>".$units[$i]."</td><td>".$sds."</td><td>";
       if ($rowft != '') {
          echo number_format((float) ($sds/$rowft), 1, '.', '');
       } else {
          echo "&nbsp;";
       }
       echo "</td><td>";
       echo '<input class="textbox2 mobile-input" type="text"';
       echo ' name ="calc_'.$units[$i].'" id="calc_'.$units[$i].'" value="'.$sds.
        '" style="width:100%" onkeypress="stopSubmitOnEnter(event);" oninput="update_seeds(\''.
        $units[$i].'\','.$seeds.');">';
       echo "</td></tr>";
   }
   echo "</table>";
   echo '<script type="text/javascript">update_seeds("SEEDS", '.$seeds.');</script>';
}

function fromGram($unit, $amt) {
   $res = 0;
   if ($unit == "GRAM") {
      $res = $amt;
   } else if ($unit == "OUNCE") {
      $res = $amt / 28.3495;
   } else if ($unit == "POUND") {
      $res = $amt / (28.3495 * 16);
   }
   return $res;
}

if ((isset($crop) && $crop != "" && isset($rowftToPlant) && $seeds != "" && $rowft != "" && $defUnit != "")
 || (isset($cover) && $cover != "" && isset($rate) && $rate != "" && isset($acres) && $acres != "")) {
   if (isset($crop) && $crop != "") {
      $isCover = false;
      $showCrop = $crop;
   } else {
      $isCover = true;
      $showCrop = $cover;
      $defUnit = "POUND";
   }
   echo '<br clear="all"/>';
   echo "<h3>Seed Summary</h3>";
   echo '<br clear="all"/><table><tr><td>';
   if ($isCover) {
      $needed = number_format((float) ($acres * $rate), 2, '.', '');
   } else {
      $needed = number_format((float) ($rowftToPlant * $rowft)/ convertFromGram($defUnit, $seeds),
          2, '.', '');
   }
   echo 'Total '.$showCrop.' seed needed:&nbsp; </td><td>'.$needed.'</td><td> '.$defUnit.'(S)</td></tr>';
   $inInven = 0;
   if ($isCover) {
      $sql = "select sum(inInventory) as tot from coverSeedInventory where crop = '".$cover."'";
   } else {
      $sql = "select sum(inInventory) as tot from seedInventory where crop = '".$crop."'";
   }
   $res = mysql_query($sql);
   echo mysql_error();
   if ($row = mysql_fetch_array($res)) {
      $inInven = $row['tot'];
   }
   if ($isCover) {
      $inInven = number_format((float) $inInven, 2, '.', '');
   } else {
      $inInven = number_format((float) fromGram($defUnit, $inInven), 2, '.', '');
   } 
   echo '<tr><td>Total '.$crop.' seed on hand:&nbsp;</td><td> '.$inInven.'</td><td> '.$defUnit.
      '(S)</td></tr>';
   echo "<tr><td>Quantity to order:&nbsp;</td><td> ".
      number_format((float) ($needed - $inInven), 2, '.', '')."</td><td>".
      $defUnit.'(S)</td></tr></table>';
   echo '<br clear="all"/>';
   echo '<h3>Seed Inventory</h3>';
   echo '<br clear="all"/>';
   echo "<table id='inven'>";
   echo "<tr><th>&nbsp;&nbsp;Seed&nbsp;Code&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Variety&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Purchase Year</th>";
   if ($isCover) {
      echo "<th>Acres to Plant</th>";
      echo "<th>POUND(S) To Plant</th><th>POUND(S) In Inventory</th><th>POUND(S) To Buy</th>";
      echo "<th>Delete</th></tr>";
/*
      echo "<tr id='totrow'><td>Totals:</td><td>&nbsp;</td><td>&nbsp;</td><td>";
      echo '<input class="textbox2 mobile-input" type="text" name ="totAcres" id="totAcres" style="width:100%"';
      echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>';
      echo '<input class="textbox2 mobile-input" type="text" name ="totToPlant" id="totToPlant"';
      echo ' onkeypress="stopSubmitOnEnter(event);" style="width:100%"';
      echo ' readonly value="0"></td><td>';
      echo '<input class="textbox2 mobile-input" type="text" name ="totInven" id="totInven" style="width:100%"';
      echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>';
      echo '<input class="textbox2 mobile-input" type="text" name ="totToBuy" id="totToBuy" style="width:100%"';
      echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>&nbsp';
*/
   } else {
      echo "<th>Row Feet To Plant</th><th>Seed Unit</th>";
      echo "<th>Units To Plant</th><th>Units In Inventory</th><th>Units To Buy</th>";
      echo "<th>Delete</th></tr>";
   }
   echo "<tr id='totrow'><td>Totals:</td><td>&nbsp;</td><td>";
   echo "&nbsp;</td><td>";
   echo '<input class="textbox2 mobile-input" type="text" name ="totRowFt" id="totRowFt" style="width:100%"';
   echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>';
   if (!$isCover) {
      echo "&nbsp;</td><td>";
   }
   echo '<input class="textbox2 mobile-input" type="text" name ="totToPlant" id="totToPlant"';
   echo ' onkeypress="stopSubmitOnEnter(event);" style="width:100%"';
   echo ' readonly value="0"></td><td>';
   echo '<input class="textbox2 mobile-input" type="text" name ="totInven" id="totInven" style="width:100%"';
   echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>';
   echo '<input class="textbox2 mobile-input" type="text" name ="totToBuy" id="totToBuy" style="width:100%"';
   echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>&nbsp;';
   echo "</td></tr></table>";
   echo "<script type='text/javascript'>";
   if ($isCover) {
      $sql = "select * from coverSeedInventory where crop = '".$cover."'";
   } else {
      $sql = "select * from seedInventory where crop = '".$crop."'";
   }
   $res = mysql_query($sql);
   echo mysql_error();
   $colorArray = array('FFE5CC','FFCC99', 'FFB266', 'FF9933', 'FF8000', 'CC6600', '994C00');
   while ($row = mysql_fetch_array($res)) {
      if ($isCover) {
         echo "addRowInven('".$row['code']."', '".$row['variety']."', ".$row['acres'].", '',".
           number_format((float) ($row['acres'] * $rate), 2, '.', '').
           ", ".number_format((float) $row['inInventory'], 2, '.', '').", ".
           $row['year'].");";
      } else {
         echo "addRowInven('".$row['code']."', '".$row['variety']."', ".$row['rowFt'].", '".$defUnit."', ".
           number_format((float) ($row['rowFt'] * $rowft)/ convertFromGram($defUnit, $seeds), 2, '.', '').
           ", ".number_format((float) fromGram($defUnit, $row['inInventory']), 2, '.', '').", ".
           $row['year'].");";
      }
   }
   echo "</script>";
   // echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" id="updateInven" name="updateInven"';
   echo ' value="Update Inventory" onclick="return show_inven_confirm();">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<table>';
// function addRowInven(code, variety, rowft, defUnit, toPlant, inInventory, sYear) {
   if ($isCover) {
      echo "<tr><th>Variety</th><th>Acres To Plant</th>";
      echo "<th>POUND(S) In Inventory</th>";
   } else {
      echo "<tr><th>Variety</th><th>Row Feet To Plant</th><th>Seed Unit</th>";
      echo "<th>Units In Inventory</th>";
   }
   echo "<th>Purchase Year</th><th>Organic?</th></tr>";
   echo "<tr><td><div id='vardiv' class='styled-select'> <select name='var' id='var' class='mobile-select'>";
   if ($isCover) {
      $sql = "select variety from coverVariety where crop = '".$cover."' order by variety";
   } else {
      $sql = "select variety from variety where crop = '".$crop."' order by variety";
   }
   $res = mysql_query($sql);
   echo mysql_error();
   echo "<option value='0' disabled>Variety</option>";
   while ($row = mysql_fetch_array($res)) {
      echo "<option value='".$row['variety']."'>".$row['variety']."</option>";
   }
   echo "</select></div></td><td>";
   echo '<input class="textbox2 mobile-input" type="text" name ="varRowFt" id="varRowFt" value=0 ';
   echo 'style="width:100%" onkeypress="stopSubmitOnEnter(event);">';
   if (!$isCover) {
      echo '</td><td>'.$defUnit;
   }
   echo '</td><td>';
   echo '<input class="textbox2 mobile-input" type="text" name ="varInven" id="varInven" value=0 ';
   echo 'style="width:100%" onkeypress="stopSubmitOnEnter(event);">';
   echo '</td><td><div id="yeardiv" class="styled-select"><select name="varYear"';
   echo ' id="varYear" class="mobile-select">';
   $curYear = strftime("%Y");
   for ($j = $curYear - 4; $j <= $curYear + 1; $j++) {
      echo "<option value='".$j."'";
      if ($j == $curYear) {
         echo " selected";
      }
      echo ">".$j."</option>";
   }
   echo "</select></div></td><td><div id='orgdiv' class='styled-select'><select name='varOrg' id='varOrg' class='mobile-select'>";
   echo "<option value='1' selected>OG</option><option value='0'>UT</option>";
   echo '</select></div></td></tr></table>';
   echo '<input type="button" class="submitbutton" id="addInven" name="addInven"';
   echo ' value="Add To Inventory" onclick="return add_inven(\''.$defUnit.'\');">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<label for="newVar">New Variety:&nbsp;</label>';
   echo '<input class="textbox3 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="newVar"'.
     ' id="newVar">';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" id="addVar" name="addVar"';
   echo ' value="Add New Variety" onclick="return show_var_confirm();">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<h3>Seed Order</h3>';
   echo '<br clear="all"/>';
   echo "<table id='order'>";
   $unt = "Default Units";
   if ($isCover) {
      $unt = "Pounds";
   }
   echo "<tr><th>Variety</th><th>Source</th><th>Catalog #</th><th>Organic?</th><th>Catalog Unit</th>";
   echo "<th>Price Per Catalog Unit</th><th>".$unt." Per Catalog Unit</th>";
   echo "<th>Number of Catalog Units Ordered</th><th>".$unt." Ordered</th><th>Total&nbsp;Price</th>";
   echo "<th>Order Status</th>";
   echo "<th>Delete</th><th>Add To Inventory</th>";
   echo "<th>Source 1</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;Searched&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
   echo "<th>Source 2</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;Searched&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
   echo "<th>Source 3</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;Searched&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
   echo "</tr>";
   echo "<tr id='totorderrow'><td>Totals:</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
   echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp</td><td>";
   echo '<input class="textbox2 mobile-input" type="text" name ="totUnits" id="totUnits" style="width:100%"';
   echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>';
   echo '<input class="textbox2 mobile-input" type="text" name ="totPrice" id="totPrice" style="width:100%"';
   echo ' readonly value="0" onkeypress="stopSubmitOnEnter(event);"></td><td>&nbsp;</td>';
   echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
   echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
   echo "</table>";
   echo "<script type='text/javascript'>";
   if ($isCover) {
      $sql = "select * from coverOrderItem where crop = '".$cover."' and status <> 'ARRIVED' and year = ".
          $year;
   } else {
      $sql = "select * from orderItem where crop = '".$crop."' and status <> 'ARRIVED' and year = ".
          $year;
   }
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      echo "addRowOrder('".$row['variety']."', '".$row['source']."', '".$row['catalogOrder']."', ".
        $row['organic'].", '".$row['catalogUnit']."', ".$row['price'].", ".$row['unitsPerCatUnit'].
        ", ".$row['catUnitsOrdered'].", '".$row['status']."', '".$defUnit."', '".
        $row['source1']."', '".$row['sdate1']."', '".
        $row['source2']."', '".$row['sdate2']."', '".
        $row['source3']."', '".$row['sdate3']."');";
   }
   echo "</script>";
   echo '<input type="button" class="submitbutton" id="addrow_order" name="addrow_order"';
   echo ' value="Add Order Row" onclick="addRowOrder(\'\', \'\', \'\', 1, \'\', 0, 0, 0,
        \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\');">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" id="update_order" name="update_order"';
   echo ' value="Update Order" onclick="return show_order_confirm();">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<label for="newSource">New Source:&nbsp;</label>';
   echo '<input class="textbox3 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="newSource"'.
     ' id="newSource">';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" id="addSource" name="addSource"';
   echo ' value="Add New Source" onclick="return show_source_confirm();">';
}

?>
</form>

