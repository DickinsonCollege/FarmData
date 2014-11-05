<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
if (!isset($_POST['submit'])) {
  include $_SERVER['DOCUMENT_ROOT'].'/design.php';
}

echo "<form name='comment' method='POST' action='addComment.php?year=".
   $_GET['year']."&month=".$_GET['month']."&day=".$_GET['day']."&currentID=".
   $_GET['currentID']."&detail=0'>";
?>

<h3>Add Comment to Harvest List:</h3>
<br clear = "all"/>
<textarea  name="comments" rows="20" cols="50" >
</textarea>
<br clear = "all"/>
<br clear = "all"/>
<input type='submit' name='submit' value='Update Comments' class='submitbutton' >
<?php
if(isset($_POST['submit'])){
   $comSanitized=escapehtml($_POST['comments']);
   $user=escapehtml($_SESSION['username']);
   $sql="UPDATE harvestList SET comment = concat(comment,'\n".$user.
      " posted: ".$comSanitized."') where id=".$_GET['currentID'];
   mysql_query($sql);
   echo mysql_error();

   $url = "Location: harvestList.php?tab=harvest:harvestList&year=".$_GET['year']."&month=".$_GET['month'].
      "&day=".$_GET['day']."&currentID=".$_GET['currentID']."&detail=0'";
   header($url);
}

?>
</form>
