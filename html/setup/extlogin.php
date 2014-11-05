<?php
// HTTPSON
if($_SERVER["HTTPS"] != "on") {
   header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
   exit();
}
// HTTPSOFF
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/utilities.php';
$useragent=$_SERVER['HTTP_USER_AGENT'];
$_SESSION['mobile'] = isMobile($useragent);
// $_SESSION['mobile'] = 1;
if($_SESSION['mobile']) {
  echo '<link type="text/css" href="/mobileDesign2.css" rel = "stylesheet">';
}else {
  echo '<link type="text/css" href="/design.css" rel = "stylesheet">';
}
echo '<link type="text/css" href="/tabs.css" rel = "stylesheet">';
?>
<body>
<h1>FARMDATA Login</h1> <br>
<form name='form' id='test'  method='POST' action="validate.php">
<label for="username"><b>Username:&nbsp;</b></label>
<input type="text" class = "textbox3" name="username" id="username">
<br clear="all">
<label for="pass"><b>Password:&nbsp;</b></label>
<input type="password" class = "textbox3" name="pass" id="pass">
<br clear="all">
<p>
<input class = "submitbutton" type="submit" value = "Submit">
<script type="text/javascript">
  var u = document.getElementById("username");
  u.focus();
</script>
</form>

<p>

</body>

