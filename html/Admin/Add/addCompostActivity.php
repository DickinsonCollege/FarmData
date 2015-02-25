<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h3><b>Add New Compost Activity</b></h3>
<br>
<label for="name"> Compost Activity:&nbsp;</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="name" id="name">
<br clear="all"/>

<script>
function show_confirm() {
        var i = document.getElementById("name").value;
        var con="Compost Activity: "+ i+ "\n";


return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>

<input onclick= "return show_confirm()";  class="submitbutton" type="submit" name="done" value="Add">
<?php
if (!empty($_POST['done'])) {
   if(!empty($_POST['name'])) {
      $name = escapehtml(strtoupper($_POST['name']));
      $sql="Insert into compost_activities(activityName) values ('".$name."')";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add material: Please try again!\\n".mysql_error()."\");</script>\n";
      } else {
         echo "<script>showAlert(\"Added Material Successfully!\");</script> \n";
      }
   } else {
      echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>

