<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$result=mysql_query("Select unit from extUnits");

echo "<table>";
echo "<caption> Units Table </caption>";
echo "<tr><th>Unit</th></tr>";
while($row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['unit'];
        echo "</td></tr>";
        echo "\n";
}
echo "</table>";
