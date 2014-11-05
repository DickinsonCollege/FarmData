<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<?php
if(isset($_POST['submit'])) {
	// Get values from compostTable.php
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $tcurYear = $_POST['tyear'];
   $tcurMonth = $_POST['tmonth'];
   $tcurDay = $_POST['tday'];
   $pileID = escapehtml($_POST['pileID']);
   $fieldID = escapehtml($_POST['fieldID']);

	// Create Header
   if($fieldID == "%" && $pileID == "%") {
      echo "<h3> Compost Records for All Fields and All Compost Piles</h3>";
	} else if ($fieldID == "%") {
		echo "<h3> Compost Records for All Fields and Compost Pile: ".
    $_POST['pileID']."</h3>";
   } else if ($pileID == "%") {
      echo "<h3> Compost Records for Field: ".$_POST['fieldID'].
      " and All Compost Piles</h3>";
   } else {
      echo "<h3> Compost Records for Field: ".$_POST['fieldID']. 
        " and Compost Pile: ".$_POST['pileID']."</h3>";
	}
	echo "<div style='margin-bottom:50px'></div>";
?>

<form name='accumulationForm' method='POST' action='/down.php'>
<?php
	// Accumulation Records
	$accumulationSQL = "SELECT accDate, pileID, material, pounds, cubicyards, comments 
		FROM compost_accumulation 
		WHERE accDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND pileID LIKE '".$pileID."' ORDER BY accDate";
	$result = mysql_query($accumulationSQL);

	if (!$result) {
		echo "<script>alert(\"Could not Generate Compost Reports: Please try again!\\n".$mysql_error()."\");</script>\n";
	}

	echo "<table border>";
	echo "<caption>Compost Accumulation Records</caption>";
	echo "<tr><th>Accumulation Date</th><th>Pile ID</th><th>Material</th><th>Pounds</th><th>Cubic Yards</th><th>Comments</th></tr>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr><td>";
		echo $row['accDate'];
		echo "</td><td>";
		echo $row['pileID'];
		echo "</td><td>";
		echo $row['material'];
		echo "</td><td>";
		echo $row['pounds'];
		echo "</td><td>";
		echo $row['cubicyards'];
		echo "</td><td>";
		echo $row['comments'];
		echo "</td></tr>";
	}
	echo "</table>";
	echo '<input type="hidden" value="'.escapehtml($accumulationSQL).'" name="query" id="query">';
   echo '<input type="submit" name="submit" class="submitbutton" value="Download Report">';
?>
</form>

<form name='activityForm' method='POST' action='/down.php'>
<?php
	// Activity Records
	$activitySQL = "SELECT actDate, pileID, activity, comments 
		FROM compost_activity 
		WHERE actDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND pileID LIKE '".$pileID."' ORDER BY actDate";
	$result = mysql_query($activitySQL);

	if (!$result) {
		echo "<script>alert(\"Could not Generate Compost Reports: Please try again!\\n".$mysql_error()."\");</script>\n";
	}

	echo "<table border>";
	echo "<caption>Compost Activity Records</caption>";
	echo "<tr><th>Activity Date</th><th>Pile ID</th><th>Activity</th><th>Comments</th>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr><td>";
		echo $row['actDate'];
		echo "</td><td>";
		echo $row['pileID'];
		echo "</td><td>";
		echo $row['activity'];
		echo "</td><td>";
		echo $row['comments'];
		echo "</td></tr>";
	}
	echo "</table>";
	echo '<input type="hidden" value="'.escapehtml($activitySQL).'" name="query" id="query">';
	echo "<input type='submit' name='submit' class='submitbutton' value='Download Report'>";
?>
</form>

<form name='temperatureForm' method='POST' action='/down.php'>
<?php
	// Temperature Records
	$temperatureSQL = "SELECT tmpDate, pileID, temperature, numReadings, comments 
		FROM compost_temperature 
		WHERE tmpDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND pileID LIKE '".$pileID."' ORDER BY tmpDate";
	$result = mysql_query($temperatureSQL);

	if (!$result) {
		echo "<script>alert(\"Could not Generate Compost Reports: Please try again!\\n".mysql_error()."\");</script>\n";
	}

	echo "<table border>";
	echo "<caption>Compost Temperature Reading Records</caption>";
	echo "<tr><th>Reading Date</th><th>Pile ID</th><th>Temperature</th><th>Number of Readings</th><th>Comments</th></tr>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr><td>";
		echo $row['tmpDate'];
		echo "</td><td>";
		echo $row['pileID'];
		echo "</td><td>";
		echo $row['temperature'];
		echo "</td><td>";
		echo $row['numReadings'];
		echo "</td><td>";
		echo $row['comments'];
		echo "</td></tr>";
	}
	echo "</table>";
	echo '<input type="hidden" value="'.escapehtml($temperatureSQL).'" name="query" id="query">';
	echo "<input type='submit' name='submit' class='submitbutton' value='Download Report'>";
?>
</form>

<form name='applicationForm' method='POST' action='/down.php'>
<?php
	// Application Records
   $applicationSQL = "SELECT util_date, fieldID, incorpTool, pileID, tperacre, incorpTiming, fieldSpread, comments 
		FROM utilized_on 
		WHERE util_date between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND fieldID LIKE '".$fieldID."' AND pileID LIKE '".$pileID."' ORDER BY util_date";
   $result = mysql_query($applicationSQL);

   if (!$result) {
    echo "<script>alert(\"Could not Generate Compost Reports: Please try again!\\n".mysql_error()."\");</script>\n";
   }

	echo "<table body>";
	echo "<caption>Compost Application Records</caption>";
   echo "<tr><th>Utilized Date</th><th>Field ID</th><th>Pile ID</th><th>Incorporation Tool</th><th>Incorporation Timing</th><th>Tons per Acre</th><th>Area Spread (acres)</th><th>Comments</th></tr>";
   while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['util_date'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['pileID'];
        echo "</td><td>";
        echo $row['incorpTool'];
        echo "</td><td>";
        echo $row['incorpTiming'];
        echo "</td><td>";
        echo number_format((float) $row['tperacre'], 2, '.', '');
        echo "</td><td>";
        echo $row['fieldSpread'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td></tr>";
   }
   echo "</table>";
   echo '<input type="hidden" value="'.escapehtml($applicationSQL).'" name="query" id="query">';
   echo '<input type="submit" name="submit" class="submitbutton" value="Download Report">';

   echo '<br clear="all"/>';

   if ($fieldID != "%") {
   	$sqlget = "Select sum(tperacre) as total, avg(tperacre) as average from utilized_on where util_date between '".
   	   $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
   	   $tcurDay."' and  fieldID like '".$fieldID."' and pileID like '".
           $pileID."'";
   	$result = mysql_query($sqlget);
   	while ($row1 = mysql_fetch_array($result)) {
   	     $row3Deci3=number_format((float)$row1['total'], 2, '.', '');
		echo "<label for='total'>Total Tons Per Acre:&nbsp;</label>";
     	   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
     	   echo '<br clear="all"/>';
     	   $row3Deci3=number_format((float)$row1['average'], 2, '.', '');
     	   echo "<label for='total'>Average Tons Per Acre:&nbsp</label>";
     	   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
        }
     echo "<br clear=\"all\">";
   }

   if ($pileID != '%') {
     $sql = "select sum(tperacre * fieldSpread) as tons from utilized_on".
           " where util_date between '".
   	   $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
   	   $tcurDay."' and  fieldID like '".$fieldID."' and pileID like '".
           $pileID."'";
     $result = mysql_query($sql);
     echo mysql_error();
     echo "<label for='tons'>Total Tons Applied from Pile ".
           $_POST['pileID'].":&nbsp;</label>";
     while ($row1 = mysql_fetch_array($result)) {
   	  $tons=number_format((float)$row1['tons'], 2, '.', '');
     	  echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$tons.">";
     }
   }
?>
</form>



<?php
echo '<br>';
echo '<form method="POST" action="compostReport.php?tab=soil:soil_fert:soil_compost:compost_report">';
echo '<input type="submit" class="submitbutton" value = "Run Another Report">';
echo '</form>';
}
?>
