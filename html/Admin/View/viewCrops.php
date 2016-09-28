<?php session_start(); ?>
<form name='form' method='POST' action='/down.php'>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];
if ($_SESSION['sales_invoice']) {
   $sql = "select crop, units, units_per_case, dh_units as invoice_units, active from plant";
} else {
   $sql = "select crop, units, active from plant";
}
// $result = $dbcon->query("Select crop, units, units_per_case, dh_units, active from plant");
$result = $dbcon->query($sql);

echo "<table class = 'pure-table pure-table-bordered'>";

// Table Header
echo "<center><h2>Crop Table </h2><center>";
echo "<thead><tr>
	<th>Crop</th>
	<th>Default Unit</th>";
// If Dickinson Version Database, include units_per_case and dh_units
if ($_SESSION['sales_invoice']) {
	echo "<th>Units Per Case</th>
		<th>Invoice Units</th>";
}
echo "<th>Active?</th></tr></thead>";


// Display Data
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo $row['crop'];
        echo "</td><td>";
        echo $row['units'];
        echo "</td>";
	if ($_SESSION['sales_invoice']) {
		echo "<td>";
		echo $row['units_per_case'];
		echo "</td><td>";
		echo $row['invoice_units'];
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
echo '<br clear="all"/>';

echo '<input type="submit" class="submitbutton pure-button wide" name="submit" value="Download Report">';
echo '<br clear = "all">';
echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
echo "</form>";
?>
