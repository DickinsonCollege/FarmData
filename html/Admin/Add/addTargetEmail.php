<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h1><b>Add Email to Sales Target</b></h1>
<label for="target">Sales Target:&nbsp;</label> 
<div id='targDiv' class='styled-select'>
<select name="target" id="target">
<option value=0 selected disabled>Sales Target</option>
<?php
$sql = "select targetName from targets where active = 1";
$result=mysql_query($sql);
while ($row1 =  mysql_fetch_array($result)){
  echo '<option value= "'.$row1['targetName'].'">'.$row1['targetName'].'</option>';
}
?>
</select>
<br clear="all"/>
<label for="email">Email:&nbsp;</label> 
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3" type="text" name="email" id="email">
<br clear="all"/>
<script type="text/javascript">
function show_confirm() {
	var targ = document.getElementById("target").value;
        if (checkEmpty(targ)) {
           alert("Select a Sales Target Name!");
           return false;
        }
        var con="Sales Target: "+ targ + "\n";
        var em = document.getElementById("email").value;
        if (checkEmpty(em)) {
           alert("Enter email address!");
           return false;
        }
        con+="Email: "+ em + "\n";
        return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="done" value="Add" onclick="return show_confirm();">

<?php
if (isset($_POST['done'])) {
   $sql="insert into targetEmail values ('".escapehtml($_POST['email'])."','".
     escapehtml($_POST['target'])."')";
   $result=mysql_query($sql);
   if ($result) {
      echo "<script>showAlert(\"Added Email Successfully!\");</script> \n";
   } else {
      echo "<script>alert(\"Could Not Add Email: Please try again!\\n".mysql_error()."\");</script> \n";
   }
}
?>
