<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   // delete from tSprayMaster, tSprayWater, tSprayField.
   if(isset($_GET['id'])){
      $sqlDel="Delete from tSprayWater where id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   
      $sqlDel="Delete from tSprayField where id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();

      $sqlDel="Delete from tSprayMaster where id=".$_GET['id'];
      mysql_query($sqlDel) or die (mysql_error());
      echo mysql_error();
   }
?>
<center>
<h2> Edit/Delete Tractor Spraying Record </h2>
</center>
<table class="pure-table pure-table-bordered">
<thead><tr>
   <th >Date</th>
   <th># Field</th>
   <th># Material</th>
   <th>Comments</th>
   <th>Complete</th>
   <th>Initials</th>
   <th>Edit</th>
   <th>Delete</th>   
</tr></thead>


<?php
// get date Range
$fromDate=$_GET['year']."-".$_GET['month']."-".$_GET['day'];
$toDate=$_GET['tyear']."-".$_GET['tmonth']."-".$_GET['tday'];
$sql="select id,user, sprayDate, noField, noMaterial, comment, complete, initials ".
   " from tSprayMaster where sprayDate between '$fromDate' and '$toDate' order by sprayDate";
echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
$count=0;
$totalMaterial=0;
$resultM=mysql_query($sql);
echo mysql_error();
// echo table rows
   while($rowM=mysql_fetch_array($resultM)){
      echo "<tr><td>".$rowM['sprayDate']."</td>";
      echo "<td>".$rowM['noField']."</td>";
      echo "<td>".$rowM['noMaterial']."</td>";
      echo "<td>".$rowM['comment']."</td>";
      echo "<td>";
      if ($rowM['complete'] == 1) {
         echo "Yes";
      } else {
         echo "No";
      }
      echo "</td>";
      echo "<td>".$rowM['initials']."</td>";
      echo "<td><form method='POST' action='tSpray.php?user=".$rowM['user'].
           "&date=".$rowM['sprayDate'].
           "&month=".$_GET['month']."&day=".$_GET['day']."&year=".$_GET['year'].
           "&tmonth=".$_GET['tmonth']."&tyear=".$_GET['tyear']."&tday=".$_GET['tday'].
           "&id=".$rowM['id']."&complete=".$rowM['complete']."&initials=".
           escapehtml($rowM['initials']).
           "&tab=soil:soil_spray:tspray:tspray_edit'>";
      echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form></td>";
      echo "<td><form method='POST' action='deleteTspray.php?month=".$_GET['month'].
         "&day=".$_GET['day']."&year=".$_GET['year']."&tmonth=".$_GET['tmonth'].
         "&tyear=".$_GET['tyear']."&tday=".$_GET['tday']."&id=".$rowM['id'].
         "&tab=soil:soil_spray:tspray:tspray_edit'>";
      echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
      echo "onclick='return warn_delete();'></form></td></tr>";
   
   }
echo '</table>';


echo '<br clear = "all"/>';
?>
