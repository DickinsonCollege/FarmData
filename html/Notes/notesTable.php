<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
if (isset($_POST['submit'])) {
   $result=mysql_query("Select username,comDate,comments from comments where comDate between '".$_POST['year']."-".$_POST['month']."-".$_POST['day']."' 
   and '".$_POST['tyear']."-".$_POST['tmonth']."-".$_POST['tday']."'");
   echo "<table>";
   echo "<colgroup> <col id='col1'/>";
   echo "<col id='col2' />";
   echo "<col id='col3' />";
   echo "</colgroup>";
   echo "<caption> Comments Report </caption>";
   echo "<tr><th>UserName</th><th> Date </th><th>Comments</th></tr>";
   while($row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['username'];
        echo "</td><td>";
        echo $row['comDate'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td></tr>";
        echo "\n";
   }
   echo "</table>";
}
?>
<br clear="all"/>
<form method="POST" action = "/Notes/viewNote.php?tab=notes:notes_report"><input type="submit" class="submitbutton" value = "View Another Date Range"></form>
