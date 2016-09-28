<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' class = "pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center><h2 class="hi"><b>Add Compost Pile</b></h2></center>
<div class = "pure-control-group">
<label for="pileID">Pile ID:</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input" type="text" name="pileID" id="pileID">
</div>

<div class = "pure-control-group">
<label for="size">Comments:</label> 
<textarea name='comments' id="comments" rows = '10' cols = '30'></textarea>
</div>

<script>
function show_confirm() {
   var i = document.getElementById("pileID").value;
   if (checkEmpty(i)) {
      alert("Enter Pile ID");
      return false;
   }
   var con="Pile ID: "+ i + "\n";
   var i = document.getElementById("comments").value;
   con += "Comments: " + i;
   return confirm("Confirm Entry: " + "\n" + con);
}
</script>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="add" value="Add" id="add"
   onClick="return show_confirm();">
</form>
<br clear="all"/>

<?php
if (isset($_POST['add'])) {
   $pileID = escapehtml(strtoupper($_POST['pileID']));
   $comments = escapehtml($_POST['comments']);
   if (!empty($pileID)) {
      $sql="insert into compost_pile(pileID,comments) values ('".
         $pileID."','".$comments."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert("Could not add pile", $p);
         die();
      }
      echo "<script>showAlert('Added pile successfully!');</script> ";
   } else {
      echo  "<script>alert('Enter all data!');</script>";
   }
}
?>
