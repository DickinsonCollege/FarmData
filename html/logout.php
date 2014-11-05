<?php session_start(); ?>
<?php
$farm = $_SESSION['db'];
// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
include 'authentication.php';
if($_SESSION['mobile']) {
   echo '<link type="text/css" href="/mobileDesign2.css" rel = "stylesheet">';
}else {
   echo '<link type="text/css" href="/design.css" rel = "stylesheet">';
}
echo '<body id = "logout">';
echo '<br clear="all"/>';
if ($farm == 'dfarm') {
#   $url = "login.php";
   echo 'To Log Out Completely, Close Your Browser!';
} else {
#   $url = "extlogin.php";
   echo 'Thank You for Using FARMDATA!';
}
$url = "https://".$_SERVER['HTTP_HOST']."/";
echo '<br clear="all"/><br clear="all"/>';
echo '<form method="POST" action="'.$url.'">';
echo '<input type ="submit" class="submitbutton" value = "Log In Again">';
echo '</form>';
?>
</body>
