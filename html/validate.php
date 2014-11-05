<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/escapehtml.php';
$dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or die ("Connect Failed! :".mysql_error());
mysql_select_db('wahlst_users');
$pass = escapehtml(crypt($_POST['pass'], '123salt'));
$user = $_POST['username'];
$user = escapehtml($user);
$sql = "select active, dbuser, username, users.passwd as upasswd, farms.dbase, admin, farms.passwd as fpasswd from users, farms where username = '".$user."' and users.dbase = farms.dbase";
$result = mysql_query($sql);
if ($result) {
  $ct = 0;
  $dbpass = "";
  $db = "";
  while ($row = mysql_fetch_array($result)) {
    $userpass = $row['upasswd'];
    $db = $row['dbase'];
    $farmpass = $row['fpasswd'];
    $admin = $row['admin'];
    $dbuser = $row['dbuser'];
    $active = $row['active'];
    $ct = $ct + 1;
  }
  if ($ct == 0) {
     echo "No such user: ".$user;
     echo "<br clear = \"all\">";
     echo '<a href="extlogin.php">Try again</a>'; 
  } else if ($ct == 1) {
     if ($pass == $userpass) { 
        if ($active) {
           $_SESSION['dbuser'] = $dbuser;
           $_SESSION['admin'] = $admin;
           $_SESSION['username'] = $user;
           $_SESSION['dbpass'] = $farmpass;
           $_SESSION['db'] = $db;
           $_SESSION['bigfarm'] = ($db == 'wahlst_spiralpath');
           header("Location: exthome.php");
        } else {
           echo "<script>alert(\"Fatal error: user account is not active\");</script>";
        }
     } else {
        echo "Invalid password for: ".$user;
        echo "<br clear = \"all\">";
        echo '<a href="extlogin.php">Try again</a>'; 
     }
  } else {
     echo "<script>alert(\"Fatal error: duplicate username\");</script>";
  }
} else {
   echo "<script>alert(\"Could not connect to user database\");</script>";
}
?>
