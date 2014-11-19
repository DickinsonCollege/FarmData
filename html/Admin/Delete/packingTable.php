<?php session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<link rel="stylesheet" href="/pure-release-0.5.0/pure-min.css">
<?php
if(isset($_GET['id'])){
	$sqlDel="DELETE FROM pack WHERE id=".$_GET['id'];
	mysql_query($sqlDel);
	echo mysql_error();
}
if(isset($_GET['submit'])) {
	$year = $_GET['year'];
	$month = $_GET['month'];
	$day = $_GET['day'];
	$tcurYear = $_GET['tyear'];
	$tcurMonth = $_GET['tmonth'];
	$tcurDay = $_GET['tday'];
	$crop_product = escapehtml($_GET['crop_product']);
	$target = escapehtml($_GET['target']);
	$grade = $_GET['grade'];
	$bringback = $_GET['bringback'];

	$sql = "SELECT packDate, id, crop_product, grade, amount, unit, comments, bringBack, target FROM pack 
		WHERE packDate BETWEEN '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND pack.crop_product like '".$crop_product."' AND pack.target like '".$target."' AND pack.grade like '".$grade."'";
	if ($bringback != "%") {
		$sql .= " AND bringback=".$bringback;
	}
	//$sql .= " ORDER BY packDate, crop_product, target, grade";
	echo "<input type=\"hidden\" name=\"query\" value=\"".escapehtml($sql)."\">";
	
	$result = mysql_query($sql);
	
	echo "<center>";
	$crpProd = $_GET['crop_product'];
	if ($crpProd === "%") {
		$crpProd = "All Crops/Products";
	}
	$trg = $_GET['target'];
	if ($trg === "%") {
		$trg = "All Targets";
	}
	$grd = $_GET['grade'];
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
	echo "<table class='pure-table'>";
	echo "<tr><th>Date</th>
		<th>Crop/Product</th>
		<th>Target</th>
		<th>Grade</th>
		<th>Amount</th>
		<th>Unit</th>
		<th style='width:20%'>Comments</th>
		<th>Bring Back</th><th>Edit</th><th>Delete</th></tr>";
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
/*
		$convsql = "SELECT conversion FROM units WHERE crop='".$row['crop_product'].
			"' AND unit='POUND'";
		$convresult = mysql_query($convsql);
		if (mysql_num_rows($convresult) > 0) {
			$convrow = mysql_fetch_array($convresult);
			$conversion = $convrow[0];
                        $amount = $amount * $conversion;
                        $unit = 'POUND';
		}
*/
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
		echo "<td><form method=\"POST\" action=\"packEdit.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&crop_product=".encodeURIComponent($crop_product).
      "&target=".$target."&grade=".$grade."&bringback=".$bringback."&tab=admin:admin_delete:deletesales:delete_packing&submit=Submit\">";

      echo "<input type=\"submit\" class=\"editbutton\" value=\"Edit\"></form> </td>";

      echo "<td><form method=\"POST\" action=\"packingTable.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&crop_product=".encodeURIComponent($crop_product).
      "&target=".$target."&grade=".$grade."&bringback=".$bringback."&tab=admin:admin_delete:deletesales:delete_packing&submit=Submit\">";
      echo "<input type=\"submit\" class=\"deletebutton\" value=\"Delete\"></form></td>";
		
		echo "</tr>";
	}
	echo "</table></center>";
	echo "<br clear='all'>";
}
?>
