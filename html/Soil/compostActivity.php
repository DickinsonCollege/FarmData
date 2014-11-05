<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3>Compost Activity Form</h3>
<form method='post' action="<?php $_PHP_SELF ?>?tab=soil:soil_fert:soil_compost:compost_activity">
<script>
        function show_confirm() {
        var m = document.getElementById("month");
        var con="Activity Date: "+m.value+"-";
        var d = document.getElementById("day");
        con=con+d.value+"-";
        var y = document.getElementById("year");
        con=con+y.value+"\n";
        var act = document.getElementById("activity").value;
        if (checkEmpty(act)) {
           alert("Please Select Activity");
           return false;
        }
        con += "Activity: " + act + "\nPiles: ";
	var numActivities = document.getElementById("numActivities").value;
        if (numActivities < 1) {
           alert("Please Select At Least One Pile");
           return false;
        } else if (numActivities < 2 && act == "COMBINING") {
           alert("You Must Select At Least Two Piles for COMBINING");
           return false;
        }
   
        for (i = 1; i <= numActivities; i++) {
           var pile = document.getElementById("pileID"+i).value;
           if (checkEmpty(pile)) {
               alert("Please select a pile in row: " + i);
               return false;
           }
           con += pile;
           if (i < numActivities) {
              con += ", ";
           }
        }
        return confirm("Confirm Entry:"+"\n"+con);
    }

function addActivityToTable() {
	var numActivitiesInput = document.getElementById("numActivities");
	numActivitiesInput.value++;
	var numActivities = numActivitiesInput.value;

	console.log(numActivities);

	var tbl = document.getElementById("activitiesTable");
	var row = tbl.insertRow(-1);
	row.id = "row" + numActivities;
	var cell = row.insertCell(0);

	var cellHTML = "";
	cellHTML += "<div class='styled-select' id='pileIDDiv'>" + 
		"<select name='pileID" + numActivities + "' id='pileID" + numActivities + "' class='mobile-select'>" + 
		"<option value=0 selected disabled>Pile ID</option>";

		<?php
		$result = mysql_query("Select pileID from compost_pile where active=1");
		while ($row = mysql_fetch_array($result)) {
			echo "cellHTML += \"<option value='".
                  $row['pileID']."'>".$row['pileID']."</option>\";";
		}
		?>

	cellHTML += "</select></div>";
	cell.innerHTML = cellHTML;
}

function removeActivityFromTable() {
	var numActivitiesInput = document.getElementById("numActivities");
	var numActivities = numActivitiesInput.value;

	if (numActivities >= 1) {
		numActivitiesInput.value--;

		var tbl = document.getElementById("activitiesTable");
		tbl.deleteRow(numActivities);
	}
}
</script>

<br clear="all"/>
<label for="Seed">Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>

<br clear="all"/>
<label for="activitylabel">Activity: </label>
<div class="styled-select" id="activityDiv">
<select name="activity" id="activity" class='mobile-select'>
<option value = 0 selected disabled>Activity</option>
<?php
$result=mysql_query("Select activityName from compost_activities");
while ($row1 =  mysql_fetch_array($result)){
	echo "\n<option value= \"$row1[activityName]\">$row1[activityName]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all">
<br clear="all">

<table id="activitiesTable">
<tr>
<th>
Pile ID:&nbsp;
</th>
</tr>
<tr id="row1">
<td>
<div class='styled-select' id='pileIDDiv'>
<select name='pileID1' id='pileID1' class='mobile-select'>
<option value=0 selected disabled>Pile ID</option>
<?php
$result = mysql_query("Select pileID from compost_pile where active=1");
while ($row = mysql_fetch_array($result)) {
	echo "<option value=\"".$row['pileID']."\">".$row['pileID']."</option>";
}
?>
</select>
</div>
</td>
</tr>
</table>
<input type='hidden' id='numActivities' name='numActivities' value=1>
<br clear='all'>
<input type='button' class='genericbutton' id='addActivity' name='addActivity' onclick='addActivityToTable();' value='Add Pile'>
<input type='button' class='genericbutton' id='removeActivity' name='removeActivity' onclick='removeActivityFromTable();' value='Remove Pile'>
<br clear='all'>
<?php if ($_SESSION['mobile']) echo "<div style='margin-top:100px'></div>"; ?>

<div>
<label for="commentslabel">Comments:&nbsp;</label>
<br clear='all'>
<textarea name='comments' rows='20' cols='30'>
</textarea>
</div>

<br clear="all"/>
<br clear="all"/>

<input onclick="return show_confirm();" type="submit" class = "submitbutton" name="submit" id="submit" value="Submit">
<br clear="all"/>
</form>
<form method="POST" action = "/Soil/compostReport.php?tab=soil:soil_fert:soil_compost:compost_report"><input type="submit" class="submitbutton" value = "View Table"></form>
<?php
if (isset($_POST['submit'])) {
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$date = $year."-".$month."-".$day;
	$activity = escapehtml($_POST['activity']);
	$comments = escapehtml($_POST['comments']);
	$numActivities = $_POST['numActivities'];

	if ($activity === "COMBINING") {
		$bigPileID = escapehtml($_POST['pileID1']);
		$commentText = "Combined Piles ".$bigPileID.", ";
		for ($i = 2; $i <= $numActivities; $i++) {
			$pileID = escapehtml($_POST["pileID".$i]);
			$commentText .= $pileID.", ";

			// Insert into Compost Activity
			$sql = "INSERT into compost_activity (actDate, pileID, activity, comments) 
				VALUES ('".$date."', '".$pileID."', '".$activity."', 'Combined Into Pile ".$bigPileID."')";
			$result = mysql_query($sql);

			// Set combined piles to inactive
			$sql = "UPDATE compost_pile SET active=0 WHERE pileID='".$pileID."'";
			$result = mysql_query($sql);
		}
		$commentText .= "into one pile";
		$comments .= "\n".$commentText;

		// Insert into Compost Activity
		$sql = "INSERT INTO compost_activity (actDate, pileID, activity, comments) 
			VALUES ('".$date."', '".$bigPileID."', '".$activity."', '".$comments."')";
		$result = mysql_query($sql);

	} else {
		for ($i = 1; $i <= $numActivities; $i++) {
			$pileID = escapehtml($_POST["pileID".$i]);
			$sql = "INSERT INTO compost_activity (actDate, pileID, activity, comments) 
				VALUES ('".$date."', '".$pileID."', '".$activity."', '".$comments."')";
			$result = mysql_query($sql);
			if (!$result) break;
		}
	}

   if(!$result) { 
      echo "<script> alert(\"Could not enter Compost Activity Data! Try again.\\n ".mysql_error()."\"); </script>";
   }else {
      echo "<script> showAlert(\"Compost Activity Record Entered Successfully\"); </script>";
   }
}
?>
