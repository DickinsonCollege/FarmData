<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h1 > Edit/Delete Liquid Fertilizer </h1>
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
<form name='form' method='POST' action='<?php  $_SERVER['PHP_SELF']?>?tab=admin:admin_delete:deletesoil:deletematerials:deleteliquidfertilizermaterial'>

<label for="name"><b>Fertilizer Name:&nbsp;</b></label>
<div id='name2' class='styled-select'>
<select name='name' id='name' class='mobile-select'>
<option disabled selected>Fertilizer</option>
<?php
$result = mysql_query("SELECT fertilizerName from liquidFertilizerReference");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[fertilizerName]\">$row1[fertilizerName]</option>";
        }
        echo "</select></div>";
?>
<br clear="all">

<label for="rename"><b>Rename Fertilizer:</b></label>
<input type="text" id="rename" name="rename" class="textbox25 mobile-input">
<br clear="all">

<label for="active"><b>Active:&nbsp;</b></label>
<div id='active2' class='styled-select'>
<select name='active' id='active' class='mobile-select'>
   <option value="1" selected>Active</option>
   <option value="0">Inactive</option>
</select>
</div>

<br clear="all"/>
<br clear="all"/>
<input name="submit" type="submit" class="submitbutton" id="submit" value="Submit">
</form>
