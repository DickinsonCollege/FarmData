<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h1><b>Add New Irrigation Device</b></h1>
<label for="name"> Irrigation Device:&nbsp;</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; 
  type="text" name="name" id="name">
<br clear="all"/>

<script>
function show_confirm() {
   var i = document.getElementById("name").value;
   if (checkEmpty(i)) {
      alert("Please Enter Irrigation Device");
      return false;
   }
   var con="Irrigation Device: "+ i+ "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>
<br clear="all"/>
<input onclick= "return show_confirm()";  class="submitbutton" type="submit" name="done" value="Add">
<?php
if (!empty($_POST['done'])) {
    $sql="Insert into irrigation_device(irrigation_device) values ('".
       escapehtml(strtoupper($_POST['name']))."')";
    $result=mysql_query($sql);
    if (!$result) {
       echo "<script>alert(\"Could not enter data: Please try again!\\n".
          mysql_error()."\");</script>\n";
    } else {
       echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
    }
}
?>

