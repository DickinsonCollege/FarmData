<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$result = mysql_query("select materialName from compost_materials");
echo mysql_error();
$materials=array();
while ($row = mysql_fetch_array($result)) {
  $mat = $row['materialName'];
  $unitSQL = "Select distinct unit from compost_units where material='".$mat."'";
  $res2 = mysql_query($unitSQL);
  echo mysql_error();
  $units = array();
  while ($row2 = mysql_fetch_array($res2)) {
     $units[] = $row2['unit'];
  }
  $materials[$mat] = $units;
}
?>

<script type = "text/javascript">
var mat_units = eval(<?php echo json_encode($materials); ?>);
</script>

<center>
<h2>Compost Accumulation Form</h2>
</center>


<form method='post' class='pure-form pure-form-aligned' action="<?php $_PHP_SELF ?>?tab=soil:soil_fert:soil_compost:compost_accumulation">
<script type='text/javascript'>
function show_confirm() {
    var fields = ["pileID", "material", "amount", "units"];

    var m = document.getElementById("month").value;
    var con="Date: "+m+"-";
    var d = document.getElementById("day").value;
    con=con+d+"-";
    var y = document.getElementById("year").value;
    con=con+y+"\n";
    var p = document.getElementById("pileID").value;
    if (checkEmpty(p)) {
       alert("Please Enter Pile ID");
       return false;
    }
    con=con+"Pile ID: " + p +"\n\n";
    var numMaterials = document.getElementById("numMaterials").value;
    if (numMaterials < 1) {
       alert("Please Select at Least One Material");
       return false;
    }
    for (i = 1; i <= numMaterials; i++) {
        var mat = document.getElementById("material"+i).value;
        var amt = document.getElementById("amount"+i).value;
        var unit = document.getElementById("units"+i).value;
        if (checkEmpty(mat)) {
          alert("Please Select Material in Row " + i);
          return false;
        }
        if (checkEmpty(amt)) {
          alert("Please Enter Amount in Row " + i);
          return false;
        }
        if (checkEmpty(unit)) {
          alert("Please Select Unit in Row " + i);
          return false;
        }
        con += "Material " + i + ": " + mat + ", " + amt + " " + unit + "(s)\n";
    }

    return confirm("Confirm Entry:"+"\n"+con);
 }

function getUnits(num) {
  var material = document.getElementById('material' + num).value;
  material = escapeHtml(material);
console.log(material);
  var unitDiv = document.getElementById('unitsDiv' + num);
  opts = "";
  units = mat_units[material];
  for (i = 0; i < units.length; i++) {
     unit = units[i];
     opts += '<option value = "' + unit + '">' + unit + '</option>';
  }
  unitDiv.innerHTML = "<div id='unitsDiv" + num + "'>" + 
     "<select name ='units" + num + "' id='units" + num + 
     "' class='wide'>" + 
     "<option value=0 selected disabled>Unit</option>" +
     opts + "</select></div>";
}

function addMaterialToTable() {
     var numMaterialsInput = document.getElementById("numMaterials");
     numMaterialsInput.value++;
     var numMaterials = numMaterialsInput.value;

/*
     var tbl = document.getElementById("materialsTable");
     var row = tbl.insertRow(-1);
*/
     var tbl = document.getElementById("materialsTable").getElementsByTagName('tbody')[0];
      var row = tbl.insertRow(-1);


     // Material
     var cell = row.insertCell(0);

     var cellHTML = "";
     cellHTML +=  "<select name='material" + numMaterials + "' id='material" + 
           numMaterials + "' class='wide' onChange='getUnits(" + numMaterials + ");'>" + 
          "<option value=0 selected disabled>Material</option>";

          <?php
          $result = mysql_query("select materialName from compost_materials");
          while ($row = mysql_fetch_array($result)) {
               echo "cellHTML+= \"<option value='".$row['materialName']."'>".
                 $row['materialName']."</option>\";";
          }
          ?>

     cellHTML += "</select>";
     cell.innerHTML = cellHTML;

     // Amount
     var cell = row.insertCell(1);

     var cellHTML = "";
     cellHTML += "<input onkeypress='stopSubmitOnEnter(event)' type='text' id='amount" + numMaterials + "' name='amount" + numMaterials + "' class='textbox2 mobile-input wide' value=0>";

     cell.innerHTML = cellHTML;

     // Unit
     var cell = row.insertCell(2);
     
     var cellHTML = "";
     cellHTML += "<div id='unitsDiv" + numMaterials + "'>" + 
          "<select name ='units" + numMaterials + "' id='units" + numMaterials + "' class='wide'>" + 
          "<option value=0 selected disabled>Unit</option>";
     cellHTML += "</select></div>";
     cell.innerHTML = cellHTML;
}

function removeMaterialFromTable() {
     var numMaterialsInput = document.getElementById("numMaterials");
     var numMaterials = numMaterialsInput.value;
     
     if (numMaterials >= 1) {
          numMaterialsInput.value--;

          var tbl = document.getElementById("materialsTable");
          tbl.deleteRow(numMaterials);
     }
}
</script>


<div class="pure-control-group">
<label for="Seed">Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for="pileIDlabel">Pile ID:</label>
<select name ="pileID" id="pileID" class='mobile-select'>
<option value = 0 selected disabled> Pile ID</option>
<?php
$result=mysql_query("Select pileID from compost_pile where active=1");
while ($row1 =  mysql_fetch_array($result)){
     echo "\n<option value= '".$row1[pileID]."'>".$row1[pileID]."</option>";
}
echo '</select>';
echo '</div>';
?>

<br clear="all"/>
<table id="materialsTable" class="pure-table pure-table-bordered">
<thead><tr>
<th>
Materials
</th>
<th>
Amount
</th>
<th>
Unit:
</th>
</tr></thead>
<tbody></tbody>
</table>
<input type='hidden' id='numMaterials' name='numMaterials' value=0>
<br clear="all">
<div class="pure-g">
<div class="pure-u-1-2">
<input type='button' class='genericbutton pure-button wide' id='addMaterial' name='addMaterial' onclick='addMaterialToTable();' value='Add Material'>
</div>
<div class="pure-u-1-2">
<input type='button' class='genericbutton pure-button wide' id='removeMaterial' name='removeMaterial' onclick='removeMaterialFromTable();' value='Remove Material'>
</div>
</div>
<script type="text/javascript">
addMaterialToTable();
</script>
<br clear='all'>
<!--
<?php if ($_SESSION['mobile']) echo "<div style='margin-top:100px'></div>"; ?>
-->

<div class="pure-control-group">
<label for="comments">Comments:</label>
<textarea name="comments"rows="5" cols="30">
</textarea>
</div>


<br clear="all"/>
<br clear="all"/>

<div class="pure-g">
<div class="pure-u-1-2">
<input onclick="return show_confirm();" type="submit" class = "submitbutton pure-button wide" name="submit" id="submit" value="Submit">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "/Soil/compostReport.php?tab=soil:soil_fert:soil_compost:compost_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table"></form>
</div>
</div>


<?php
if (isset($_POST['submit'])) {
     $pileID = escapehtml($_POST['pileID']);
     $comments = escapehtml($_POST['comments']);
     $year = $_POST['year'];
     $month = $_POST['month'];
     $day = $_POST['day'];
     $date = $year."-".$month."-".$day;
     $numMaterials = $_POST['numMaterials'];

     for ($i = 1; $i <= $numMaterials; $i++) {
          $material = escapehtml($_POST["material".$i]);
          $amount = escapehtml($_POST["amount".$i]);
          $units = escapehtml($_POST["units".$i]);
          $convSQL = "select pounds, cubicyards from compost_units where ".
             "material = '".$material."' and unit = '".$units."'";
          $result = mysql_query($convSQL); 
          echo mysql_error();
          while ($row1 =  mysql_fetch_array($result)){
             $pounds = $row1['pounds'];
             $cf = $row1['cubicyards'];
          }
          $sql="INSERT INTO compost_accumulation (accDate, pileID, material, pounds, cubicyards, comments) 
               VALUES ('".$date."', '".$pileID."', '".$material."', ".
               ($amount * $pounds).", ".($amount * $cf).", '".$comments."')";
        $result = mysql_query($sql);
          if (!$result) break;
     }

   if(!$result) { 
      echo "<script> alert(\"Could not enter Compost Accumulation Data! Try again.\\n ".mysql_error()."\n\"); </script>";
   }else {
      echo "<script> showAlert(\"Compost Accumulation Record Entered Successfully\"); </script>";
   }
}
?>
