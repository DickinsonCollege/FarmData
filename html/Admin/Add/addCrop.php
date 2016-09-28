<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class="pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center>
<h2>Add New Crop</h2>
</center>
<div class="pure-control-group">
<label>Crop Name:</label> 
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3 mobile-input" type="text" name="name" id="name">
</div>

<div class="pure-control-group">
<label for="default">Default Unit:</label> 
<select name="default_unit" id="default_unit" class='mobile-select'>
<option value=0 selected>Unit </option>
<?php
$sql = "select distinct unit from extUnits";
$result=$dbcon->query($sql);
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= \"$row1[unit]\">$row1[unit]</option>";
}
?>
</select>
</div>

<?php
if ($_SESSION['sales_invoice']) {
   echo '<div class="pure-control-group">';
   echo '<label for="dh_unit">Invoice Unit:</label> ';
   echo '<select name="dh_unit" id="dh_unit" class="mobile-select">';
   echo '<option value=0 selected>Unit </option>';
   $result=$dbcon->query("Select distinct unit from extUnits");
   while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
      echo "\n<option value= \"$row1[unit]\">$row1[unit]</option>";
   }
   echo '</select>';
   echo '</div>';

   echo '<div class="pure-control-group">';
   echo '<label for="dh_case">Units per Case:</label> ';
   echo '<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text"'.
      ' name="dh_case" id="dh_case">';
   echo '</div>';
}
?>
<br clear="all"/>

<script>
function show_confirm() {
   var nm = document.getElementById("name").value;
   if (checkEmpty(nm)) {
      alert("Enter a crop name!");
      return false;
   }
   var con="Crop: "+ nm + "\n";
   var du = document.getElementById("default_unit").value;
   if (checkEmpty(du)) {
      alert("Choose a default unit!");
      return false;
   }
   con += "Default unit: "+ du + "\n";
<?php
if ($_SESSION['sales_invoice']) {
   echo 'var dh = document.getElementById("dh_unit").value;';
   echo 'if (checkEmpty(dh)) {';
   echo '   alert("Choose an invoice unit!");';
   echo '   return false;';
   echo '}';
   echo 'con += "Invoice unit: "+ dh + "\n";';
   echo 'var uc = document.getElementById("dh_case").value;';
   echo 'if (checkEmpty(uc) || !isFinite(uc)) {';
   echo '   alert("Enter units per case!");';
   echo '   return false;';
   echo '}';
   echo 'con += "Units per case: "+ uc + "\n";';
}
?>
   return confirm("Confirm Entry:"+"\n"+con);
}
</script>

<input class="submitbutton pure-button wide" type="submit" name="done" value="Add"
    onclick = "return show_confirm();">

<?php
if (isset($_POST['done'])) {
   $name = escapehtml(strtoupper($_POST['name']));
   $default_unit = escapehtml($_POST['default_unit']);
   if ($_SESSION['sales_invoice']) {
      $dh_case = escapehtml($_POST['dh_case']);
      $dh_unit = escapehtml($_POST['dh_unit']);
   } else {
      $dh_unit = $default_unit;
      $dh_case = 1;
   }
   if (!empty($name) && !empty($default_unit) && !empty($dh_unit) && !empty($dh_case) && $dh_case > 0) {
      $sql="Insert into plant values ('".$name."','".$default_unit.
         "',".$dh_case.",'".  $dh_unit."', 1)";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
      $sql2="Insert into units(crop,default_unit,unit,conversion) values ('".$name."','".
         $default_unit."','".$default_unit."','1')";
      try {
         $stmt = $dbcon->prepare($sql2);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
      echo "<script>showAlert(\"Added Crop Successfully!\");</script> \n";
   } else {
      echo  "<script>alert(\"Enter all data!\\n\");</script> \n";
   }
}
?>
