<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' method='post' action='<?php $_SERVER['PHP_SELF'] ?>'>
<h3><b>Edit/Delete an Existing Field </b></h3>
<br>
<label for="fieldID"> Field ID:&nbsp; </label>
<div id="fieldID23" class="styled-select">
<!--
<select id= "fieldID" name="fieldID" class='mobile-select' onChange='addInput();addInput2();addInput3();getActive();'>
-->
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
<br clear="all"/>
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
      newdiv.innerHTML="<div id= \"sizeDiv\">" +
      '<label for="size">Size:&nbsp;</label>' +
      "<input class= \"textbox25 mobile-input\" type= \"text\" onkeyup = \"updateBeds();\" name = \"size\" id = \"size\"" + 
      'onkeypress= "stopSubmitOnEnter(event)"; value=' +
      arr[0] +">" +
      '<label style="margin-top: 8px;" for="acres">&nbsp;acres</label>' +
      "</div>";

      newdiv = document.getElementById('bedsDiv');
      newdiv.innerHTML="<div id= \"bedsDiv\"> <input onkeyup=\"updateSize();\" class=\"textbox2 mobile-input\" type=\"text\" name= \"beds\" id = \"beds\" value = " + arr[1] + "></div>";

      var activeDiv = document.getElementById("activeDiv");
      var str = '<div id="activeDiv" class="styled-select"><select ' +
        'name = "active" id = "active" class="mobile-select">';
      if (arr[3] == 1) {
         str += '<option value=1>Yes</option><option value=0>No</option>';
      } else {
         str += '<option value=0>No</option><option value=1>Yes</option>';
      }
      str += '</select></div>';
      activeDiv.innerHTML=str;

      newdiv = document.getElementById('lengthDiv');
      newdiv.innerHTML="<div id= \"lengthDiv\"> <input onkeyup=\"updateSize();\" class=\"textbox2 mobile-input\" type=\"text\" name= \"length\" id = \"length\" value = "
      + arr[2] +
     '> <label style="margin-top: 8px;" for="length">&nbsp;feet</label>' +
     "</div>";

     var width = ((arr[0] * 43560) / arr[2]).toFixed(2);
     newdiv = document.getElementById('widthDiv');
     newdiv.innerHTML = '<div id="widthDiv">' +
       '<input class="textbox25 mobile-input" type="textbox" name="width" id="width" value=' + width + 
       ' onkeyup="updateSizeWidth();">' + 
       '<label style="margin-top: 8px;" for="length">&nbsp;feet (optional)</label>' +
       '</div>';
   }
}

/*
function addInput2(){
   var newdiv = document.getElementById('sizeDiv');
   var fld = encodeURIComponent(document.getElementById("fieldID").value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getSize.php?fieldID="+fld, false);
   xmlhttp.send();

   newdiv.innerHTML="<div id= \"sizeDiv\">" +
   '<label for="size">Size:&nbsp;</label>' +
   "<input class= \"textbox2 mobile-input\" type= \"text\" onkeyup = \"updateBeds();\" name = \"size\" id = \"size\"" + 
   'onkeypress= "stopSubmitOnEnter(event)"; ' +
   xmlhttp.responseText+">" +
   '<label style="margin-top: 8px;" for="acres">&nbsp;acres</label>' +
   "</div>";
}

function addInput(){
   var newdiv = document.getElementById('bedsDiv');
   var fld = encodeURIComponent(document.getElementById("fieldID").value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getBeds.php?fieldID="+fld, false);
   xmlhttp.send();
   newdiv.innerHTML="<div id= \"bedsDiv\"> <input onkeyup=\"updateSize();\" class=\"textbox2 mobile-input\" type=\"text\" name= \"beds\" id = \"beds\" "+ xmlhttp.responseText+"></div>";
}

function getActive() {
   var fld = encodeURIComponent(document.getElementById("fieldID").value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getActive.php?fieldID="+fld, false);
   xmlhttp.send();
   var activeDiv = document.getElementById("activeDiv");
   activeDiv.innerHTML='<div id="activeDiv" class="styled-select"><select ' +
     'name = "active" id = "active" class="mobile-select">' + xmlhttp.responseText + 
     '</select></div>';
   console.log('active: ' + xmlhttp.responseText);
}

function addInput3(){
   var newdiv = document.getElementById('lengthDiv');
   var fld = encodeURIComponent(document.getElementById("fieldID").value);
   xmlhttp= new XMLHttpRequest();xmlhttp.open("GET", "getLength.php?fieldID="+fld, false);
   xmlhttp.send();
   newdiv.innerHTML="<div id= \"lengthDiv\"> <input onkeyup=\"updateSize();\" class=\"textbox2 mobile-input\" type=\"text\" name= \"length\" id = \"length\" "
      + xmlhttp.responseText +
     '> <label style="margin-top: 8px;" for="length">&nbsp;feet</label>' +
     "</div>";
}
*/

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

<label for="length">Length:&nbsp;</label>
<div id="lengthDiv">
<input class="textbox2 mobile-input" type="textbox" name="length" id="length">
<label style="margin-top: 8px;" for="length">&nbsp;feet</label>
</div>
<br clear="all"/>
<label for="width">Average Width:&nbsp;</label>
<div id="widthDiv">
<input class="textbox2 mobile-input" type="textbox" name="width" id="width">
<label style="margin-top: 8px;" for="length">&nbsp;feet (optional)</label>
</div>
<br clear="all"/>
<label for="beds">Number of Beds:&nbsp;</label> 
<div id="bedsDiv">
<input class = "textbox2 mobile-input" type="text" name = "beds" id="beds">
</div>
<br clear="all"/>
<label for="bspace">Bed spacing on center:&nbsp;</label>
<input onkeypress= "stopSubmitOnEnter(event)"; value = 60 class="textbox2 mobile-input" type="text" name="bspace" id="bspace" onkeyup = "updateSize();">
<label style="margin-top: 8px;" for="acres">&nbsp;inches</label>
<br clear="all"/>
<div id = "sizeDiv">
<label for="size">Size:&nbsp;</label>
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox2 mobile-input" type="text" name="size" id="size" onkeyup= "updateBeds();">
<label style="margin-top: 8px;" for="acres">&nbsp;acres</label>
</div>
<br clear="all"/>
<label for="active">Active:&nbsp;</label>
<div class="styled-select" id="activeDiv">
<select name="active" id="active" class='mobile-select'>
</select>
</div>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="add" id="submit" value="Update">
<br clear="all"/>
<br clear="all"/>
<!--
<script type="text/javascript">
function show_confirm() {
  var fieldID = document.getElementById('fieldID').value;
console.log(fieldID);
  if (checkEmpty(fieldID)) {
      alert('Please Select a Field');
      return false;
  } else {
     return confirm('Confirm Deletion of Field ' + fieldID + '?');
  }
}
</script>

<input class="submitbutton" type="submit" name="delete" id="Delete" value="Delete"
   onclick= "return show_confirm();">
-->
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

