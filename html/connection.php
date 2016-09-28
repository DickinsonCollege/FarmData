<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/escapehtml.php';
$host = "localhost";
$user = $_SESSION['dbuser'];
$pass = $_SESSION['dbpass'];
$dbName = $_SESSION['db'];
try {
   $dbcon = new PDO("mysql:host=".$host.";dbname=".$dbName, $user, $pass,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"'));
   $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  $host = $_SERVER['HTTP_HOST']."";
  echo '<link type="text/css" href="/design.css" rel = "stylesheet">';
  echo  ("Connection Failed !! : ".$e->getMessage());
  echo '<br clear="all"/><br clear="all"/>';
$method = "http";
// HTTPSON
$method = "https";
// HTTPSOFF

  echo '<form method="POST" action="'.$method.'://'.$host.'"/>';
  echo '<input type ="submit" class="submitbutton" value = "Log In Again">';
  echo '</form>';
  die();
}
?>
