<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h1><b>Add New Crop</b></h1>
<label for="crop">Crop Name:&nbsp;</label> 
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3 mobile-input" type="text" name="name" id="name">
<br clear="all"/>
<label for="default">Default Unit:&nbsp;</label> 
<div id='crop'class='styled-select'>
<select name="default_unit" id="default_unit" class='mobile-select'>
<option value=0 selected>Unit </option>
<?php
$sql = "select distinct unit from extUnits";
$result=mysql_query($sql);
while ($row1 =  mysql_fetch_array($result)){  echo "\n<option value= \"$row1[unit]\">$row1[unit]</option>";
}
?>
</select>
<?php
if ($_SESSION['sales_invoice']) {
   echo '<br clear="all"/>';
   echo '<label for="dh_unit">Invoice Unit:&nbsp;</label> ';
   echo '<select name="dh_unit" id="dh_unit" class="mobile-select">';
   echo '<option value=0 selected>Unit </option>';
   $result=mysql_query("Select distinct unit from extUnits");
   while ($row1 =  mysql_fetch_array($result)){
      echo "\n<option value= \"$row1[unit]\">$row1[unit]</option>";
   }
   echo '</select>';
   echo '<br clear="all"/>';
   echo '<label for="dh_case">Units per Case:&nbsp;</label> ';
   echo '<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="dh_case" id="dh_case">';
   echo '</div>';
}
?>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="done" value="Add">


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
      $result=mysql_query($sql);
      if ($result) {
         $sql2="Insert into units(crop,default_unit,unit,conversion) values ('".$name."','".
            $default_unit."','".$default_unit."','1')";
         $result2=mysql_query($sql2);
      }
      if (!$result || !$result2) {
	echo "<script>alert(\"Could not add crop: Please try again!\\n".mysql_error()."\");</script> \n";
      } else {
         echo "<script>showAlert(\"Added Crop Successfully!\");</script> \n";
      
      }
   }else {
      echo  "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>
