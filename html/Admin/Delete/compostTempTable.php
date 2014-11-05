<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

if(!empty($_GET['submit'])){
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM compost_temperature WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }
      $year = $_GET['year'];
      $month = $_GET['month'];
      $day = $_GET['day'];
      $tcurYear = $_GET['tyear'];
      $tcurMonth = $_GET['tmonth'];
      $tcurDay = $_GET['tday'];
      $pileID = escapehtml($_GET['pileID']);
      $sql = "SELECT * from compost_temperature where tmpDate between '".$year."-".$month."-".$day.
         "' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and pileID like '".$pileID.
         "' order by tmpDate";
      $result=mysql_query($sql);
      if(!$result){
         echo "<script>alert(\"Could not Generate Compost Temperature Report: Please try again!\\n".
            mysql_error()."\");</script> \n";
      }
      echo '<br clear="all"/>';
      echo "<table border>";
      echo "<caption> Compost Temperature Records</caption>";
      echo "<tr><th>Date</th><th>Pile ID</th><th>Temperature</th><th>Number of Readings</th>
          <th>Comments</th><th>Edit</th><th>Delete</th></tr>";
      while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['tmpDate'];
        echo "</td><td>";
        echo $row['pileID'];
        echo "</td><td>";
        echo number_format((float) $row['temperature'], 2, '.', '');
        echo "</td><td>";
        echo $row['numReadings'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td>";
        echo "<td><form method='POST' action=\"compostTempEdit.php?month=".$month."&day=".$day."&year=".
           $year.  "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
           "&pileID=".encodeURIComponent($_GET['pileID']).
         "&tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecomposttemp&submit=Submit\">";
        echo "<input type='submit' class='editbutton' value='Edit'></form></td>";

        echo "<td><form method='POST' action=\"compostTempTable.php?month=".$month."&day=".$day."&year=".
         $year.  "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&pileID=".encodeURIComponent($_GET['pileID']).
         "&tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecomposttemp&submit=Submit\">";
      echo "<input type='submit' class='deletebutton' value='Delete'></form></td>";

   echo "</tr>";

      }
      echo "</table>";
      echo '<br clear="all"/>';
}
?>
</div>
</body>
</html>
