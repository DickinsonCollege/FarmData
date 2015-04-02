<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h3 > Delete Tray Size </h3>
<br>
<form name='form' method='POST' action='<?php  $_SERVER['PHP_SELF']?>'>
<label for="flat">Tray Size:&nbsp;</label>
<div id='crop2' class='styled-select'>
<select name='flat' id='flat' class='mobile-select'>
<option disabled selected>Tray Size</option>
<?php
$result = mysql_query("SELECT cells from flat");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= ".$row1['cells'].">".$row1['cells']."</option>";
        }
        echo "</select></div>";
?>
<br clear="all"/>
<br clear="all"/>
<input name="submit" type="submit" class="submitbutton" id="submit" value="Submit">
<?php
if(!empty($_POST['submit'])) {
   $flat = escapehtml($_POST['flat']);
   if(!empty($flat)) {
      $sql5 = "delete from flat where cells = ".$flat;
      $totalResult = mysql_query($sql5);
      if(!$totalResult) {
          echo '<script> alert("Could not remove tray.  Is it used in a tray seeding record?"); </script>';
      } else {
          echo '<script> alert("Removed Tray Size Successfully"); </script>';
      }
   } else {
        echo '<script> alert("Please Select a Tray Size"); </script>';
   }
}
?>
</form>
