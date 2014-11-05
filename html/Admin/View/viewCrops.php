<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];
$result = mysql_query("Select crop, units, units_per_case, dh_units, active from plant");

echo "<table>";

/**
echo "<colgroup>";
echo "<col width='10px' id='col1'/>";
echo "<col id='col2'/>";
echo "<col id='col3'/>";
echo "<col id='col4'/>";
echo "</colgroup>";
**/

// Table Header
echo "<caption> Crop Table </caption>";
echo "<tr>
	<th>Crop</th>
	<th>Default Unit</th>";
// If Dickinson Version Database, include units_per_case and dh_units
if ($farm == "dfarm") {
	echo "<th>Units Per Case</th>
		<th>DH Units</th>";
}
echo "<th>Active?</th></tr>";


// Display Data
while($row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['crop'];
        echo "</td><td>";
        echo $row['units'];
        echo "</td>";
	// If Dickinson Version Database, include units_per_case and dh_units
	if ($farm == "dfarm") {
		echo "<td>";
		echo $row['units_per_case'];
		echo "</td><td>";
		echo $row['dh_units'];
		echo "</td>";
	}
	echo "<td>";
	// If crop is active, display "yes", otherwise, "no"
        if ($row['active'] == 1) {
		echo "yes";
	} else {
		echo "no";
	}
        echo "</td>";
        echo "\n";
}

echo "</table>";
?>
