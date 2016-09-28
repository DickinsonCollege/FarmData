<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<form name='form' class = "pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center><h2> Add a New User </h2></center>

<div class = "pure-control-group">
<label for="user">New User:</label>
<input class="textbox3 mobile-input" type="text" name="userid" id="userid">
</div>

<div class = "pure-control-group">
<label for="admin">Admin:</label> 
<input type="checkbox" name="admin" id="adminB" class="imgClass2 regular-checkbox big-checkbox"  /><label for="checkboxFiveInput"></label>
</div>
<script type="text/javascript">
function show_confirm() {
   var i = document.getElementById("userid").value;
   if (checkEmpty(i)) {
      alert("Enter New User ID");
      return false;
   }
   var con="User ID: "+ i + "\n";
   var i = document.getElementById("adminB").checked;
   console.log(i);
   con += "Admin: "+ i + "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>

<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit"
   onclick="return show_confirm();">
<?php
$admin=0;
if(!empty($_POST['submit'])){
   if (!empty($_POST['admin'])) {
      $admin=1;
   }
   if(!empty($_POST['userid'])){
      $sql="Insert into users(username,admin,active) values ('".escapehtml($_POST['userid'])."',".$admin.", 1)";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not add user".$p->getMessage()."\");</script>";
         die();
      }
      echo "<script>showAlert(\"Added User successfully!\");</script> \n";
   }else {
      echo "<script>alert(\"Enter all data!\");</script> \n";
   }
}
?>
</body>
</html>
