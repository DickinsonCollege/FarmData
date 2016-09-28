<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<center><h2> <b> Add a Comment</b> </h2></center>
<form name="form" method="post" class = "pure-form pure-form-aligned"  action="<?php $_PHP_SELF ?>?tab=notes:notes_input">
<div class = "pure-control-group">
<label for="date">Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<div class ="pure-control-group"><label for="note">Comment:</label>
<textarea name="comments" rows="5" cols="30"> </textarea>
<br clear="all"/>
</div>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit">
</form>
<?php
if (!empty($_POST['submit'])) {
   if(!empty($_POST['comments'])) {
      $comSanitized=escapehtml($_POST['comments']);
      $user=escapehtml($_SESSION['username']);
      $sql = "insert into comments(username,comDate,comments) values ('".
         $user."','".$_POST['year']."-".$_POST['month']."-".$_POST['day'].
         "','".$comSanitized."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }else {
       echo  "<script>alert(\"Enter all data!\");</script> \n";
   }   
}
?>
