<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' method='post' class="pure-form pure-form-aligned" action='<?php $_SERVER['PHP_SELF'] ?>'>
<center>
<h2>Edit/Delete an Existing Field</h2>
</center>

<script type="text/javascript"> 
function getFieldInfo() {
   var fld = encodeURIComponent(document.getElementById("fieldID").value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getFieldInfo.php?fieldID="+fld, false);
   xmlhttp.send();
   var arr = eval(xmlhttp.responseText);
   console.log(arr);
   if (arr.length == 4) {
      var newdiv = document.getElementById('sizeDiv');
      newdiv.innerHTML = '<label for="size">Size:</label> ' +
         '<input onkeypress= "stopSubmitOnEnter(event)";  value="' + arr[0] +
         '" type="text" name="size" id="size" onkeyup= "updateBeds();"> ' + 
         '&nbsp;acres';

      newdiv = document.getElementById('bedsDiv');
      newdiv.innerHTML = '<label for="beds">Number of Beds:</label>  ' +
         '<input type="text" name = "beds" id="beds" value="' + arr[1] +
         '" onkeyup="updateSize();"> ';

      var activeDiv = document.getElementById("activeDiv");
      var str = '<label for="active">Active:</label> <select ' +
        'name = "active" id = "active" class="mobile-select">';
      if (arr[3] == 1) {
         str += '<option value=1>Yes</option><option value=0>No</option>';
      } else {
         str += '<option value=0>No</option><option value=1>Yes</option>';
      }
      str += '</select>';
      activeDiv.innerHTML=str;


      newdiv = document.getElementById('lengthDiv');
      newdiv.innerHTML = '<label for="length">Length:</label> ' +
        '<input type="text" name="length" id="length" value="' +
        arr[2] + '" onkeyup="updateSize();"> &nbsp;feet';


     var width = ((arr[0] * 43560) / arr[2]).toFixed(2);
     newdiv = document.getElementById('widthDiv');
     newdiv.innerHTML = '<label for="width">Average Width:</label> ' +
         '<input type="text" name="width" id="width" ' +
         'value="' + width + '" onkeyup="updateSizeWidth();">' + 
         ' &nbsp;feet (optional)';
   }
}

function updateSize() {
   var len = parseFloat(document.getElementById('length').value);
   var beds = parseFloat(document.getElementById('beds').value);
   var bspace = parseFloat(document.getElementById('bspace').value);
   var size = len * beds /(43560 / (bspace / 12));
   if (isNaN(size)) { size = 0; }
   document.getElementById('size').value = size.toFixed(2);
}

function updateSizeWidth() {
   var len = parseFloat(document.getElementById('length').value);
   var width = parseFloat(document.getElementById('width').value);
   var size = (len * width)/43560;
   if (isNaN(size)) { size = 0; }
   document.getElementById('size').value = size.toFixed(3);
   updateBeds();
}

function updateBeds() {
   var len = parseFloat(document.getElementById('length').value);
   var bspace = parseFloat(document.getElementById('bspace').value);
   var size = parseFloat(document.getElementById('size').value);
   var cons = 43560 / (bspace / 12);
   var beds = cons * size / len;
   if (isNaN(beds)) { beds  = 0; }
   document.getElementById('beds').value = beds.toFixed(2);
}
</script>

<fieldset>

<div class="pure-control-group">
<label for="fieldID">Name of Field: </label>
<select id= "fieldID" name="fieldID" class='mobile-select' onChange='getFieldInfo();'>
<option value="0" selected disabled> Field </option>
<?php
$result = mysql_query("SELECT distinct fieldID from field_GH");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"".$row1[fieldID]."\">".
   $row1[fieldID]."</option>";
}
?>
</select></div>

<div class="pure-control-group" id="lengthDiv">
<label>Length:</label>
<input type="text" name="length" id="length">
 &nbsp;feet
</div>

<div class="pure-control-group" id="widthDiv">
<label for="width">Average Width:</label>
<input type="text" name="width" id="width">
&nbsp;feet (optional)
</div>

<div class="pure-control-group" id="bedsDiv">
<label for="beds">Number of Beds:</label> 
<input type="text" name = "beds" id="beds">
</div>

<div class="pure-control-group">
<label for="bspace">Bed spacing on center:</label>
<input onkeypress= "stopSubmitOnEnter(event)"; value = 60 type="text" name="bspace" id="bspace" onkeyup = "updateSize();">
&nbsp;inches
</div>

<div id = "sizeDiv" class="pure-control-group">
<label for="size">Size:</label>
<input onkeypress= "stopSubmitOnEnter(event)";  type="text" name="size" id="size" onkeyup= "updateBeds();">
&nbsp;acres
</div>

<div id = "activeDiv" class="pure-control-group">
<label for="active">Active:</label>
<select name="active" id="active" class='mobile-select'>
</select>
</div>
<br clear="all"/>

<input class="submitbutton pure-button wide" type="submit" name="add" id="submit" value="Update">
</fieldset>
</form>
<?php
$size = escapehtml($_POST['size']);
$beds = escapehtml($_POST['beds']);
$length = escapehtml($_POST['length']);
$id = escapehtml($_POST['fieldID']);
$active = escapehtml($_POST['active']);
if (isset($_POST['add'])) {
   if ($size > 0 && !empty($id) && $beds > 0 && $length > 0) {
      	//$deleteSql = "Delete from field_GH where fieldID ='".$_POST['fieldID']."'";
        $updateSQL = "update field_GH set size = ".$size." where fieldID = '".$id."'";
	$ures = mysql_query($updateSQL);
        echo mysql_error();
        $updateSQL = "update field_GH set length = ".$length." where fieldID = '".$id."'";
	$ures = $ures && mysql_query($updateSQL);
        echo mysql_error();
        $updateSQL = "update field_GH set numberOfBeds = ".$beds." where fieldID = '".$id."'";
	$ures = $ures && mysql_query($updateSQL);
        echo mysql_error();
        $updateSQL = "update field_GH set active = ".$active.
                " where fieldID = '".$id."'";
	$ures = $ures && mysql_query($updateSQL);
        echo mysql_error();
	//$sql="Insert into field_GH(fieldID,size,numberOfBeds,length) values (upper('".$_POST['fieldID']."'), '".$size."', '".$beds."','".$length."')";
// echo '<br>';
      // $result=mysql_query($sql);
      if (!$ures) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
      }else {
         echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      }
   } else {
      echo    "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>

