<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class="pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center>
<h2>Add New Tray Size</h2>
</center>

<div class="pure-control-group">
<label for="covercrop"> Tray Size:</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="size" id="size">
</div>
<br clear="all"/>

<script>
function show_confirm() {
        var size = document.getElementById("size").value;
        var con = "Tray Size: "+ size + "\n";
	return confirm("Confirm Entry: " +"\n"+con);
}
</script>

<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Add">
<?php
$active = 1;
if (!empty($_POST['done'])) {
   if(!empty($_POST['size'])) {
      $size = escapehtml($_POST['size']);
      if ($size < 0) {
         echo "<script>alert(\"Tray size cannot be negative!\");</script> \n";
         exit(0);
      }
      $sql="Insert into flat values ('". $size."');";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add tray size: Please try again!\\n".mysql_error()."\");</script>\n";
      } else {
         echo "<script>showAlert(\"Added Tray Size Successfully!\");</script> \n";
      }
   } else {
      echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>

