<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="add">
<form name='form' method='post' action='<?php $_SERVER['PHP_SELF']; ?>'>
<h1><b>Add Unit to Compost Material </b></h1>
<label for="crop">Material:&nbsp;</label>
<div class="styled-select">
<select name="mat" id="mat" onChange="addInput2();" class="mobile-select"> 
<option value=0 selected disabled>Material&nbsp; </option>
<?php
$result=mysql_query("Select materialName from compost_materials");
while ($row1 =  mysql_fetch_array($result)){  
  echo "\n<option value= \"$row1[materialName]\">$row1[materialName]</option>";
}
?>
</select>
</div>
<br clear="all"/>

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
   newdiv.innerHTML="<div id= \"units\"> <select class='mobile-select' name= \"unit\" id = \"unit\" onchange=\"updateNew();\"> "+ xmlhttp.responseText+"</div>";
   var newUnit = document.getElementById("unit").value;
   var inp = document.getElementById('newunit');
   var inp2 = document.getElementById('newunit2');
   inp.value = newUnit;
   inp2.value = newUnit;
}

</script>

<label for="unit">New Unit:&nbsp;</label> 
<div id="units"class="styled-select">
<select name="unit" id="unit" class="mobile-select">
<option disabled value=0 selected>Unit&nbsp; </option>
</select>
</div>
<br clear="all"/>
<label for="default">Conversion Factors for New Unit:&nbsp;</label>
<br clear="all"/>
<label>One&nbsp; </label>
<input class="textbox3 mobile-input" type="textbox" readonly name="newunit" id="newunit">
<label>&nbsp;equals&nbsp;</label>
<input class="textbox3 mobile-input" type="textbox" name="conversion" id="conversion">
<label>&nbsp;pounds</label>
<br clear="all"/>
<label>One&nbsp; </label>
<input class="textbox3 mobile-input" type="textbox" readonly name="newunit2" id="newunit2">
<label>&nbsp;equals&nbsp;</label>
<input class="textbox3 mobile-input" type="textbox" name="conversion2" id="conversion2">
<label>&nbsp;cubic yards</label>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="add" id="submit" value="Add">

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
echo "</form>";
# echo '<br clear="all"/>';
echo '<form method="POST" action = "viewCompostUnits.php?tab=admin:admin_add:admin_addsoil:addcompost:addcompostconv"><input type="submit" class="submitbutton" value = "View Units Table"></form>';
?>

