<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
if(isset($_GET['submit'])){
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM coverSeed WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
		$sqlDeleteMaster = 'Delete from coverSeed_master where id='.$_GET['id'];
		mysql_query($sqlDeleteMaster);
		echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $sqlget = "SELECT id,fieldID, area_seeded, seed_method, incorp_tool, comments, seedDate FROM coverSeed_master where seedDate BETWEEN '".
       $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
       $tcurDay."' and fieldID like '".$fieldID."' order by seedDate";
   $sqldata = mysql_query($sqlget);
   echo mysql_error();
   echo "<table border>";
	$field = $_GET['fieldID'];
	if ($_GET['fieldID']=='%'){$field = 'All';}
	echo "<caption> Cover Crop Seeding in Field: ".$field." </caption>";
   echo "<tr><th >Date</th><th>Field ID</th><th>% Field Seeded</th>".
     "<th> Seed Method </th><th> Incorporation Tool</th>".
     "<th> Comments</th><th> Edit</th><th> Delete </th></tr>";
   while($row = mysql_fetch_array($sqldata)) {
	echo "<tr><td>";
	echo $row['seedDate'];
	echo "</td><td>";
	echo $row['fieldID'];
	echo "</td><td>";
	echo $row['area_seeded'];
	echo "</td><td>";
	echo $row['seed_method'];
	echo "</td><td>";
	echo $row['incorp_tool'];
	echo "</td><td>";
	echo $row['comments'];
	echo "</td>";

	echo "<td><form method='POST' action=\"coverEdit.php?month=".$month."&day=".$day."&year=".$year.
		"&tmonth=".$tcurMonth."&tday=".$tcurDay."&tyear=".$tcurYear."&id=".$row['id'].
		"&fieldID=".encodeURIComponent($_GET['fieldID']).
		"&tab=admin:admin_delete:deletesoil:deletefert:deletecover:deletecoverseed&submit=Submit\">";
	echo "<input type='submit' class='editbutton' value='Edit'";
        echo 'onclick="return show_warning();">';
        echo "</form></td>";

	echo "<td><form method='POST' action=\"coverTable.php?month=".$month."&day=".$day."&year=".$year.
		"&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
		"&fieldID=".encodeURIComponent($_GET['fieldID']).
		"&tab=admin:admin_delete:deletesoil:deletefert:deletecover:deletecoverseed&submit=Submit\">";
	echo "<input type='submit' class='deletebutton' value='Delete'";
        echo 'onclick="return show_warning();">';
        echo "</form></td>";

	echo "</tr>";

   }
   echo "</table>";
} else {
   echo'<br>Error: Please try resubmitting the request <a href="coverReport.php">here </a>';
}
?>
</div>
</body>
</html>
