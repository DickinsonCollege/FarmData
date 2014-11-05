<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/escapehtml.php';
$host = "localhost";
$user = $_SESSION['dbuser'];
$pass = $_SESSION['dbpass'];
$dbName = $_SESSION['db'];
$dbcon = mysql_connect($host, $user, $pass);
if ($dbcon) {
   mysql_select_db($dbName);
// or die ("Connect Failed !! : ".mysql_error());
} else {
  // $host = gethostname();
  $host = $_SERVER['HTTP_HOST']."";
  echo '<link type="text/css" href="/design.css" rel = "stylesheet">';
  echo  ("Connection Failed !! : ".mysql_error());
  echo '<br clear="all"/><br clear="all"/>';
  echo '<form method="POST" action="https://'.$host.'"/>';
  echo '<input type ="submit" class="submitbutton" value = "Log In Again">';
  echo '</form>';
  die();
}
?>
