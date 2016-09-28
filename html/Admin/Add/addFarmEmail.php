<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class = "pure-form pure-form-aligned"  method="post" action="<?php $_PHP_SELF ?>">
<center><h2><b>Add Email for Invoices</b></h2></center>

<div class = "pure-control-group">
<label for="email">Email address (of a farm employee who should&nbsp;receive&nbsp;invoices):&nbsp;</label> 
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3" type="text" name="email" id="email">
</div>

<script type="text/javascript">
function show_confirm() {
   var em = document.getElementById("email").value;
   if (checkEmpty(em)) {
      alert("Enter Email Address!");
      return false;
   }
   var con = "Email: "+ em + "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="done" value="Add" onclick="return show_confirm();">

<?php
if (isset($_POST['done'])) {
   $sql="insert into email values ('".escapehtml($_POST['email'])."')";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert("Could not add farm email", $p);
      die();
   }
   echo "<script>showAlert(\"Added Email Successfully!\");</script> \n";
}
?>
