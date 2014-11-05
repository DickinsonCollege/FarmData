<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/escapehtml.php';
$dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or die ("Connect Failed! :".mysql_error());
mysql_select_db('wahlst_users');
?>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h1> Update User Status</h1>
<label for="user">Username:&nbsp;</label>
<div id="useriddiv" class="styled-select">
<select name="userid" id="userid">
<option value=0 selected disabled>Username</option>
<?php
$sql="select username from users where dbase='".$_SESSION['db']."'";
$result = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($result)) {
   echo '\n<option value="'.$row['username'].'">'.$row['username'].'</option>';
}
?>
</select>
</div>
<!--
<input class="textbox3" type="text" name="userid" id="userid">
-->
<br clear="all">
<input class="genericbutton" type="button" id="setpass"
   value="Reset Password" onClick="addBoxes();"/>
<div id="container"></div>
<script type="text/javascript">
function addBoxes() {
   var but = document.getElementById("setpass");
   var container = document.getElementById('container');
   if (but.value == "Reset Password") {
      var str = '<label for="pass">New Password:&nbsp;</label>';
      str = str + '<input class="textbox3" type="password" name="pass" id="pass">';
      str = str + '<br clear="all">';
      str = str + '<label for="pass2">Retype New Password:&nbsp;</label>';
      str = str + '<input class="textbox3" type="password" name="pass2" id="pass2">';
      str = str + '<br clear="all">';
      container.innerHTML=str;
      but.value = "Leave Password Unchanged";
   } else {
      but.value="Reset Password";
      container.innerHTML="";
   }
}
</script>
<label for="admin">Admin:&nbsp;</label> 
<div class="styled-select">
<select name="admin" id="admin">
<option selected value="0">No</option>
<option value="1">Yes</option>
</select>
</div>
<br clear="all">
<label for="admin">Active:&nbsp;</label> 
<div class="styled-select">
<select name="active" id="active">
<option value="0">No</option>
<option selected value="1">Yes</option>
</select>

<br clear="all">
<br clear="all">
<input class="submitbutton" type="submit" name="submit" value="Submit">
<?php
if (!empty($_POST['submit'])){
   $admin=$_POST['admin'];
   $active=$_POST['active'];
   $userid=escapehtml($_POST['userid']);
   $pass=$_POST['pass'];
   $pass2=$_POST['pass2'];
   if (!empty($userid)) {
      $runupdate = true;
      if (empty($pass)) {
         $update = "update users set admin = ".$admin.", active = ".$active." where username = '".$userid."'";
      } else if ($pass != $pass2) {
         echo "<script>alert(\"Passwords do not match!\");</script> \n";
         $runupdate = false;
      } else {
         $pass = escapehtml(crypt($pass, '123salt'));
         $update = "update users set passwd = '".$pass."', admin = ".$admin.
                 ", active = ".$active." where username = '".$userid."'";
      }
      if ($runupdate) {
         $sql = "select * from users where username = '".$userid."'";
         $result = mysql_query($sql);
         if ($result) {
            $ct = 0;
            while ($row = mysql_fetch_array($result)) {
               $ct = $ct + 1;
            }
            if ($ct == 1) {
               $result = mysql_query($update);
               if (!$result) {
                  echo "<script>alert(\"Could not update user: Please try again!\\n".mysql_error()."\");</script> \n";
               } else {
                  echo "<script>showAlert(\"Updated User successfully!\");</script> \n";
               }
            } else {
               echo "<script>alert(\"No such user: ".$userid."!\");</script>";
            }
         } else {
            echo "<script>alert(\"Could not connect to user database!\\n".mysql_error()."\");</script>";
         }
      }
   } else {
      echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>
</html>
