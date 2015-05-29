<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>


<script type="text/javascript">
function addInput() {
   xmlhttp = new XMLHttpRequest();
   var sprayMaterial = encodeURIComponent(document.getElementById("spraymaterial").value);
   xmlhttp.open("GET", "update_spray_material.php?spraymaterial="+sprayMaterial, false);
   xmlhttp.send();
   if (xmlhttp.responseText == "\n") {
      
   }
   var js_array = eval(xmlhttp.responseText);
   var thediv = document.getElementById('renamediv');
   thediv.innerHTML = '<div class="pure-control-group" id="renamediv">' +
           '<label for="rename">Rename Spray Material:</label> ' +
           '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="rename" id="rename" value="' +
           js_array[0] + '"></div>';

   thediv = document.getElementById('trateunitsdiv');
   thediv.innerHTML = '<div class="pure-control-group" id="trateunitsdiv">' +
      '<label for="trateunits">Change Tractor Rate Units:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="trateunits" id="trateunits" value="' +
      js_array[1] + '"></div>';


   thediv = document.getElementById('tratemindiv');
   thediv.innerHTML = '<div class="pure-control-group" id="tratemindiv">' +
      '<label for="tratemin">Change Minimum Tractor Rate:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="tratemin" id="tratemin" value="' +
      js_array[2] + '"></div>';


   thediv = document.getElementById('tratemaxdiv');
   thediv.innerHTML = '<div class="pure-control-group" id="tratemaxdiv">' +
      '<label for="tratemax">Change Maximum Tractor Rate:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="tratemax" id="tratemax" value="' +
      js_array[3] + '"></div>';


   thediv = document.getElementById('tratedefaultdiv');
   thediv.innerHTML = '<div class="pure-control-group" id="tratedefaultdiv">' +
     '<label for="tratedefault">Change Default Tractor Rate:</label> ' +
     '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="tratedefault" id="tratedefault" value="' +
      js_array[4] + '"></div>';

   thediv = document.getElementById('brateunitsdiv');
   thediv.innerHTML = '<div class="pure-control-group" id="brateunitsdiv">' +
      '<label for="brateunits">Change Backpack Rate Units:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="brateunits" id="brateunits" value="' +
      js_array[5] + '"></div>';


   thediv = document.getElementById('bratemindiv');
   thediv.innerHTML = '<div class="pure-control-group" id="bratemindiv">' +
      '<label for="bratemin">Change Minimum Backpack Rate:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="bratemin" id="bratemin" value="' +
      js_array[6] + '"></div>';
 

   thediv = document.getElementById('bratemaxdiv');
   thediv.innerHTML = '<div class="pure-control-group" id="bratemaxdiv">' +
      '<label for="bratemax">Change Maximum Backpack Rate:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="bratemax" id="bratemax" value="' +
      js_array[7] + '"></div>';

   thediv = document.getElementById('bratedefaultdiv');
   thediv.innerHTML = '<div class="pure-control-group" id="bratedefaultdiv">' +
      '<label for="bratedefault">Change Default Backpack Rate:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="bratedefault" id="bratedefault" value="' +
      js_array[8] + '"></div>';

   thediv = document.getElementById('reidiv');
   thediv.innerHTML = '<div class="pure-control-group" id="reidiv">' +
      '<label for="restrictedentryinterval">Change Restricted Entry Interval:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="rei" id="rei" value="' +
      js_array[9] + '"></div>';


   thediv = document.getElementById('ppediv');
   thediv.innerHTML = '<div class="pure-control-group" id="ppediv">' +
      '<label for="ppe">Change Personal Protection Equipment:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="ppe" id="ppe" value="' +
      js_array[10] + '"></div>';

   thediv = document.getElementById('activediv');
   var str = '<div class="pure-control-group" id="activediv">' +
      '<label for="active">Change Active Status:</label> ' +
      '<select name="active" id="active">';
   if (js_array[11] == 1) {
       str += '<option value="1" selected>Active</option>' +
            '<option value="0">Inactive</option>';
   } else {
       str += '<option value="1">Active</option>' +
            '<option value="0" selected>Inactive</option>';
   }
   str += '</select></div>';
   thediv.innerHTML = str;
/*
      <option value="1" selected>Active</option>
      <option value="0">Inactive</option>
   </select>
</div>
*/

/*
   if (js_array[11] == 1) {
      thediv.innerHTML = '<div id="activediv" class="styled-select">'+
         '<select name="active" id="active" class="mobile-select">'+
            '<option value="1" selected>Active</option>'+
            '<option value="0">Inactive</option>'+
         '</select></div>';
   } else {
      thediv.innerHTML = '<div id="activediv" class="styled-select">'+
         '<select name="active" id="active" class="mobile-select">'+
            '<option value="1">Active</option>'+
            '<option value="0" selected>Inactive</option>'+
         '</select></div>';
   }
*/
}
</script>

<body id= "delete">
<center>
<h2> Edit/Delete Spray Material </h>
</center>
<form name='form' class='pure-form pure-form-aligned' method='POST' action='<?php $_SERVER['PHP_SELF']?>'>

<div class="pure-control-group">
<label for="spraymaterial">Spray Material:</label>
<select name='spraymaterial' id='spraymaterial' onChange='addInput();' class='mobile-select'>
<option disabled selected></option>
<?php
$result = mysql_query("SELECT sprayMaterial from tSprayMaterials");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[sprayMaterial]\">$row1[sprayMaterial]</option>";
        }
        echo "</select></div>";
?>

<div class="pure-control-group" id="renamediv">
<label for="rename">Rename Spray Material:</label>
<input onkeypress='stopSubmitOnEnter(event)' type="text" name="rename" id="rename" class="textbox25 mobile-input" value="">
</div>


<div class="pure-control-group" id="trateunitsdiv">
<label for="trateunits">Change Tractor Rate Units:</label>
<input onkeypress='stopSubmitOnEnter(event)' type="text" name="trateunits" id="trateunits" class="textbox25 mobile-input">
</div>

<div class="pure-control-group" id="tratemindiv">
<label for="tratemin">Change Minimum Tractor Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' type="text" name="tratemin" id="tratemin" class="textbox2 mobile-input">
</div>

<div class="pure-control-group" id="tratemaxdiv">
<label for="tratemax">Change Maximum Tractor Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)' type="text" name="tratemax" id="tratemax" class="textbox2 mobile-input">
</div>

<div class="pure-control-group" id="tratedefaultdiv">
<label for="tratedefault">Change Default Tractor Rate:</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="tratedefault" id="tratedefault">
</div>

<div class="pure-control-group" id="brateunitsdiv">
<label for="brateunits">Change Backpack Rate Units:</label>
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="brateunits" id="brateunits">
</div>

<div class="pure-control-group" id="bratemindiv">
<label for="bratemin">Change Minimum Backpack Rate:</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="bratemin" id="bratemin">
</div>

<div class="pure-control-group" id="bratemaxdiv">
<label for="bratemax">Change Maximum Backpack Rate:</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="bratemax" id="bratemax">
</div>

<div class="pure-control-group" id="bratedefaultdiv">
<label for="bratedefault">Change Default Backpack Rate:</label>
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="bratedefault" id="bratedefault" class="textbox2 mobile-input">
</div>

<div class="pure-control-group" id="reidiv">
<label for="restrictedentryinterval">Change Restricted Entry Interval:</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="rei" id="rei">
</div>

<div class="pure-control-group" id="ppediv">
<label for="ppe">Change Personal Protection Equipment:</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="ppe" id="ppe">
</div>

<div class="pure-control-group" id="activediv">
<label for="active">Change Active Status:</label>
   <select name="active" id="active" class='mobile-select'>
      <option value="1" selected>Active</option>
      <option value="0">Inactive</option>
   </select>
</div>

<br clear="all"> 


<input class="submitbutton pure-button wide" name="submit" type="submit" id="submit" value="Submit">

<?php
if(!empty($_POST['submit'])) {
   $spraymaterial = escapehtml($_POST['spraymaterial']);
   $rename = escapehtml($_POST['rename']);
   $trateunits = escapehtml($_POST['trateunits']);
   $tratemin = escapehtml($_POST['tratemin']);
   $tratemax = escapehtml($_POST['tratemax']);
   $tratedefault = escapehtml($_POST['tratedefault']);
   $brateunits = escapehtml($_POST['brateunits']);
   $bratemin = escapehtml($_POST['bratemin']);
   $bratemax = escapehtml($_POST['bratemax']);
   $bratedefault = escapehtml($_POST['bratedefault']);
   $restrictedentryinterval = escapehtml($_POST['rei']);
   $protectionequipment = escapehtml($_POST['ppe']);
   $active = escapehtml($_POST['active']);

   $sql = "select * from tSprayMaterials where sprayMaterial='".$spraymaterial."'";
   $result = mysql_query($sql);
   $row = mysql_fetch_assoc($result);

   if (trim($trateunits) == "") {
      $trateunits = $row['TRateUnits']; 
   }

   if (trim($tratemin) == "") {
      $tratemin = $row['TRateMin'];
   }

   if (trim($tratemax) == "") {
      $tratemax = $row['TRateMax'];
   }   

   if (trim($tratedefault) == "") {
      $tratedefault = $row['TRateDefault'];
   }

   if (trim($brateunits) == "") {
      $brateunits = $row['BRateUnits'];
   }

   if (trim($bratemin) == "") {
      $bratemin = $row['BRateMin'];
   }

   if (trim($bratemax) == "") {
      $bratemax = $row['BRateMax'];
   }

   if (trim($bratedefault) == "") {
      $bratedefault = $row['BRateDefault'];
   }

   if (trim($restrictedentryinterval) == "") {
      $restrictedentryinterval = $row['REI_HRS']; 
   }

   if (trim($protectionequipment) ==  "") {
      $protectionequipment = $row['PPE'];
   }

   if (trim($rename) == "") {
      $rename = $spraymaterial;
   }

   $sql = "UPDATE tSprayMaterials SET sprayMaterial=upper('".$rename."'), 
      TRateUnits=upper('".$trateunits."'), TRateMin=".$tratemin.", TRateMax=".$tratemax.", TRateDefault=".$tratedefault.", 
      BRateUnits=upper('".$brateunits."'), BRateMin=".$bratemin.", BRateMax=".$bratemax.", BRateDefault=".$bratedefault.",
      REI_HRS='".$restrictedentryinterval."', PPE='".$protectionequipment."', active=".$active."
      WHERE sprayMaterial='".$spraymaterial."'";

   $query = mysql_query($sql) or die(mysql_error());

   if (!$query) {
      echo '<script> alert("Could not edit Spray Material, please try again"); </script>';
   } else {
      echo '<script> alert("Changed Spray Material successfully!"); </script>';
   }
}
?>
</form>
</body>
</html>
