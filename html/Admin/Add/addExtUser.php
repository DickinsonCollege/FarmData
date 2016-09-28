<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<body id="add">
<form name='form' method='post' class = 'pure-form pure-form-aligned' action="<?php $_PHP_SELF ?>">
<center><h2> Add a New User </h2></center>
<div class = 'pure-control-group'>
<label for="user">Username:</label>
<input class="textbox3" type="text" name="userid" id="userid">
</div>

<div class = 'pure-control-group'>
<label for="pass">Password:</label>
<input class="textbox3" type="password" name="pass" id="pass">
</div>

<div class = 'pure-control-group'>
<label for="pass2">Retype Password:</label>
<input class="textbox3" type="password" name="pass2" id="pass2">
</div>

<div class = 'pure-control-group'>
<label for="admin">Admin:</label> 
<select name="admin" id="adminB">
<option selected value="0">No</option>
<option value="1">Yes</option>
</select>
</div>

</div>
<script type="text/javascript">
function show_confirm() {
   var i = document.getElementById("userid").value;
   if (checkEmpty(i)) {
      alert("Enter New Username");
      return false;
   }
   var con="User ID: "+ i + "\n";
   var i = document.getElementById("pass").value;
   if (checkEmpty(i)) {
      alert("Enter Password");
      return false;
   }
   var p = document.getElementById("pass2").value;
   if (checkEmpty(p)) {
      alert("Enter Password Twice (Retype Password)");
      return false;
   }
   if (i != p) {
      alert("Passwords Do Not Match");
      return false;
   }
   var i = document.getElementById("adminB").value;
   var adm = "no";
   if (i == 1) {
      adm = "yes";
   }
   con += "Admin: "+ adm + "\n";
   return confirm("Confirm Entry: " + "\n" + con);
}
</script> 

<br clear="all">
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit"
  onclick = "return show_confirm();">
<?php
if (!empty($_POST['submit'])){
   $admin=$_POST['admin'];
   $userid=escapehtml($_POST['userid']);
   $pass=$_POST['pass'];
   $pass2=$_POST['pass2'];
   if (!empty($userid) && !empty($pass)) {
      if ($pass == $pass2) {
         try {
            $dbcon = new PDO('mysql:host=localhost;dbname=wahlst_users', 'wahlst_usercheck', 'usercheckpass',
               array(PDO::MYSQL_ATTR_INIT_COMMAND => 'set sql_mode="TRADITIONAL"'));
            $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         } catch (PDOException $e) {
            phpAlert("Could not connect to user database", $p);
            die();
         }
         $sql = "select * from users where username = '".$userid."'";
         $result = $dbcon->query($sql);
         if ($result) {
            $ct = 0;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $ct = $ct + 1;
            }
            if ($ct == 0) {
               $pass = escapehtml(crypt($pass, '123salt'));
               $sql = "insert into users values ('".$userid."', '".$pass."', '".$_SESSION['db']."', ".
                  $admin.", 1)";
               try {
                  $stmt = $dbcon->prepare($sql);
                  $stmt->execute();
               } catch (PDOException $p) {
                  echo "<script>alert(\"Could not add user".$p->getMessage()."\");</script>";
                  die();
               }
               echo "<script>showAlert(\"Added User successfully!\");</script> \n";
            } else {
               echo "<script>alert(\"Username already exists: choose another!\");</script>";
            }
         } else {
            echo "<script>alert(\"Could not connect to user database!\");</script>";
         }
      } else {
         echo "<script>alert(\"Passwords do not match!\");</script> \n";
      }
   } else {
      echo "<script>alert(\"Enter all data!\");</script> \n";
   }
}
?>
</body>
</html>
