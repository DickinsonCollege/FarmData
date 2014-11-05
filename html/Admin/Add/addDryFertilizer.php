<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h3 class="hi"><b>Add Dry Fertilizer</b></h3>
<br clear="all"/>
<label for="task">Fertilizer Name:&nbsp;</label>
<input class="textbox3 mobile-input" type="text" name="name" id="name">
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="add" value="Add" id="add">
</form>
<?php
if (isset($_POST['add'])) {
   $name = escapehtml(strtoupper($_POST['name']));
   if (!empty($name)) {
      $sql="insert into fertilizerReference(fertilizerName) values ('".$name."')";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add Dry Fertilizer: Please try again!\\n".mysql_error()."\");</script>";
      } else {
         echo "<script>showAlert('Added Dry Fertilizer successfully!');</script> ";
      }
   } else {
      echo  "<script>alert('Enter all data! ".mysql_error()."');</script>";
   }
}
?>
