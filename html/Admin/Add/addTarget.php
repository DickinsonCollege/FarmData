<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h1><b>Add New Sales Target</b></h1>
<label for="target">Sales Target Name:&nbsp;</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="target" id="target">
<br clear="all"/>
<label for="prefix">Invoice Prefix:&nbsp;</label>
<input class="textbox2 mobile-input"type="text" name="prefix" onkeypress= 'stopSubmitOnEnter(event)'; id="prefix">
<br clear="all"/>
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
<input onclick= "return show_confirm()";  class="submitbutton" type="submit" name="done" value="Add">
<?php
if (!empty($_POST['done'])) {
   $name = escapehtml($_POST['target']);
   $pre = escapehtml($_POST['prefix']);
   $sql="insert into targets(targetName, prefix, nextNum) values('".$name."', '".$pre."', 1)";
   $result=mysql_query($sql);
   if (!$result) {
      echo "<script>alert(\"Could not add sales target: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Added Sales Target Successfully!\");</script> \n";
   }
}
?>

