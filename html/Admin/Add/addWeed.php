<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
 include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class = "pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center><h2><b>Add New Weed Species</b></h2></center>

<div class="pure-control-group">
<label for="covercrop"> Weed Species Name:</label>
<input  onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="name" id="name">
</div>
<br clear="all"/>

<script>
function show_confirm() {
   var i = document.getElementById("name").value;
   if (checkEmpty(i)) {
      alert("Enter Weed Name");
      return false;
   }
   var con="Weed Name : "+ i+ "\n";
   return confirm("Confirm Entry: " + "\n" + con);
}
</script>
<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Add">
<?php
$active = 1;
if (!empty($_POST['done'])) {
   if(!empty($_POST['name'])) {
      $name = escapehtml(strtoupper($_POST['name']));
      $sql="Insert into weed(weedName) values ('".$name."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert("Could not add weed species", $p);
         die();
      }
      echo "<script>showAlert(\"Added weed species Successfully!\");</script> \n";
   } else {
      echo "<script>alert(\"Enter all data!\");</script> \n";
   }
}
?>

