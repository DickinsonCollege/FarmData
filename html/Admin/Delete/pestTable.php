<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';  

if(isset($_GET['submit'])) {
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM pestScout WHERE id=".$_GET['id'];
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
   $fieldID = escapehtml($_GET['fieldID']);
   $pest = escapehtml($_GET['pest']);
   $sql="select id,sDate,crops,fieldID,pest,avgCount,comments from pestScout where sDate between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and crops like '%".$crop."%' and fieldID like '".$fieldID.
         "' and pest like '".$pest."' order by sDate";
      $result=mysql_query($sql);
      if(!$result){
          echo "<script>alert(\"Could not Generate Insect Scouting Report: Please try again!\\n".mysql_error()."\");</script>\n";
      }
      echo "<table border>";
      if ($crop=="%"){
         $var="All Crops";
      } else {
         $var=$_GET['crop'];
      }
      if ($fieldID=="%") {
         $var2="All Fields";
      } else {
         $var2="Field ".$_GET['fieldID'];
      }
      if ($pest=="%") {
         $var3="All Insects";
      } else {
         $var3=$_GET['pest'];
      }
      echo "<caption> Insect Scouting Report for ".$var." in ".
         $var2." for ".$var3."</caption>";
      echo "<tr><th>Scout Date</th><th>Crops</th><th>Field ID</th><th>Insect</th><th>Average Count</th><th>Comments</th><th>Edit</th><th> Delete </th></tr>";
      while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['sDate'];
        echo "</td><td>";
        echo $row['crops'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['pest'];
        echo "</td><td>";
        echo $row['avgCount'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td><td>";
		  echo "<form method='POST' action=\"pestEdit.php?month=".$month."&day=".$day."&year=".
           $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".
           $tcurDay."&id=".$row['id']."&fieldID=".encodeURIComponent($_GET['fieldID'])."&crop=".
           encodeURIComponent($_GET['crop'])."&pest=".encodeURIComponent($_GET['pest']).
           "&tab=admin:admin_delete:deletesoil:deletescout:deletepestscout&submit=Submit\">";
		  echo "<input type='submit' class='editbutton' value='Edit'/></form>";
		  echo "</td><td>";
		  echo "<form method='POST' action=\"pestTable.php?month=".$month."&day=".$day."&year=".
           $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".
           $tcurDay."&id=".$row['id']."&fieldID=".encodeURIComponent($_GET['fieldID'])."&crop=".
           encodeURIComponent($_GET['crop'])."&pest=".encodeURIComponent($_GET['pest']).
           "&tab=admin:admin_delete:deletesoil:deletescout:deletepestscout&submit=Submit\">";
		  echo "<input type='submit' class='deletebutton' value='Delete'/></form>";
	echo "</td></tr>";
      }
   echo "</table>";
}
?>

