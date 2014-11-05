<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<body id="add">
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h1> Add a New User </h1>
<label for="user">Username:&nbsp;</label>
<input class="textbox3" type="text" name="userid" id="userid">
<br clear="all">
<label for="pass">Password:&nbsp;</label>
<input class="textbox3" type="password" name="pass" id="pass">
<br clear="all">
<label for="pass2">Retype Password:&nbsp;</label>
<input class="textbox3" type="password" name="pass2" id="pass2">
<br clear="all">
<label for="admin">Admin:&nbsp;</label> 
<div class="styled-select">
<select name="admin" id="admin">
<option selected value="0">No</option>
<option value="1">Yes</option>
</select>
</div>

<br clear="all">
<br clear="all">
<input class="submitbutton" type="submit" name="submit" value="Submit">
<?php
if (!empty($_POST['submit'])){
   $admin=$_POST['admin'];
   $userid=escapehtml($_POST['userid']);
   $pass=$_POST['pass'];
   $pass2=$_POST['pass2'];
   if (!empty($userid) && !empty($pass)) {
      if ($pass == $pass2) {
         $dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or die ("Connect Failed! :".mysql_error());
         mysql_select_db('wahlst_users');
         $sql = "select * from users where username = '".$userid."'";
         $result = mysql_query($sql);
         if ($result) {
            $ct = 0;
            while ($row = mysql_fetch_array($result)) {
               $ct = $ct + 1;
            }
            if ($ct == 0) {
               $pass = escapehtml(crypt($pass, '123salt'));
               $sql = "insert into users values ('".$userid."', '".$pass."', '".$_SESSION['db']."', ".$admin.", 1)";
               $result = mysql_query($sql);
               if (!$result) {
                  echo "<script>alert(\"Could not add user: Please try again!\\n".mysql_error()."\");</script> \n";
               }else {
                  echo "<script>showAlert(\"Added User successfully!\");</script> \n";
               }
            } else {
               echo "<script>alert(\"Username already exists: choose another!\");</script>";
            }
         } else {
            echo "<script>alert(\"Could not connect to user database!\\n".mysql_error()."\");</script>";
         }
      } else {
         echo "<script>alert(\"Passwords do not match!\");</script> \n";
      }
   } else {
      echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>
</body>
</html>
