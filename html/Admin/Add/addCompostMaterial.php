<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class = "pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center><h2><b>Add New Compost Material</b></h2></center>
<div class = "pure-control-group">
<label for="name"> Compost Material:</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="name" id="name">
</div>

<script>
function show_confirm() {
   var i = document.getElementById("name").value;
   if (checkEmpty(i)) {
      alert("Enter Compost Material");
      return false;
   }
   var con="Compost Material: "+ i+ "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>

<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Add">
<?php
if (!empty($_POST['done'])) {
   if(!empty($_POST['name'])) {
      $name = escapehtml(strtoupper($_POST['name']));
      $sql="Insert into compost_materials(materialName) values ('".$name."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert("Could not add material", $p);
         die();
      }
      echo "<script>showAlert(\"Added Material Successfully!\");</script> \n";
   } else {
      echo "<script>alert(\"Enter all data!\");</script> \n";
   }
}
?>

