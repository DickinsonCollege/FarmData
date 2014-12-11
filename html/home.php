<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/setconfig.php';
// include $_SERVER['DOCUMENT_ROOT'].'/navigation.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
?>
<center>
<?php 
if (!$_SESSION['mobile']) {
   echo '<p>';
   echo '<h1> Welcome to the College Farm! <br clear="all"/> Select One of the Tabs Above. </h1>';
} else {
   echo '<br clear="all"/>';
   echo '<p>';
   echo '<font size=4> Welcome to the College Farm! <br clear="all"/> Select One of the Tabs Above. </font>';
}
?>
<p>
<?php
if (!$_SESSION['mobile']) {
   echo "<img src='farmdata.png'>"; 
} else { 
   //echo "<img src='farmdata.png' style='padding-top:20px;' width='100%'>";
   echo "<img src='farmdata.png' width='100%'>";
}
?>
<!--
<?php if (!$_SESSION['mobile']) echo "<img src='FOTS.jpg'>"; ?>
-->
</center>
</body>
