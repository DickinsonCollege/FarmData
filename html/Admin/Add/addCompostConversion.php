<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="add">
<form name='form' class = "pure-form pure-form-aligned" method='post' action='<?php $_SERVER['PHP_SELF']; ?>'>
<center><h2><b>Add Unit to Compost Material </b></h2></center>

<div class = "pure-control-group">
<label for="crop">Material:</label>
<select name="mat" id="mat" onChange="addInput2();" class="mobile-select"> 
<option value=0 selected disabled>Material </option>
<?php
$result=$dbcon->query("Select materialName from compost_materials");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){  
  echo "\n<option value= \"$row1[materialName]\">$row1[materialName]</option>";
}
?>
</select>
</div>

<script type="text/javascript"> 
function updateNew() {
   var newUnit = document.getElementById("unit").value;
   var inp = document.getElementById('newunit');
   var inp2 = document.getElementById('newunit2');
   inp.value = newUnit;
   inp2.value = newUnit;
}

function addInput2(){
   var newdiv = document.getElementById('units');
   var mat = document.getElementById("mat").value;
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "compostUnit.php?material="+mat, false);
   xmlhttp.send();
   var str = '<label for="unit">New Unit:</label> ' +
      '<select name="unit" id="unit" onchange="updateNew();"> ' + 
      xmlhttp.responseText + '</select>';
   newdiv.innerHTML = str;

   var newUnit = document.getElementById("unit").value;
   var inp = document.getElementById('newunit');
   var inp2 = document.getElementById('newunit2');
   inp.value = newUnit;
   inp2.value = newUnit;
}

function show_confirm() {
   var i = document.getElementById("mat").value;
   if (checkEmpty(i)) {
      alert("Select Compost Material");
      return false;
   }
   var con="Compost Material: "+ i + "\n";
   var i = document.getElementById("newunit").value;
   if (checkEmpty(i)) {
      alert("Select New Unit");
      return false;
   }
   con += "New Unit: "+ i + "\n";
   var i = document.getElementById("conversion").value;
   if (checkEmpty(i) || !isFinite(i) || i < 0) {
      alert("Enter Valid Conversion to Pounds");
      return false;
   }
   con += "Conversion to Pounds: "+ i + "\n";
   var i = document.getElementById("conversion2").value;
   if (checkEmpty(i) || !isFinite(i) || i < 0) {
      alert("Enter Valid Conversion to Cubic Yards");
      return false;
   }
   con += "Conversion to Cubic Yards: "+ i + "\n";
   return confirm("Confirm Entry: " + "\n" + con);
}
</script>
<div class = "pure-control-group" id="units">
<label for="unit">New Unit:</label> 
<select name="unit" id="unit" class="mobile-select">
<option disabled value=0 selected>Unit</option>
</select>
</div>

<div class = "pure-control-group">
<label for="default">Conversion Factors for New Unit:</label>
One&nbsp; 
<input type="text" size="12" readonly name="newunit" id="newunit">
&nbsp;equals&nbsp;
<input type="text" size = "5" name="conversion" id="conversion">
&nbsp;pounds
</div>
<div class = "pure-control-group">
<label>&nbsp;</label>
One&nbsp;
<input readonly size="12" name="newunit2" id="newunit2">
&nbsp;equals&nbsp;
<input size="5" type="text" name="conversion2" id="conversion2">
&nbsp;cubic yards
<br clear="all"/>
<br clear="all"/>
<br clear="all"/>
<div class = "pure-g">
<div class = "pure-u-1-2">
<input class="submitbutton pure-button wide" type="submit" name="add" id="submit" value="Add"
  onClick="return show_confirm();">
</form>
</div>
<div class = "pure-u-1-2">
<form method="POST" action = "viewCompostUnits.php?tab=admin:admin_add:admin_addsoil:addcompost:addcompostconv">
<input type="submit" class="submitbutton pure-button wide" value = "View Units Table"></form>
</div>
</div>

<?php
if (isset($_POST['add'])) {
   $conversion = escapehtml($_POST['conversion']);
   $conversion2 = escapehtml($_POST['conversion2']);
   $unit = escapehtml($_POST['unit']);
   $mat = escapehtml($_POST['mat']);
   $default_unit = escapehtml($_POST['default_unit']);
   if ($conversion > 0 && !empty($unit) && !empty($conversion)
      && $conversion2 > 0 && !empty($conversion2) && !empty($mat)) {
      $sql="Insert into compost_units(material,unit,pounds,cubicyards) values ('".
         $mat."','".$unit."',".$conversion.", ".$conversion2.")";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert("Could not add conversion", $p);
         die();
      }
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   } else {
      echo    "<script>alert(\"Enter all data!\");</script> \n";
   }
}
?>

