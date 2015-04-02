<?php session_start();?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
if(isset($_POST['submit'])){
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $tcurYear = $_POST['tyear'];
   $tcurMonth = $_POST['tmonth'];
   $tcurDay = $_POST['tday'];
   $crop = escapehtml($_POST['crop']);
   $fieldID = escapehtml($_POST['fieldID']);
   $task = escapehtml($_POST['task']);
   $sql = "SELECT ldate,crop,fieldID,task,hours,comments FROM laborview where ldate BETWEEN '".
       $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth.
       "-".$tcurDay."' and crop like '" .$crop."' and fieldID like '".
       $fieldID."' and task like '".$task.
       "' and hours > 0 order by crop, ldate";
   $sqldata = mysql_query($sql) or die("ERROR");
   echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
   echo "<table>";
   if($fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = "Field ".$fieldID;
   }
   if ($crop == "%") {
      $crp = "All Crops";
   } else {
      $crp = $crop;
   }
   if ($task == "%") {
      $tsk = "All Tasks";
   } else {
      $tsk = $task;
   }
   echo "<caption>  Labor Report for ".$tsk." for ".$crp." in ".$fld."</caption>";
   echo "<tr><th>Date of Labor</th><th>Crop</th><th>Name of Field</th><th>Task</th><th>Hours</th><th> &nbsp  Comments &nbsp </th></tr>";
   while($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['ldate'];
      echo "</td><td>";
      echo $row['crop'];       
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['task'];
      echo "</td><td>";
      echo number_format((float) $row['hours'], 2, '.', '');
      echo "</td><td>";
      echo $row['comments'];
      echo "</td></tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   $sql2 = "SELECT sum(hours) as total FROM laborview where ldate BETWEEN '".
       $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
       $tcurDay."' and crop like '" .$crop."' and fieldID like '".$fieldID.
       "' and task like '".$task."'";
   $result=mysql_query($sql2);
   while ($row1 = mysql_fetch_array($result)  ) {
      echo "<label for='total'>Total Hours:&nbsp</label>";
      echo "<input class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".
         number_format((float) $row1['total'], 1, '.', '').">";
   }
   echo '<br clear="all"/>';
}
   echo '<br clear="all"/>';
        echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';
echo '</form>';
echo '<form method="POST" action = "/Labor/laborReport.php?tab=labor:labor_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
</body>
</html>
