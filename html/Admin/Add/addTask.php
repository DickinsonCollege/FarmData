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
<script type="text/javascript">
function show_confirm() {
   var i = document.getElementById("task").value;
   if (checkEmpty(i)) {
      alert("Enter Labor Task");
      return false;
   }
   var con="Task: "+ i + "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<input class="submitbutton pure-button wide" type="submit" name="add" value="Add" id="add"
  onclick = "return show_confirm();">
</form>
<?php
if (isset($_POST['add'])) {
   $task = escapehtml(strtoupper($_POST['task']));
   if (!empty($task)) {
      $sql="insert into task(task) values ('".$task."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not add task".$p->getMessage()."\");</script>";
         die();
      }
      echo "<script>showAlert('Added task successfully!');</script> ";
   } else {
      echo  "<script>alert('Enter all data!');</script>";
   }
}
?>
