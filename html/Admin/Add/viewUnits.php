<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

if (isset($_GET['crop']) && isset($_GET['unit'])) {
   $crop = escapehtml($_GET['crop']);
   $unit = escapehtml($_GET['unit']);
   $crop = $_GET['crop'];
   $unit = $_GET['unit'];
   if (isset($_GET['delete'])) {
echo      $sql = "delete from  units where crop='".$crop."' and unit='".$unit."'";
   } else if (isset($_GET['edit']) && isset($_GET['rowNum'])) {
      $sql = "update units set conversion=".$_POST['conv'.$_GET['rowNum']]." where crop='".$crop.
        "' and unit='".$unit."'";
   }
   $res = mysql_query($sql);
   echo mysql_error();
}
$result=mysql_query("Select crop,default_unit,unit,conversion from units");

echo "<table>";

/*
echo "<colgroup>";
echo "<col width='10px' id='col1'/>";
echo "<col id='col2'/>";
echo "<col id='col3'/>";
echo "<col id='col4'/>";
echo "<col id='col5'/>";
echo "</colgroup>";
*/

echo "<caption> Units Table </caption>";
echo "<tr>
	<th>Crop</th>
	<th>Default Unit</th>
	<th>Unit</th>
	<th>Conversion</th>
        <th>Update</th><th>Delete</th></tr>";
$rowNum = 0;
while($row = mysql_fetch_array($result)) {
   $rowNum++;
   echo "<tr><td>";
   echo $row['crop'];
   echo "</td><td>";
   echo $row['default_unit'];
   echo "</td><td>";
   echo $row['unit'];
   echo "</td><td>";
   if ($row['default_unit'] == $row['unit']) {
      echo $row['conversion']."</td>";
      echo "<td>No</td><td>No";
   } else {
      echo "<form method=\"POST\" action=\"viewUnits.php?&crop=".encodeURIComponent($row['crop']).
        "&edit=1&unit=".encodeURIComponent($row['unit'])."&rowNum=".$rowNum.
        "&tab=admin:admin_delete:deleteother:deleteunit\">";
      echo '<input onkeypress= "stopSubmitOnEnter(event);" name="conv'.$rowNum.'" id="conv'.$rowNum.
         '" value="'.$row['conversion'].'" class="textbox2 mobile-input" type="text" style="width:100%">';
      echo "</td><td>";
      echo "<input type=\"submit\" class=\"submitbutton\" value=\"Update\"></form> </td><td>";
      echo "<form method=\"POST\" action=\"viewUnits.php?&crop=".encodeURIComponent($row['crop']).
        "&delete=1&unit=".encodeURIComponent($row['unit']).
        "&tab=admin:admin_delete:deleteother:deleteunit\">";
      echo '<input type="submit" class="deletebutton" value="Delete" style="width:100%"></form>';
   }
   echo "</td></tr>";
   echo "\n";
}

echo "</table>";
?>
