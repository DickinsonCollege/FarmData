<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h3 class="hi"><b>Add Pile ID</b></h3>
<br clear="all"/>
<label for="pileID">PileID:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input" type="text" name="pileID" id="pileID">
<br clear="all"/>
<br clear="all"/>
<div>
<label for="size">Comments:&nbsp;</label> 
<br clear="all"/>
<textarea name='comments' rows = '20' cols = '30'>
</textarea>
</div>
<br clear="all"/>
<input class="submitbutton" type="submit" name="add" value="Add" id="add">
</form>
<?php
if (isset($_POST['add'])) {
   $pileID = escapehtml(strtoupper($_POST['pileID']));
   $comments = escapehtml($_POST['comments']);
   if (!empty($pileID)) {
      $sql="insert into compost_pile(pileID,comments) values ('".
         $pileID."','".$comments."')";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add pile: Please try again!\\n".
            mysql_error()."\");</script> \n";
      } else {
         echo "<script>showAlert('Added pile successfully!');</script> ";
      }
   } else {
      echo  "<script>alert('Enter all data! ".mysql_error()."');</script>";
   }
}
?>
