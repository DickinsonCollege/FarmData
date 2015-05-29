<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/escapehtml.php';
$dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or die ("Connect Failed! :".mysql_error());
mysql_select_db('wahlst_users');
?>
<form name='form' class="pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center>
<h2> Update User Status</h2>
</center>
<div class="pure-control-group">
<label for="user">Username:</label>
<select name="userid" id="userid" onchange="update();">
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
<br clear="all"/>
<input class="genericbutton pure-button wide" type="button" id="setpass"
   value="Reset Password" onClick="addBoxes();"/>
<br clear="all"/>
<br clear="all">
<div id="container"></div>
<script type="text/javascript">
function addBoxes() {
   var but = document.getElementById("setpass");
   var container = document.getElementById('container');
   if (but.value == "Reset Password") {
      var str = '<div class="pure-control-group"> <label for="pass">New Password:</label> ';
      str = str + '<input type="password" name="pass" id="pass">';
      str = str + '</div><div class="pure-control-group">';
      str = str + '<label for="pass2">Retype New Password:</label> ';
      str = str + '<input  type="password" name="pass2" id="pass2">';
      str = str + '</div><br clear="all">';
      container.innerHTML=str;
      but.value = "Leave Password Unchanged";
   } else {
      but.value="Reset Password";
      container.innerHTML="";
   }
}

function update() {
   var user = document.getElementById("userid").value;
   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "getUserExt.php?user=" + encodeURIComponent(user), false);
   xmlhttp.send();
   var info = eval("(" + xmlhttp.responseText + ")");
   DOCument.getElementById("active").selectedIndex = info['active'];
   document.getElementById("adminS").selectedIndex = info['admin'];
}
</script>
<div class="pure-control-group">
<label for="admin">Admin:</label> 
<select name="admin" id="adminS">
<option value="0">No</option>
<option value="1">Yes</option>
</select>
</div>
<div class="pure-control-group">
<label for="active">Active:</label> 
<select name="active" id="active">
<option value="0">No</option>
<option value="1">Yes</option>
</select>

<br clear="all">
<br clear="all">
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit">
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
