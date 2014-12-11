<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<?php
  
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM diseaseScout WHERE id=".$_GET['id'];
//      echo $sqlDel;
      mysql_query($sqlDel) or die(mysql_error());
      echo mysql_error();
   }

   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $crop = escapehtml($_GET['crop']);
   $disease = escapehtml($_GET['disease']);
   $sql = "select sDate, id, fieldID, disease, crops, infest, stage, comments ".
      "from diseaseScout where sDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' and crops like '%".
      $crop."%' and disease like '".$disease."' order by sDate";
      echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
   //echo $sql;
   $sqldata = mysql_query($sql) or die(mysql_error());
   echo "<table>";
   if( $fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = $_GET['fieldID'];
   } 
   if( $crop == "%") {
      $grp = "All Crops";
   } else {
      $grp = $_GET['crop'];
   } 
   if( $disease == "%") {
      $dis = "All Diseases";
   } else {
      $dis = $_GET['disease'];
   } 
   echo "<caption>Disease Scouting Report for ".$dis." on ".$grp." in Field: ".$fld."  </caption>";
   
   echo "<tr><th>Date</th><th>Field</th><th>Disease</th><th>Crops</th><th>Infestation</th>
			<th>Stage</th><th>Comments</th><th>Edit</th><th>Delete</th></tr>";
   while ($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['sDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['disease'];       
      echo "</td><td>";
      echo $row['crops']; echo "</td><td>";
      echo $row['infest'];
      echo "</td><td>";
      echo $row['stage'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";

      echo "<td><form method=\"POST\" action=\"diseaseEdit.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".
         encodeURIComponent($_GET['fieldID'])."&crop=".encodeURIComponent($_GET['crop']).
         "&disease=".encodeURIComponent($_GET['disease']).
         "&tab=admin:admin_delete:deletesoil:deletescout:deletediseasescout&submit=Submit\">";
      echo "<input type=\"submit\" name=\"submitEdit\" class=\"editbutton\" value=\"Edit\"></form></td>";
      
     echo "<td><form method=\"POST\" action=\"diseaseTable.php?month=".$month."&day=".$day."&year=".
         $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".
         encodeURIComponent($_GET['fieldID'])."&crop=".encodeURIComponent($_GET['crop']).
         "&disease=".encodeURIComponent($_GET['disease']).
           "&tab=admin:admin_delete:deletesoil:deletescout:deletediseasescout&submit=Submit\">";
      echo "<input type=\"submit\" name=\"submit\" class=\"deletebutton\" value=\"Delete\"></form></td>";
      echo "<tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
?>
</div>
