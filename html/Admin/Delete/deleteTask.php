<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<center>
<h2> Delete Labor Task </h2>
</center>
<form name='form' method='POST' class='pure-form pure-form-aligned' action='<?php  $_SERVER['PHP_SELF']?>'>
<div class="pure-control-group">
<label for="task">Labor Task:</label>
<select name='task' id='task' class='mobile-select'>
<option disabled selected>Labor Task</option>
<?php
$result = mysql_query("SELECT task from task");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= ".$row1['task'].">".$row1['task']."</option>";
        }
        echo "</select></div>";
?>
<br clear="all"/>
<input name="submit" type="submit" class="submitbutton pure-button wide" id="submit" value="Submit">
<?php
if(!empty($_POST['submit'])) {
   $task = escapehtml($_POST['task']);
   if(!empty($task)) {
      $sql5 = "delete from task where task = '".$task."'";
      $totalResult = mysql_query($sql5);
      if(!$totalResult) {
          echo '<script> alert("Could not remove task.  Is it used in a labor record?"); </script>';
      } else {
          echo '<script> alert("Removed Task Successfully"); </script>';
      }
   } else {
        echo '<script> alert("Please Select a Task"); </script>';
   }
}
?>
</form>
