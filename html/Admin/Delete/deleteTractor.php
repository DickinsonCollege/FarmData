<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<center>
<h2 > Delete Tractor </h2>
</center>
<form name='form' class='pure-form pure-form-aligned' method='POST' action='<?php  $_SERVER['PHP_SELF']?>'>
<div class="pure-control-group">
<label>Tractor Name:</label>
<select name='tractor' id='tractor' class='mobile-select'>
<option disabled selected>Tractor</option>
</div>

<?php
$result = mysql_query("SELECT tractorName from tractor where active=1");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[tractorName]\">$row1[tractorName]</option>";
        }
        echo "</select></div>";
?>

<br clear="all"/>
<input name="submit" type="submit" class="submitbutton pure-button wide" id="submit" value="Submit">
<?php
if(!empty($_POST['submit'])) {
   $tractor = escapehtml($_POST['tractor']);
   if(!empty($tractor)) {
      $sql5 = "update tractor set active=0 where tractorName='".$tractor."'";
      $totalResult = mysql_query($sql5);
      if(!$totalResult) {
          echo '<script> alert("Could not process command, please try again"); </script>';
      } else {
          echo '<script> alert("Removed Tractor Successfully"); </script>';
      }
   }
}
?>
</form>
