<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class = "pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center><h2><b>Add New Sales Target</b></h2></center>

<div class = "pure-control-group">
<label for="target">Sales Target Name:</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="target" id="target">
</div>

<div class = "pure-control-group">
<label for="prefix">Invoice Prefix:</label>
<input class="textbox2 mobile-input"type="text" name="prefix" onkeypress= 'stopSubmitOnEnter(event)'; id="prefix">
</div>

<br clear="all"/>
<script type="text/javascript">
function show_confirm() {
   var targ = document.getElementById("target").value;
   if (checkEmpty(targ)) {
      alert("Enter Sales Target Name!");
      return false;
   }
   var con="Target Name: "+ targ + "\n";
   var pre = document.getElementById("prefix").value;
   if (checkEmpty(pre)) {
      alert("Enter Invoice Prefix!!");
      return false;
   }
   con+="Invoice Prefix: "+ pre + "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Add">
<?php
if (!empty($_POST['done'])) {
   $name = escapehtml($_POST['target']);
   $pre = escapehtml($_POST['prefix']);
   $sql="insert into targets(targetName, prefix, nextNum) values('".$name."', '".$pre."', 1)";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert("Could not add sales target", $p);
      die();
   }
   echo "<script>showAlert(\"Added Sales Target Successfully!\");</script> \n";
}
?>

