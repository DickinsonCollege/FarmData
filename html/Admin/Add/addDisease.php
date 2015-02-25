<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h3><b>Add New Disease</b></h3>
<br>
<label for="disease"> Disease Name:&nbsp;</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="name" id="name">
<!--
<br clear="all"/>
<label for="admin">Active:&nbsp;</label>
<input style="margin-top: 10px;" type="checkbox"name="active" id="active" class="imgClass2 regular-checkbox big-checkbox"  /><label for="checkboxFiveInput"></label>
-->
<br clear="all"/>
<br clear="all"/>

<script>
function show_confirm() {
        var i = document.getElementById("name").value;
        var con="Disease Name : "+ i+ "\n";


return confirm("Confirm Entry: " +"\n"+con);
}
</script>

<input onclick= "return show_confirm()";  class="submitbutton" type="submit" name="done" value="Add">
<?php
$active = 1;
if (!empty($_POST['done'])) {
   if(!empty($_POST['name'])) {
      $name = escapehtml(strtoupper($_POST['name']));
      $sql="Insert into disease(diseaseName) values ('".$name."')";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add disease: Please try again!\\n".mysql_error()."\");</script>\n";
      } else {
         echo "<script>showAlert(\"Added Disease Successfully!\");</script> \n";
      }
   } else {
      echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>

