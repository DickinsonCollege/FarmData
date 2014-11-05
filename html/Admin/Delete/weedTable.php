<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<?php
if(!empty($_GET['submit'])){
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM weedScout WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
   }else {
      echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $species = escapehtml($_GET['species']);
   $sql = "SELECT id, sDate,fieldID,weed,infestLevel, goneToSeed, comments FROM weedScout where sDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' and weed like '".
      $species."' order by weed, sDate";
   $sqldata = mysql_query($sql) or die("ERROR: ".mysql_error());
   echo "<table>";
   if($fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = "Field ".$_GET['fieldID'];
   }
   if ($species == "%") {
      $crp = "All Species";
   } else {
      $crp = $_GET['species'];
   }
   echo "<caption>  Weed Scouting Report for ".$tsk." for ".$crp." in ".$fld."</caption>";
   echo "<tr><th>Date</th><th>Field</th><th>Species</th> <th>Infestation</th>".
     "<th>GoneToSeed</th><th>Comments</th><th>Edit</th><th>Delete</th></tr>";
   while($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['sDate'];
      echo "</td><td>";
      echo $row['fieldID'];       
      echo "</td><td>";
      echo $row['weed'];       
      echo "</td><td>";
      echo $row['infestLevel'];       
      echo "</td><td>";
      echo $row['goneToSeed'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td><td>";
      echo "<form method='POST' action=\"weedEdit.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&tab=admin:admin_delete:deletesoil:deletescout:deleteweedscout&submit=Submit&fieldID=".
         encodeURIComponent($_GET['fieldID'])."&species=".encodeURIComponent($_GET['species'])."\">";
      echo "<input type='submit' class='editbutton' value='Edit'/></form>";
      echo "</td><td>";
      echo "<form method='POST' action=\"weedTable.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&tab=admin:admin_delete:deletesoil:deletescout:deleteweedscout&submit=Submit&fieldID=".
         encodeURIComponent($_GET['fieldID'])."&species=".encodeURIComponent($_GET['species'])."\">";
      echo "<input type='submit' class='deletebutton' value='Delete'/></form>";
      echo "</td></tr>";
      echo "\n";
   }
   echo "</table>";
}
?>
</body>
</html>
