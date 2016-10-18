<?php

include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/setconfig.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';

echo '<center>';
if ($_SESSION['mobile']) {
   echo '<br clear="all"/>';
   echo '<P>';
}
?>
<h1> Welcome to FARMDATA Version 1.6! <br> Click one of the tabs to begin. </h1>
<br clear="all"/>
<img src='farmdata.png'>
</center>
</body>


