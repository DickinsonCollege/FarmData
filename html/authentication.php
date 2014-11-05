<?php
#session_start();
if(!$_SESSION['dbuser'] == 'guest') {
if(!isset($_SESSION['username'])) {
#     header("Location: https://" . $_SERVER['HTTP_HOST'] . "/login.php");
    }
}
?>

