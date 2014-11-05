<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

if(isset($_GET['submit'])){
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM gh_seeding WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }
   if(!empty($_GET['crop'])){
      $year = $_GET['year'];
      $month = $_GET['month'];
      $day = $_GET['day'];
      $tcurYear = $_GET['tyear'];
      $tcurMonth = $_GET['tmonth'];
      $tcurDay = $_GET['tday'];
      $crop = escapehtml($_GET['crop']);

      $sqlget = "SELECT username, plant.crop, varieties, id,seedDate,numseeds_planted,comments 
         FROM gh_seeding,plant where seedDate 
         BETWEEN '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
         AND gh_seeding.crop LIKE '" .$crop."' AND plant.crop = gh_seeding.crop order by seedDate";
      $sqldata = mysql_query($sqlget);
      if(!$sqldata) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
      } else{
         echo "<table border>";
         if ($crop == "%") {
            $crp = "All Crops";
         } else {
            $crp = $_GET['crop'];
         }
         echo "<caption> GreenHouse Seeding Report for ".$crp." </caption>";
         echo "<tr><th>Seed Date</th><th>Username</th><th>Crop</th>
            <th>Number of Seeds Planted</th><th>Varieties</th>
            <th>Comments</th><th>Edit</th><th>Delete</th></tr>";
         while($row = mysql_fetch_array($sqldata)) {
            echo "<tr><td>";
            echo $row['seedDate'];
            echo "</td><td>";
            echo $row['username'];
            echo "</td><td>";
            echo $row['crop'];
            echo "</td><td>";
            echo $row['numseeds_planted'];
            echo "</td><td>";
            echo $row['varieties'];
            echo "</td><td>";
            echo $row['comments'];
            echo "</td>";
   
            echo "<td><form method='POST' action=\"ghEdit.php?month=".$month."&day=".$day."&year=".$year.
               "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
              "&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop']).
               "&tab=admin:admin_delete:deleteseed:deleteflats&submit=Submit\">";
            echo "<input type='submit' class='editbutton' value='Edit'></form></td>";
      
            echo "<td><form method='POST' action=\"ghTable.php?month=".$month."&day=".$day."&year=".$year.
               "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
               "&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop']).
               "&tab=admin:admin_delete:deleteseed:deleteflats&submit=Submit\">";
            echo "<input type='submit' class='deletebutton' value='Delete'></form></td>";
   
            echo "</tr>";
         }
        echo "</table>";
      }
   } else {
        echo "<script>alert(\"No crop selected!\");</script>\n";
   }
} else {
   echo'<br>Error: Please try resubmitting the request <a href="gh_seedingReport.php">here </a>';
}
?>
</div>
</body>
</html>
