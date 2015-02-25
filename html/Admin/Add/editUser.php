<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<body id="add">
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h3> Update User Status</h3>
<br>
<label for="user">Username:&nbsp;</label>
<div id="useriddiv" class="styled-select">
<select name="userid" id="userid" class='mobile-select'>
<option value=0 selected disabled>Username</option>
<?php
$sql="select username from users";
$result = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($result)) {
   echo '\n<option value="'.$row['username'].'">'.$row['username'].'</option>';
}
?>
</select>
</div>
<br clear="all">
<label for="admin">Admin:&nbsp;</label> 
<div class="styled-select">
<select name="admin" id="admin" class='mobile-select'>
<option selected value="0">No</option>
<option value="1">Yes</option>
</select>
</div>
<br clear="all">
<label for="admin">Active:&nbsp;</label> 
<div class="styled-select">
<select name="active" id="active" class='mobile-select'>
<option value="0">No</option>
<option selected value="1">Yes</option>
</select>
</div>

<br clear="all">
<br clear="all">
<input class="submitbutton" type="submit" name="submit" value="Submit">
<?php
if (!empty($_POST['submit'])){
   $admin=$_POST['admin'];
   $active=$_POST['active'];
   $userid=escapehtml($_POST['userid']);
   if (!empty($userid)) {
      $update = "update users set admin = ".$admin.  ", active = ".$active." where username = '".$userid."'";
      $result = mysql_query($update);
      if (!$result) {
         echo "<script>alert(\"Could not update user: Please try again!\\n".mysql_error()."\");</script> \n";
      } else {
         echo "<script>showAlert(\"Updated User successfully!\");</script> \n";
      }
   } else {
      echo "<script>alert(\"Please Select a User!\\n\");</script> \n";
   }
}
?>
</body>
</html>
