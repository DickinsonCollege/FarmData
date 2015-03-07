<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<?php
if(!empty($_GET['submit'])){
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM labor WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $crop = escapehtml($_GET['crop']);
   $origCrop = $_GET['crop'];
   $fieldID = escapehtml($_GET['fieldID']);
   $origFieldID = $_GET['fieldID'];
   $task = escapehtml($_GET['task']);
   $origTask = $_GET['task'];
   $sql = "SELECT id, ldate,username, crop,fieldID,task,hours FROM labor where ldate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and crop like '" .$crop."' and fieldID like '".$fieldID.
      "' and task like '".$task."' order by crop, ldate";
   $sqldata = mysql_query($sql) or die("ERROR: ".mysql_error());
   echo "<table>";
   if($fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = "Field ".$_GET['fieldID'];
   }
   if ($crop == "%") {
      $crp = "All Crops";
   } else {
      $crp = $_GET['crop'];
   }
   if ($task == "%") {
      $tsk = "All Tasks";
   } else {
      $tsk = $_GET['task'];
   }
   echo "<caption>  Labor Report for ".$tsk." for ".$crp." in ".$fld."</caption>";
   echo "<tr><th>Date</th><th>Username</th><th>Crop</th><th>Field</th><th>Task</th><th>Hours</th><th>Edit</th><th>Delete</th></tr>";
   while($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['ldate'];
      echo "</td><td>";
      echo $row['username'];       
      echo "</td><td>";
      echo $row['crop'];       
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['task'];
      echo "</td><td>";
      echo $row['hours'];
      echo "</td><td>";
      echo "<form method='POST' action=\"laborEdit.php?month=".$month."&day=".$day."&year=".
         $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
         "&id=".$row['id']."&crop=".encodeURIComponent($origCrop)."&submit=Submit&fieldID=".
         encodeURIComponent($origFieldID).
         "&tab=admin:admin_delete:deleteother:deletelabor:deletelaborR&task=".encodeURIComponent($origTask)."\">";
		echo "<input type='submit' class='editbutton' value='Edit'/></form>";
		echo "</td><td>";
      echo "<form method='POST' action=\"laborTable.php?month=".$month."&day=".$day."&year=".
         $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
         "&id=".$row['id']."&crop=".encodeURIComponent($origCrop)."&submit=Submit&fieldID=".
         encodeURIComponent($origFieldID).
         "&tab=admin:admin_delete:deleteother:deletelabor:deletelaborR&task=".encodeURIComponent($origTask)."\">";
		echo "<input type='submit' class='deletebutton' value='Delete'/></form>";
      echo "</td></tr>";
      echo "\n";
   }
   echo "</table>";
}
?>
</body>
</html>
