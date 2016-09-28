<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$result = $dbcon->query("Select material,unit,pounds,cubicyards from compost_units");

echo "<table class='pure-table pure-table-bordered'>";

/*
echo "<colgroup>";
echo "<col width='10px' id='col1'/>";
echo "<col id='col2'/>";
echo "<col id='col3'/>";
echo "<col id='col4'/>";
echo "<col id='col5'/>";
echo "</colgroup>";

echo "<caption> Units Table </caption>";
*/
echo "<thead><tr>
<th>Material</th>
<th>Unit</th>
<th>Pounds</th>
<th>Cubic Yards</th></tr></thead>";
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<tr><td>";
   echo $row['material'];
   echo "</td><td>";
   echo $row['unit'];
   echo "</td><td>";
   echo $row['pounds'];
   echo "</td><td>";
   echo $row['cubicyards'];
   echo "</td></tr>";
   echo "\n";
}

echo "</table>";
?>
