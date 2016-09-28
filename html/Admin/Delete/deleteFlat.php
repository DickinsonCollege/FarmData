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
<?php
$result = $dbcon->query("SELECT cells from flat");
        while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
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
   $sql5 = "delete from flat where cells = ".$flat;
   try {
      $stmt = $dbcon->prepare($sql5);
      $stmt->execute();
   } catch (PDOException $p) {
      echo "<script>alert(\"Could not remove tray - is it used in a tray seeding record?\\n".
         $p->getMessage()."\");</script>";
      die();
   }
   echo '<script> alert("Removed Tray Size Successfully"); </script>';
}
?>
</form>
