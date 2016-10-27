<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Soil/clearForm.php';
?>
<center><h2> <b> Add a Comment</b> </h2></center>
<form name="form" method="post" class = "pure-form pure-form-aligned"  action="<?php $_PHP_SELF ?>?tab=notes:notes_input" enctype="multipart/form-data">
<div class = "pure-control-group">
<label for="date">Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<div class ="pure-control-group"><label for="note">Comment:</label>
<textarea name="comments" id="comments" rows="5" cols="30"></textarea>
<br clear="all"/>
</div>

<div class="pure-control-group" id="filediv">
<label for="file">Picture (optional): </label>
<input type="file" name="fileIn" id="file">
</div>

<div class="pure-control-group">
<label for="clear">Max File Size: 2 MB </label>
<input type="button" value="Clear Picture" onclick="clearForm();">
</div>

<script type="text/javascript">
function show_confirm() {
   var mth = document.getElementById("month").value;
   var con = "Date: " + mth + "-";
   var dy = document.getElementById("day").value;
   con += dy + "-";
   var yr = document.getElementById("year").value;
   con += yr + "\n";
   var com = document.getElementById("comments").value;
   if (com == "") {
      alert("Please Enter a Comment");
      return false;
   }
   con += "Note: " + com + "\n";
   var fname = document.getElementById("file").value;
   if (fname != "") {
      var pos = fname.lastIndexOf(".");
      var ext = fname.substring(pos + 1, fname.length).toLowerCase();
      if (ext != "gif" && ext != "png" && ext != "jpg" && ext != "jpeg") {
         alert("Invalid image type: only gif, png, jpg and jpeg allowed.");
         return false;
      }
   con += "Picture: "+ fname + "\n";
   }
   return confirm("Confirm Entry:"+"\n"+con);
}
</script>

<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit"
   onclick="return show_confirm();">
</form>
<?php
if (!empty($_POST['submit'])) {
   $comSanitized=escapehtml($_POST['comments']);
   $user=escapehtml($_SESSION['username']);
   if (isset($_FILES['fileIn']) && isset($_FILES['fileIn']['error']) &&
      $_FILES['fileIn']['error'] == 1) {
      echo "<script>alert(\"File too large to upload!\");</script> \n";
      die();
   } else if (isset($_FILES['fileIn']) && isset($_FILES['fileIn']['tmp_name']) &&
      $_FILES['fileIn']['tmp_name'] != "") {
      //       $fname = $_SERVER['DOCUMENT_ROOT'].'/files/'.$_SESSION['db'].'/'.$_FILES['fileIn']['name'];
      $fname = '../files/'.$_SESSION['db'].'/'.$_FILES['fileIn']['name'];
      if (file_exists($fname)) {
         echo "<script>alert(\"File ".$fname." already exists - try a different file name.\");</script> \n";
         die();
      }
      if (!move_uploaded_file($_FILES['fileIn']['tmp_name'], $fname)) {
         echo "<script>alert(\"Error uploading file.\");</script> \n";
         die();
      }
   } else {
      $fname = "null";
   }
   $sql = "insert into comments(username,comDate,comments,filename) values ('".
      $user."','".$_POST['year']."-".$_POST['month']."-".$_POST['day'].
      "','".$comSanitized."', ";
   if ($fname == "null") {
      $sql .= "null";
   } else {
      $sql .= ":filename";
   }
   $sql .= ")";
   try {
      $stmt = $dbcon->prepare($sql);
      if ($fname != "null") {
         $stmt->bindParam(':filename', $fname, PDO::PARAM_STR);
      }
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
}
?>
