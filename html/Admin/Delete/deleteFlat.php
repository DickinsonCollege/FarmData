<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<center>
<h2 > Delete Tray Size </h2>
</center>
<form name='form' class="pure-form pure-form-aligned" method='POST' action='<?php  $_SERVER['PHP_SELF']?>'>
<div class = "pure-control-group">
<label for="flat">Tray Size:</label>
<select name='flat' id='flat' class='mobile-select'>
<option disabled selected>Tray Size</option>
</div>
<?php
$result = mysql_query("SELECT cells from flat");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= ".$row1['cells'].">".$row1['cells']."</option>";
        }
        echo "</select></div>";
?>
<br clear="all"/>
<input name="submit" type="submit" class="submitbutton pure-button wide" id="submit" value="Submit">
<br clear="all"/>

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
