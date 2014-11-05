<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

if(!empty($_GET['submit'])){
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM compost_activity WHERE id=".$_GET['id'];
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
      $act = escapehtml($_GET['act']);
      $sql = "SELECT * from compost_activity where actDate between '".$year."-".$month."-".$day.
         "' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and pileID like '".$pileID.
         "' and activity like '".$act."' order by actDate";
      $result=mysql_query($sql);
      if(!$result){
         echo "<script>alert(\"Could not Generate Compost Activity Report: Please try again!\\n".
            mysql_error()."\");</script> \n";
      }
      echo '<br clear="all"/>';
      echo "<table border>";
      echo "<caption> Compost Activity Records</caption>";
      echo "<tr><th>Date</th><th>Pile ID</th><th>Activity</th>
          <th>Comments</th><th>Edit</th><th>Delete</th></tr>";
      while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['actDate'];
        echo "</td><td>";
        echo $row['pileID'];
        echo "</td><td>";
        echo $row['activity'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td>";
        echo "<td><form method='POST' action=\"compostActEdit.php?month=".$month."&day=".$day."&year=".
           $year.  "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
           "&pileID=".encodeURIComponent($_GET['pileID']).
           "&act=".encodeURIComponent($_GET['act']).
         "&tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostact&submit=Submit\">";
        echo "<input type='submit' class='editbutton' value='Edit'></form></td>";

        echo "<td><form method='POST' action=\"compostActTable.php?month=".$month."&day=".$day."&year=".
         $year.  "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&pileID=".encodeURIComponent($_GET['pileID']).
         "&act=".encodeURIComponent($_GET['act']).
         "&tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostact&submit=Submit\">";
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
