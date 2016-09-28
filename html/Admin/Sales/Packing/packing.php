<?php session_start();
echo "<html>";
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Sales/convert.php';
// $sql = "select crop from plant where active = 1 union select product as crop from product";
$sql = "select crop from (select crop from plant where active = 1 union ".
       "select product as crop from product where active = 1) as tmp order by crop";
$res = $dbcon->query($sql);
$crops = array();
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
   array_push($crops, $row['crop']);
}
$units = array();
$sql = "select crop from plant where active=1";
$res = $dbcon->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
  $sql2 = "select unit from units where crop='".$row['crop']."'";
  $ures = $dbcon->query($sql2);
  $unit = array();
  $i = 0;
  while ($urow = $ures->fetch(PDO::FETCH_ASSOC)) {
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
$res = $dbcon->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
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
console.log(xmlhttp.responseText);
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
      // sum += Number(document.getElementById(targs[i].replace(/ /g, "_") + row).value);
      sum += Number(document.getElementById(encodeURIComponent(targs[i]) + row).value);
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
        " <input type='text' size='5' style='width:100%;'";
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
      //var targ = targs[i].replace(/ /g, "_");
      var targ = encodeURIComponent(targs[i]);
      htm = "<div id='" + targ + rowNum + "div' name='" + targ + rowNum + "div'>" +
        " <input type='text' style='width:100%;' size='5' ";
      if (crp != "") {
         if (crpArr[targs[i]]) {
            htm += " value = '" + parseFloat(crpArr[targs[i]]).toFixed(1) + "' ";
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
   cell.innerHTML = "<input type='button' class='deletebutton pure-button wide' value='Delete'" +
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
            // var targ = targs[i].replace(/ /g, "_");
            var targ = encodeURIComponent(targs[i]);
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
<form name='form' class = 'pure-form pure-form-aligned' method='post' action="<?php $_PHP_SELF ?>">
<input type="hidden" name="rowNum" id="rowNum" value=0>
<center><h2> Packing Input Table </h2></center>
<div class = 'pure-control-group'>
<label for="selectListDiv"> Harvest List Date:</label>
<select id="selectList" name="selectList" class="mobile-select" >
<?php
$sql = "SELECT id, harDate FROM harvestList WHERE harDate > SUBDATE(CURDATE(), 30) order by harDate desc";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value='".$row['id']."'>".$row['harDate']."</option>";
}
?>
</select>
</div>
<input type="button" class="genericbutton pure-button" id="populateFromHarvestList" name="populateFromHarvestList" value="Populate Entries From Harvest List" onclick="populateEntries();">
<br clear='all'>
<br clear='all'>

<div class = 'pure-control-group'>
<label>Packing Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class = 'pure-control-group'>
<label>&nbsp;&nbsp;&nbsp;Bring Back?:</label>
<input type='checkbox' id='bringBack' name='bringBack' class='largecheckbox'>
</div>
<br clear='all'>

<table class = "pure-table pure-table-bordered" id="packTable">
<thead><tr><th>Crop/Prod</th><th>Grade</th><th>&nbsp;&nbsp;Unit&nbsp;&nbsp;</th><th>Avail</th>
<?php
for ($i = 0; $i < count($targs); $i++) {
   echo "<th>".$targs[$i]."</th>";
}
?>
<th>Total</th><th>Delete</th></tr></thead>
</table>
<br clear="all"/>

<input type="button" class = "submitbutton pure-button" value = "Add Row" onclick="addRow('', '');">
<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton pure-button wide" id="insertRows" name="insertRows" 
 value="Submit Data to Database" onclick="return show_confirm();">
</form>

<script type="text/javascript">
window.onload=function() {
   var wid = window.innerWidth || document.body.clientWidth;
   var min = 1500;
   if (wid < min) {
      document.getElementById("packTable").style.width=min;
   }
}
</script>

<?php
if (isset($_POST['insertRows'])) {
   $rowNum = $_POST['rowNum'];
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $date = $year.'-'.$month.'-'.$day;
   $result = 1;
   try {
      $sql = "insert into pack(packDate, crop_product, grade, amount, unit, comments, bringBack,".
                 " Target) values('".$date."', :crop, :grade, :amt, :def_unit, :comments, :bring, :targ)";
      $stmt = $dbcon->prepare($sql);
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
               // $targr = str_replace(" ", "_", $targ);
               $targr = encodeURIComponent($targ);
               $val = $_POST[$targr.$i];
               if ($val > 0) {
   /*
                  $sql = "insert into pack(packDate, crop_product, grade, amount, unit, comments, bringBack,".
                     " Target) values('".$date."', '".$crop."', ".$grade.", ".$val.", '".$unit."', '".
                     $comments."', ".$bring.", '".$targ."')";
   */
/*
                  $sql = "insert into pack(packDate, crop_product, grade, amount, unit, comments, bringBack,".
                  " Target) values('".$date."', '".$crop."', ".$grade.", ".$val / $conversion[$crop][$unit].
                     ", '".$default_unit[$crop]."', '".$comments."', ".$bring.", '".$targ."')";
*/
                  $stmt->bindParam(':crop', $crop, PDO::PARAM_STR);
                  $stmt->bindParam(':grade', $grade, PDO::PARAM_INT);
                  $amt = $val / $conversion[$crop][$unit];
                  $stmt->bindParam(':amt', $amt, PDO::PARAM_STR);
                  $stmt->bindParam(':def_unit', $default_unit[$crop], PDO::PARAM_STR);
                  $stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
                  $stmt->bindParam(':bring', $bring, PDO::PARAM_INT);
                  $stmt->bindParam(':targ', $targ, PDO::PARAM_STR);
                  $stmt->execute();
               }
            }
         }
      }
   } catch (PDOException $p) {
      phpAlert("Could not enter packing record", $p);
      die();
   }
   echo "<script>alert(\"Entered data successfully!\");</script> \n";
}
?>

