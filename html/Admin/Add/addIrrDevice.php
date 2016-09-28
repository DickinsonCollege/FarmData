<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class="pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center>
<h2>Add New Irrigation Device</h2>
</center>

<div class="pure-control-group">
<label for="name"> Irrigation Device:</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; 
  type="text" name="name" id="name">
</div>

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
<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Add">
<?php
if (!empty($_POST['done'])) {
    $sql="Insert into irrigation_device(irrigation_device) values ('".
       escapehtml(strtoupper($_POST['name']))."')";
    try {
       $stmt = $dbcon->prepare($sql);
       $stmt->execute();
    } catch (PDOException $p) {
       phpAlert('', $p);
       die();
    }
    echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
}
?>

