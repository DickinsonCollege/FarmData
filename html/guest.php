<?php
session_start();
$_SESSION['dbuser'] = "guest";
$_SESSION['user'] = 0;
$_SESSION['username'] = 'guest';
$_SESSION['dbpass'] = "guest";
$_SESSION['db'] = "dfarm";
$_SESSION['admin'] = 1;
include 'connection.php';
include 'authentication.php';
include 'setconfig.php';
include 'design.php';
include 'footer.php';
?>
<h1> Welcome to the College Farm!</h1> <br>
<!--
Please Click on One of the Tabs to Meet Your Needs! 
-->
Guest users: 
the FARMDATA users manual is available at:
<a href="http://sourceforge.net/p/farmdata/wiki/Manual/">http://sourceforge.net/p/farmdata/wiki/Manual/</a>
<p>
As a guest user, you will be able to access all FARMDATA features (including administrative functionality),
but you will not be able to enter or edit any data.  Attempts to enter data or modify FARMDATA 
configuration will result in error messages.
<p>
FARMDATA is an open source project.  If you would like to download and install FARMDATA on your
own web server, see:
<a href="http://sourceforge.net/projects/farmdata/">http://sourceforge.net/projects/farmdata/</a>
<br>
Installation and configuration instructions are available at:
<a href="http://sourceforge.net/p/farmdata/wiki/Home/">http://sourceforge.net/p/farmdata/wiki/Home/</a>
<!-- 
such as:
<pre>
INSERT command denied to user 'guest'@'localhost' for table 'harvested'
</pre>
-->
<!--
click <a href="FARMDATAGuestUserGuide.html">here</a> for instructions
on using FARMDATA.  After reading (or printing) the user's guide,
use the "back" button in your browser to return to FARMDATA.
-->
<p>
<!--
<img src="http://www2.dickinson.edu/storg/sisa/images/2007%20Images/farmstand/_DSC2056.JPG">
-->
<center>
<img src="farmdata.png">
</center>

