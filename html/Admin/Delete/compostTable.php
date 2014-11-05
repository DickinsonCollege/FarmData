<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

if(!empty($_GET['submit'])){
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM utilized_on WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }
      $year = $_GET['year'];
      $month = $_GET['month'];
      $day = $_GET['day'];
      $tcurYear = $_GET['tyear'];
      $tcurMonth = $_GET['tmonth'];
      $tcurDay = $_GET['tday'];
      $fieldID = escapehtml($_GET['fieldID']);
      $sql = "SELECT id,util_date,fieldID,incorpTool, pileID,tperacre,incorpTiming,fieldSpread,comments from utilized_on where util_date between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID."' order by util_date";
      $result=mysql_query($sql);
      if(!$result){
         echo "<script>alert(\"Could not Generate Compost Reports: Please try again!\\n".mysql_error()."\");</script> \n";
      }
      echo '<br clear="all"/>';
      echo "<table border>";
      if($fieldID == "%") {
         echo "<caption> Compost Records for All Fields</caption>";
      } else{
         echo "<caption> Compost Records for Field: ".$fieldID."</caption>";
      }
      echo "<tr><th>Utilized Date</th><th>Field ID</th><th>Pile ID</th><th>Incorporation Tool</th><th>Incorporation Timing</th>
		<th>Tons per Acre</th><th>Percent Field Spread</th><th>Comments</th><th>Edit</th><th>Delete</th></tr>";
      while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['util_date'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['pileID'];
        echo "</td><td>";
		  echo $row['incorpTool'];
        echo "</td><td>";
        echo $row['incorpTiming'];
        echo "</td><td>";
        echo $row['tperacre'];
        echo "</td><td>";
        echo $row['fieldSpread'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td>";
	
		echo "<td><form method='POST' action=\"compostEdit.php?month=".$month."&day=".$day."&year=".$year.
			"&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
                        "&fieldID=".encodeURIComponent($_GET['fieldID']).
			"&tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostapp&submit=Submit\">";
		echo "<input type='submit' class='editbutton' value='Edit'></form></td>";

		echo "<td><form method='POST' action=\"compostTable.php?month=".$month."&day=".$day."&year=".$year.
			"&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
                        "&fieldID=".encodeURIComponent($_GET['fieldID']).
			"&tab=admin:admin_delete:deletesoil:deletefert:deletecompost:deletecompostapp&submit=Submit\">";
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
