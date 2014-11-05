<?php session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<link rel="stylesheet" href="/pure-release-0.5.0/pure-min.css">
<form name='form' method='POST' action='/down.php'>
<?php
if(isset($_POST['submit'])) {
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$tcurYear = $_POST['tyear'];
	$tcurMonth = $_POST['tmonth'];
	$tcurDay = $_POST['tday'];
	$crop_product = escapehtml($_POST['crop_product']);
	$target = escapehtml($_POST['target']);
	$grade = $_POST['grade'];
	$bringback = $_POST['bringback'];

	$sql = "SELECT packDate, crop_product, grade, amount, unit, comments, bringBack, target FROM pack 
		WHERE packDate BETWEEN '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND pack.crop_product like '".$crop_product."' AND pack.target like '".$target."' AND pack.grade like '".$grade."'";
	if ($bringback != "%") {
		$sql .= " AND bringback=".$bringback;
	}
	$sql .= " ORDER BY packDate, crop_product, target, grade";
	echo "<input type=\"hidden\" name=\"query\" value=\"".escapehtml($sql)."\">";
	
	$result = mysql_query($sql);
	
	echo "<table class='pure-table'>";
	$crpProd = $_POST['crop_product'];
	if ($crpProd === "%") {
		$crpProd = "All Crops/Products";
	}
	$trg = $_POST['target'];
	if ($trg === "%") {
		$trg = "All Targets";
	}
	$grd = $_POST['grade'];
	if ($grd === "%") {
		$grd = "All";
	}
	if ($year == $tcurYear && $month == $tcurMonth && $day == $tcurDay) {
		$monthName = date('F', mktime(0, 0, 0, $month, 10));
		$dat = "On Date: ".$monthName." ".$day." ".$year;
	} else {
		$monthName = date('F', mktime(0, 0, 0, $month, 10));
		$tcurMonthName = date('F', mktime(0, 0, 0, $tcurMonth, 10));
		$dat = "From: ".$monthName." ".$day." ".$year."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To: ".$tcurMonthName." ".$tcurDay." ".$tcurYear;
	}

	echo "<caption>Packing Report for ".$crpProd."<br>
			To: ".$trg." of Grade: ".$grd."<br>
			".$dat."</caption>";

	echo "<tr><th>Date</th>
		<th>Crop/Product</th>
		<th>Target</th>
		<th>Grade</th>
		<th>Amount</th>
		<th>Unit</th>
		<th style='width:20%'>Comments</th>
		<th>Bring Back</th></tr>";
	$count = 0;	
	while ($row = mysql_fetch_array($result)) {
		if ($count %2 ==1){
			echo "<tr class='pure-table-odd'>";
		} else {
			echo "<tr>";
		}
		$count ++;
		echo "<td>";
		echo $row['packDate'];
		echo "</td><td>";
		echo $row['crop_product'];
		echo "</td><td>";
		echo $row['target'];
		echo "</td><td>";
		echo $row['grade'];
		echo "</td><td>";
                $amount = $row['amount'];
                $unit = $row['unit'];
		$convsql = "SELECT conversion FROM units WHERE crop='".$row['crop_product'].
			"' AND unit='POUND'";
		$convresult = mysql_query($convsql);
		if (mysql_num_rows($convresult) > 0) {
			$convrow = mysql_fetch_array($convresult);
			$conversion = $convrow[0];
                        $amount = $amount * $conversion;
                        $unit = 'POUND';
		}
		echo number_format((float) $amount, 2, '.', '');
		echo "</td><td>";
		echo $unit;
		echo "</td><td>";
		echo $row['comments'];
		echo "</td><td>";
		if ($row['bringBack'] == 1) {
			echo "Yes";
		} else {
			echo "No";
		}
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<br clear='all'>";
}
echo "<input class='submitbutton' type='submit' name='submit' value='Download Report'>";
echo "</form>";
echo "<form method='POST' action='packingReport.php?tab=admin:admin_sales:packing:packing_report'>";
echo "<input type='submit' class='submitbutton' value='Run Another Report'></form>";
?>
