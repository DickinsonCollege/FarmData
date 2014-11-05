<?php
session_start();
/*
if(!isset($_SESSION['username'])) {
   header("Location: https://" . $_SERVER['HTTP_HOST'].'/logout.php');
}elseif ($_SESSION['admin']==0) {
   header("Location: https://". $_SERVER['HTTP_HOST']);
}
*/
if (!isset($_SESSION['username']) || $_SESSION['admin']==0) {
   echo '<script type="text/javascript">';
   echo 'alert("Error: you are not authorized to access this page.");';
   echo '</script>';
   echo '<meta http-equiv="refresh" content=0;URL="https://'.
      $_SERVER['HTTP_HOST'].'/logout.php>';
   //header("Location: https://" . $_SERVER['HTTP_HOST'].'/logout.php');
}
?>

