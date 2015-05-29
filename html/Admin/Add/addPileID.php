<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' class = "pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center><h2 class="hi"><b>Add Pile ID</b></h2></center>
<div class = "pure-control-group">
<label for="pileID">PileID:</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input" type="text" name="pileID" id="pileID">
</div>

<div class = "pure-control-group">
<label for="size">Comments:</label> 
<textarea name='comments' rows = '10' cols = '30'></textarea>
</div>

<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="add" value="Add" id="add">
</form>
<br clear="all"/>

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
