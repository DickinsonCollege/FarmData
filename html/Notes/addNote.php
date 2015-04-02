<?php session_start();?>
<html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<h1> <b> Add a Comment</b> </h1>
<form name="form" method="post" action="<?php $_PHP_SELF ?>?tab=notes:notes_input">

<label for="date">Date:</label>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="note">Comment:</label>
<br clear="all"/>
<textarea name="comments" rows="10" cols="30"> </textarea>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit">
</form>
<?php
if (!empty($_POST['submit'])) {
   if(!empty($_POST['comments'])) {
      $comSanitized=escapehtml($_POST['comments']);
      $user=escapehtml($_SESSION['username']);
      $result=mysql_query("Insert into comments(username,comDate,comments) values ('".
         $user."','".$_POST['year']."-".$_POST['month']."-".$_POST['day'].
         "','".$comSanitized."')");
      if(!$result){
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
      } else {
         echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      }   
   }else {
       echo  "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }   
}
?>
