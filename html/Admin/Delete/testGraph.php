<?php session_start(); ?>
<html>
<head>
	<!--Load the AJAX API-->
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
</head>

<body>
	<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
	include $_SERVER['DOCUMENT_ROOT'].'/design.php';
	
		$year = $_GET['year'];
		echo "year: ". $year;
   	$crop = escapehtml($_GET['crop']);
		echo "crop: ".$crop;	
		/*$sql ="Select unit, sum(yield)/(Select sum(tft) from 
			((Select bedft * rowsBed as tft from dir_planted where fieldID like '".$fieldID.
			"' and year(plantdate)=".$year." and 
			dir_planted.crop= '".$crop."') union all 
			(Select bedft * rowsBed as tft from transferred_to where year(transdate)=".$year." and transferred_to.crop= '".$crop.
				"' and fieldID like '".$fieldID."')) as temp1) as yperft from harvested where hardate between '".
				$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
				"' and harvested.crop = '" .$crop."' and harvested.fieldID like '".
				$fieldID."' group by unit order by unit";
*/
		$sql = "select fieldID, sum(yield) from harvested where crop='".$crop."' and year(hardate)=".$year." group by fieldID";
		$sqldata = mysql_query($sql);
		$count=0;
		while($row = mysql_fetch_array($sqldata)){
			$sql ="Select unit, sum(yield)/(Select sum(tft) from 
				((Select bedft * rowsBed as tft from dir_planted where fieldID like '".$row['fieldID'].
				"' and year(plantdate)=".$year." and 
				dir_planted.crop= '".$crop."') union all          
				(Select bedft * rowsBed as tft from transferred_to where year(transdate)=".$year." and transferred_to.crop= '".$crop.
				"' and fieldID like '".$fieldID."')) as temp1) as yperft from harvested where year(hardate)=".
				$year." and harvested.crop = '" .$crop."' and harvested.fieldID like '".
				$row['fieldID']."' group by unit order by unit";
			$yieldPerRowft = mysql_fetch_array(mysql_query($sql));
			echo"FieldID: <input type='text' id='fieldID".$count."' value='".$row['fieldID']."'/> avg Yield per rowft: <input type='text' id='yield".$count."' value='".$yieldPerRowft['yperft']."'/><br>";
			$count++;
		}
		echo "<input type='text' id='numfield' value='".$count."'/>";
		$sql = mysql_query("select distinct unit from harvested where crop='".$crop."'");
		$row = mysql_fetch_array($sql);
		echo"<input type='text' id='unit' value='".$row['unit']."'/>";
	?>
	<script type="text/javascript">
	// Load the visualization API and the chart package
	google.load('visualization', '1.0', {'packages':['corechart']});
	// Set a callback to run when the google visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	// callback that creates and populates a data table, instantiates the chart, passes in the data and draws it.
	function drawChart() {
      // Create the data table.
		var count = 0;
		var numYear = document.getElementById('numfield').value; 
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'fieldID');
      data.addColumn('number', 'Yield');
      for (count; count < numYear; count++){
		console.log(document.getElementById('yield'+count).value);
		data.addRow(
         [document.getElementById('fieldID'+count).value,Number(document.getElementById('yield'+count).value)]
      );
		}
   
      // Set chart options
      var options = {'title':'Sum of Yield by Field',
							'vAxis':{title:'FIELD ID', titleTextStyle:{color: 'red'}},
                     'hAxis':{title:document.getElementById('unit').value+' per row foot'},
							'width':800,
                     'height':600};
   
      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
 
	</script>
	<!--Div that will hold the pie chart-->
	<div id="chart_div"></div>
</body>
</html>
