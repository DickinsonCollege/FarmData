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
$result=mysql_query("Select materialName from compost_materials");
while ($row1 =  mysql_fetch_array($result)){  
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
   console.log(str);
   newdiv.innerHTML = str;

   var newUnit = document.getElementById("unit").value;
   var inp = document.getElementById('newunit');
   var inp2 = document.getElementById('newunit2');
   inp.value = newUnit;
   inp2.value = newUnit;
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
<input class="submitbutton pure-button wide" type="submit" name="add" id="submit" value="Add">
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
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
      }else {
         echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      }
   } else {
      echo    "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>

