<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/escapehtml.php';
try {
   $dbcon = new PDO('mysql:host=localhost;dbname=wahlst_users', 'wahlst_usercheck', 'usercheckpass');
   $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $d) {
   die($d->getMessage());
}
?>
<form name='form' class="pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center>
<h2> Update User Status</h2>
</center>
<div class="pure-control-group">
<label for="user">Username:</label>
<select name="userid" id="userid" onchange="update();">
<?php
$sql="select username from users where dbase='".$_SESSION['db']."'";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
   document.getElementById("active").selectedIndex = info['active'];
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
<script type="text/javascript">
update();
</script>
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
         $result = $dbcon->query($sql);
         if ($result) {
            $ct = 0;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $ct = $ct + 1;
            }
            if ($ct == 1) {
               try {
                  $stmt = $dbcon->prepare($update);
                  $stmt->execute();
               } catch (PDOException $p) {
                  phpAlert('Could not update user', $p);
                  die();
               }
               echo "<script>showAlert(\"Updated User Successfully!\");</script> \n";
            } else {
               echo "<script>alert(\"No such user: ".$userid."!\");</script>";
            }
         } else {
            echo "<script>alert(\"Could not connect to user database!\");</script>";
         }
      }
   } else {
      echo "<script>alert(\"Enter all data!\");</script> \n";
   }
}
?>
</html>
