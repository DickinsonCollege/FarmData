<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
if (isset($_GET['id'])) {
    $sqlDel = "DELETE FROM comments WHERE id = ".$_GET['id'];
    try {
       $stmt = $dbcon->prepare($sqlDel);
       $stmt->execute();
    } catch (PDOException $p) {
       die($p->getMessage());
    }
}
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

if (isset($_GET['submit'])) {   
   $sql= "Select id,username,comDate,comments,filename from comments where comDate between '".
      $_GET['year']."-".$_GET['month']."-".$_GET['day']."'and '".
      $_GET['tyear']."-".$_GET['tmonth']."-".$_GET['tday']."'";
   $result=$dbcon->query($sql);
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<colgroup> <col id='col1'/>";
   echo "<col id='col2' />";
   echo "<col id='col3' />";
   echo "</colgroup>";
   echo "<center><h2> Comments Report </h2></center>";
   echo "<thead><tr><th>UserName</th><th> Date </th><th>Comments</th><th>Picture</th><th>Edit</th><th>Delete</th></tr></thead>";
   while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo $row['username'];
        echo "</td><td>";
        echo $row['comDate'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td><td>";
        $filename = $row['filename'];
        if ($filename == "") {
           echo "&nbsp;";
        } else {
           $width = "200";
           $pos = strrpos($filename, ".");
           $ext = substr($filename, $pos + 1);
           if ($_SESSION['mobile']) {
              $width = "80";
           }
           echo '<img style="width:'.$width.'px" src="'.$filename.'"/>';
        }
        echo "</td>";
        echo "\n";
	if ($_SESSION['admin'] OR $row['username'] == $_SESSION['username']) {
	    echo "<td><form method =\"POST\" action = \"notesEdit.php?id=".$row['id']."&day=".$day."&month=".$month."&year=".$year."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&tab=notes:notes_report&submit=Submit\">";
	    echo "<input type = \"submit\" class = \"editbutton pure-button\" value = \"Edit\"></form></td>"; 
	    echo "<td><form method =\"POST\" action = \"notesTable.php?id=".$row['id']."&day=".$day."&month=".$month."&year=".$year."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&tab=notes:notes_report&submit=Submit\">";
	    echo "<input type = \"submit\" class = \"deletebutton pure-button\" value = \"Delete\"".
               " onclick='return warn_delete();'></form></td>";
        }
	else {
		echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
	}
   }
   echo "</table>";
}
?>
<br clear="all"/>
<form method="GET" action = "/Notes/viewNote.php?tab=notes:notes_report"><input type="submit" class="submitbutton pure-button wide" value = "View Another Date Range"></form>

