<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<?php
  
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM liquid_fertilizer WHERE id=".$_GET['id'];
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
   $material = escapehtml($_GET['material']);
   $sql = "select inputDate, id, fieldID, fertilizer, dripRows, unit, quantity, comments ".
      "from liquid_fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."'".
      "and fertilizer like '".$material."' order by inputDate";
      echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
   //echo $sql;
   $sqldata = mysql_query($sql) or die(mysql_error());
   echo "<table>";
   if( $fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = $_GET['fieldID'];
   } 
   if( $material == "%") {
      $mat = "All Materials";
   } else {
      $mat = $_GET['material'];
   } 
   echo "<caption> Liquid Fertilizer Application Report for ".$mat." in Field: ".$fld."  </caption>";
   
   echo "<tr><th>Date</th><th>Field</th><th>Material</th><th>Drip Rows</th><th>Unit</th>
			<th>Quantity</th><th>Comments</th><th>Edit</th><th>Delete</th></tr>";
   while ($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['inputDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['fertilizer'];       
      echo "</td><td>";
      echo $row['dripRows'];
      echo "</td><td>";
      echo $row['unit'];
      echo "</td><td>";
      //echo $row['quantity'];
      echo number_format((float)$row['quantity'], 2, '.', '');
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";

      echo "<td><form method=\"POST\" action=\"liquidFertilizerEdit.php?month=".$month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".$_GET['fieldID']."&material=".$_GET['material'].
           "&tab=admin:admin_delete:deletesoil:deletefert:deletefertilizer:deleteliquidfertilizer&submit=Submit\">";
      echo "<input type=\"submit\" name=\"submitEdit\" class=\"editbutton\" value=\"Edit\"></form></td>";

      echo "<td><form method=\"POST\" action=\"liquidFertilizerTable.php?month=".$month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
           "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".$_GET['fieldID']."&material=".$_GET['material'].
           "&tab=admin:admin_delete:deletesoil:deletefert:deletefertilizer:deleteliquidfertilizer&submit=Submit\">";
      echo "<input type=\"submit\" name=\"submit\" class=\"deletebutton\" value=\"Delete\"></form>";
      echo "</td><tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
  // echo "<form name='form' method='POST' action='/down.php'>"

/*
   if ($material != "%") {
      $total="Select sum(totalApply) as total from fertilizer where inputDate between '".$year."-".$month.
         "-".$day."' AND '".$tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID.
         "' and cropGroup like '".  $group."' and fertilizer like '".$material."'";

      $result=mysql_query($total) or die(mysql_error());
      while ($row1 = mysql_fetch_array($result)  ) {
        echo "<label for='total'>Total ".$material." Applied:&nbsp;</label>";
	echo "<input disabled class='textbox2' style='width: 120px;' type ='text' value=".
          number_format((float)$row1['total'], 2, '.', '').">";
        echo "&nbsp; pounds";
        echo '<br clear="all"/>';
     }
     echo '<br clear="all"/>';
  }
*/

?>
</div>
