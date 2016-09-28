<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' class = "pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center><h2 class="hi"><b>Add Dry Fertilizer</b></h2></center>
<br clear="all"/>
<div class = "pure-control-group">
<label for="task">Fertilizer Name:</label>
<input class="textbox3 mobile-input" type="text" name="name" id="name">
</div>
<br clear="all"/>
<script>
function show_confirm() {
   var i = document.getElementById("name").value;
   if (checkEmpty(i)) {
      alert("Please Enter Fertilizer Name");
      return false;
   }
   var con="Fertilizer Name: "+ i+ "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<input class="submitbutton pure-button wide" type="submit" name="add" value="Add" id="add"
   onclick = "return show_confirm()";>
</form>
<?php
if (isset($_POST['add'])) {
   $name = escapehtml(strtoupper($_POST['name']));
   if (!empty($name)) {
      $sql="insert into fertilizerReference(fertilizerName) values ('".$name."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not add Dry Fertilizer: Please try again!\\n".$p->getMessage().
            "\");</script>";
         die();
      }
      echo "<script>showAlert('Added Dry Fertilizer successfully!');</script> ";
   } else {
      echo  "<script>alert('Enter all data! ');</script>";
   }
}
?>
