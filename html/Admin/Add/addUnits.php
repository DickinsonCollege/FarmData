<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="add">
<form name='form' method='post' action='<?php $_SERVER['PHP_SELF']; ?>'>
<h3><b>Add Units to a Crop </b></h3>
<br>
<label for="crop">Crop:&nbsp;</label>
<div class="styled-select">
<select name="crop" id="crop" onChange="addInput(); addInput2();" class="mobile-select"> 
<option value=0 selected disabled>Crop&nbsp; </option>
<?php
$result=mysql_query("Select crop from plant");while ($row1 =  mysql_fetch_array($result)){  echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
<br clear="all"/>

<script type="text/javascript"> 
function updateNew() {
   var newUnit = document.getElementById("unit").value;
   var inp = document.getElementById('newunit');
   inp.value = newUnit;
}

function addInput2(){
   var newdiv = document.getElementById('units');
   var crop = encodeURIComponent(document.getElementById("crop").value);
   xmlhttp= new XMLHttpRequest();xmlhttp.open("GET", "unitsU.php?crop="+crop, false);
   xmlhttp.send();
   //console.log(xmlhttp.responseText);
   newdiv.innerHTML="<div id= \"units\"> <select class='mobile-select' name= \"unit\" id = \"unit\" onchange=\"updateNew();\"> "+ xmlhttp.responseText+"</div>";
   var newUnit = document.getElementById("unit").value;
   var inp = document.getElementById('newunit');
   inp.value = newUnit;
}

function addInput(){
   var newdiv = document.getElementById('default');
   var crop = encodeURIComponent(document.getElementById("crop").value);
   xmlhttp= new XMLHttpRequest();xmlhttp.open("GET", "addUpdate.php?crop="+crop, false);
   xmlhttp.send();
   newdiv.innerHTML="<div id= \"default\"> <input class=\"textbox3 mobile-input\" type=\"text\" name= \"default_unit\" id = \"default_unit\" readonly=\"readonly\" value = \""+ xmlhttp.responseText+"\"> </div>";
/*
   var inp = document.getElementById('defaultunit');
   inp.value = decodeURIComponent(xmlhttp.responseText);
*/
   var inp = document.getElementById('default2');
   inp.innerHTML="<div id= \"default2\"> <input class=\"textbox3 mobile-input\" type=\"text\" name= \"defaultunit\" id = \"defaultunit\" value = \""+ xmlhttp.responseText+"\"> </div>";
}
</script>

<label for="Conversion"> Default Unit:&nbsp;</label> <div id="default"> 
<input class="textbox3 mobile-input" readonly="readonly" type="text" name="default_unit" id="default_unit"> </div> 
<br clear="all"/>
<label for="unit">New Unit:&nbsp;</label> 
<div id="units"class="styled-select">
<select name="unit" id="unit" class="mobile-select">
<option disabled value=0 selected>Unit&nbsp; </option>
</select>
</div>
<br clear="all"/>
<label for="default">Conversion Factor from New Unit:&nbsp;</label>
<br clear="all"/>
<label>One&nbsp; </label>
<div id = "default2">
<input class="textbox3 mobile-input" type="textbox" readonly name="defaultunit" id="defaultunit">
</div>
<label>&nbsp;equals&nbsp;</label>
<input class="textbox3 mobile-input" type="textbox" name="conversion" id="conversion">
<label>&nbsp;</label>
<input class="textbox3 mobile-input" type="textbox" readonly name="newunit" id="newunit">
<label>(s)</label>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="add" id="submit" value="Add">
<script>
        function show_confirm() {
/**        var i = document.getElementById("name").value;
        var con="Crop: "+ i+ "\n";
	var i = document.getElementById("defunit");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+"Default Unit: "+ strUser3+ "\n";
	var i = document.getElementById("unit");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+"Unit: "+ strUser3+ "\n";
	var i = document.getElementById("conversion").value;
        var con=con+"Conversion Factor: "+ i+ "\n";

	return confirm("Confirm Entry:"+"\n"+con);
**/

}

	</script>

<?php
if (isset($_POST['add'])) {
   $conversion = escapehtml($_POST['conversion']);
   $unit = escapehtml($_POST['unit']);
   $crop = escapehtml($_POST['crop']);
   $default_unit = escapehtml($_POST['default_unit']);
   if ($conversion > 0 && !empty($unit) && !empty($conversion)) {
      $sql="Insert into units(crop,default_unit,unit,conversion) values ('".$crop."','".$default_unit."','".$unit."','".$conversion."')";
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
echo '<form method="POST" action = "viewUnits.php?tab=admin:admin_add:admin_addcrop:addunit"><input type="submit" class="submitbutton" value = "View Units Table"></form>';
?>

