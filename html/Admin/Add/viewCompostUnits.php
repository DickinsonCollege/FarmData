<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$result=mysql_query("Select material,unit,pounds,cubicyards from compost_units");

echo "<table>";

echo "<colgroup>";
echo "<col width='10px' id='col1'/>";
echo "<col id='col2'/>";
echo "<col id='col3'/>";
echo "<col id='col4'/>";
echo "<col id='col5'/>";
echo "</colgroup>";

echo "<caption> Units Table </caption>";
echo "<tr>
	<th>Material</th>
	<th>Unit</th>
	<th>Pounds</th>
	<th>Cubic Yards</th></tr>";
while($row = mysql_fetch_array($result)) {
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
