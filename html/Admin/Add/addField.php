<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<script type="text/javascript">

function updateSize() {
  var len = parseFloat(document.getElementById('length').value);
  var beds = parseFloat(document.getElementById('beds').value);
  var bspace = parseFloat(document.getElementById('bspace').value);
  var size = len * beds /(43560 / (bspace / 12));
  if (isNaN(size)) { size = 0; }
  document.getElementById('size').value = size.toFixed(2);
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


function show_confirm() {
  var fld = document.getElementById('fieldID').value;
  var len = document.getElementById('length').value;
  var beds = document.getElementById('beds').value;
  var bspace = document.getElementById('bspace').value;
  if (checkEmpty(fld)) {
     alert("Enter a field identifier!");
     return false;
  } else if (checkEmpty(len) || !isFinite(len) || len <= 0) {
     alert("Enter a valid length!");
     return false;
  } else if (checkEmpty(beds) || !isFinite(beds) || beds <= 0) {
     alert("Enter a valid number of beds!");
     return false;
  } else if (checkEmpty(bspace) || !isFinite(bspace) || bspace <= 0) {
     alert("Enter a valid bed spacing!");
     return false;
  }
  return true;
}
</script>


<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h3 class="hi"><b>Add Field</b></h3>
<br clear="all"/>
<label for="fieldID">FieldID:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input" type="text" name="fieldID" id="fieldID">
<?php
echo '<br clear="all"/>';
echo'<label for="length">Length:&nbsp;';
if ($_SESSION['mobile']) echo "(feet)";
echo '</label>';
echo'<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox2 mobile-input" type="text" name="length" id="length" onkeyup="updateSize();updateBeds();">';
if (!$_SESSION['mobile']) echo'<label style="margin-top: 8px;" for="acres">&nbsp;feet</label>'; 
echo '<br clear="all"/>';

echo'<label for="bspace">Bed spacing on center:&nbsp;';
if ($_SESSION['mobile']) echo "(inches)";
echo "</label>";
echo'<input onkeypress= "stopSubmitOnEnter(event)"; value = 60 class="textbox2 mobile-input" type="text" name="bspace" id="bspace" onkeyup = "updateSize();updateBeds();">';
if (!$_SESSION['mobile']) echo'<label style="margin-top: 8px;" for="acres">&nbsp;inches</label>'; 
echo '<br clear="all"/>';

echo'<label for="size">Size:&nbsp;';
if ($_SESSION['mobile']) echo "(acres)";
echo '</label>'; 
echo'<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox2 mobile-input" type="text" name="size" id="size" onkeyup = "updateBeds();">';
if (!$_SESSION['mobile']) echo'<label style="margin-top: 8px;" for="acres">&nbsp;acres</label>'; 
echo '<br clear="all"/>';

echo'<label for="beds">Number of beds:&nbsp;</label>'; 
echo'<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox2 mobile-input" type="text" name="beds" id="beds" onkeyup="updateSize();">';

echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo'<input class="submitbutton" type="submit" name="add" value="Add" onclick = "return show_confirm();">';
echo "<br>";
if (isset($_POST['add'])) {
   $fieldID = escapehtml(strtoupper($_POST['fieldID']));
   $size = escapehtml($_POST['size']);
   $beds = escapehtml($_POST['beds']);
   $length = escapehtml($_POST['length']);
   $sql="insert into field_GH(fieldID,size,numberOfBeds, length, active) values ('".$fieldID."', ".
      $size.", ".$beds.", ".$length.",1)";
   $result=mysql_query($sql);
   if (!$result) {
      echo "<script>alert(\"Could not add field: Please try again!\\n".mysql_error()."\");</script> \n";
   } else {
      echo "<script>showAlert(\"Added field successfully!\");</script> \n";
   }
}

