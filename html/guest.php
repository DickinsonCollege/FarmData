<body id = "home">
<?php
session_start();
$_SESSION['dbuser'] = "guest";
$_SESSION['user'] = 0;
$_SESSION['username'] = 'guest';
$_SESSION['dbpass'] = "guest";
$_SESSION['db'] = "dfarm";
$_SESSION['bigfarm'] = 0;
include 'connection.php';
include 'design.php';
?>
<h1> Welcome to the College Farm!</h1> <br>
<!--
Please Click on One of the Tabs to Meet Your Needs! 
-->
Guest users: 
click <a href="FARMDATAGuestUserGuide.html">here</a> for instructions
on using FARMDATA.  After reading (or printing) the user's guide,
use the "back" button in your browser to return to FARMDATA.
<p>
<!--
<img src="http://www2.dickinson.edu/storg/sisa/images/2007%20Images/farmstand/_DSC2056.JPG">
-->
<center>
<img src="FOTS.jpg">
</center>


</body>

