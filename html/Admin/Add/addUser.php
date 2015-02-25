<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h3> Add a New User </h3>
<br>
<label for="user">New User:&nbsp;</label>
<input class="textbox3 mobile-input" type="text" name="userid" id="userid">
<br clear="all"/>
<label for="admin">Admin:&nbsp;</label> 
<input type="checkbox"name="admin" id="admin" class="imgClass2 regular-checkbox big-checkbox"  /><label for="checkboxFiveInput"></label>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit"><label for="checkbox-2-1"></label> 
<?php
$admin=0;
if(!empty($_POST['submit'])){
   if (!empty($_POST['admin'])) {
      $admin=1;
   }
   if(!empty($_POST['userid'])){
      $sql="Insert into users(username,admin,active) values ('".escapehtml($_POST['userid'])."',".$admin.", 1)";
      $result=mysql_query($sql);
      if (!$result) {
        echo "<script>alert(\"Could not add user: Please try again!\\n".mysql_error()."\");</script> \n";

      }else {
         echo "<script>showAlert(\"Added User successfully!\");</script> \n";
      }
   }else {
      echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>
</body>
</html>
