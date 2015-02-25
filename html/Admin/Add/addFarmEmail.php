<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h3><b>Add Email for Invoices</b></h3>
<br>
<label for="email">Email address (of a farm employee who should
 receive invoices):&nbsp;</label> 
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3" type="text" name="email" id="email">
<br clear="all"/>
<script type="text/javascript">
function show_confirm() {
        var em = document.getElementById("email").value;
        if (checkEmpty(em)) {
           alert("Enter email address!");
           return false;
        }
        var con = "Email: "+ em + "\n";
        return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="done" value="Add" onclick="return show_confirm();">

<?php
if (isset($_POST['done'])) {
   $sql="insert into email values ('".escapehtml($_POST['email'])."')";
   $result=mysql_query($sql);
   if ($result) {
      echo "<script>showAlert(\"Added Email Successfully!\");</script> \n";
   } else {
      echo "<script>alert(\"Could Not Add Email: Please try again!\\n".mysql_error()."\");</script> \n";
   }
}
?>
