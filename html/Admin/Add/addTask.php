<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' class = "pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center><h2 class="hi"><b>Add Labor Task</b></h2></center>
<div class = "pure-control-group">
<label for="task">Task:</label>
<input class="textbox3 mobile-input" type="text" name="task" id="task">
</div>

<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="add" value="Add" id="add">
</form>
<?php
if (isset($_POST['add'])) {
   $task = escapehtml(strtoupper($_POST['task']));
   if (!empty($task)) {
      $sql="insert into task(task) values ('".$task."')";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add task: Please try again!\\n".mysql_error()."\");</script>";
      } else {
         echo "<script>showAlert('Added task successfully!');</script> ";
      }
   } else {
      echo  "<script>alert('Enter all data! ".mysql_error()."');</script>";
   }
}
?>
