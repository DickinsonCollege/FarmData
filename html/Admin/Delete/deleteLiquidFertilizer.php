<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<center>
<h2 > Edit/Delete Liquid Fertilizer </h2>
</center>
<?php
if(!empty($_POST['submit'])) {
   $name = escapehtml($_POST['name']);
   $rename = escapehtml($_POST['rename']);
   $active = escapehtml($_POST['active']);

   if (empty($name)) {
             echo '<script> alert("Please select a fertilizer!"); </script>';
   } else {
      if(!empty($rename)) {
         $sql = "update liquidFertilizerReference set fertilizerName=upper('".$rename."'), active=".$active.
           " where fertilizerName='".$name."'";
      } else {
         $sql = "update liquidFertilizerReference set active=".$active." where fertilizerName='".$name."'";
      }
      $result = mysql_query($sql);

      if(!$result) {
          echo '<script> alert("Could not process command, please try again"); </script>';
      } else {
          echo '<script> showAlert("Edited Fertilizer Successfully"); </script>';
      }
   }
}
?>
<form name='form' class='pure-form pure-form-aligned' method='POST' action='<?php  $_SERVER['PHP_SELF']?>?tab=admin:admin_delete:deletematerials:deleteliquidfertilizermaterial'>

<div class="pure-control-group">
<label for="name">Fertilizer Name:</label>
<select name='name' id='name' class='mobile-select'>
<option disabled selected>Fertilizer</option>
<?php
$result = mysql_query("SELECT fertilizerName from liquidFertilizerReference");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[fertilizerName]\">$row1[fertilizerName]</option>";
        }
        echo "</select></div>";
?>

<div class="pure-control-group">
<label for="rename">Rename Fertilizer:</label>
<input type="text" id="rename" name="rename" class="textbox25 mobile-input">
</div>

<div class="pure-control-group">
<label for="active">Active:</label>
<select name='active' id='active' class='mobile-select'>
   <option value="1" selected>Active</option>
   <option value="0">Inactive</option>
</select>
</div>

<br clear="all"/>
<input name="submit" type="submit" class="submitbutton pure-button wide" id="submit" value="Submit">
</form>
