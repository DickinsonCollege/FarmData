<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<center>
<h2>Compost Activity Form</h2>
</center>
<form method='post' class='pure-form pure-form-aligned' action="<?php $_PHP_SELF ?>?tab=soil:soil_fert:soil_compost:compost_activity">
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


        var tbl = document.getElementById("activitiesTable").getElementsByTagName('tbody')[0];
        var row = tbl.insertRow(-1);

   row.id = "row" + numActivities;
   var cell = row.insertCell(0);

   var cellHTML = "";
   cellHTML += "<div class='styled-select' id='pileIDDiv'>" + 
      "<select name='pileID" + numActivities + "' id='pileID" + numActivities + "' class='mobile-select'>" + 
      "<option value=0 selected disabled>Pile ID</option>";

      <?php
      $result = $dbcon->query("Select pileID from compost_pile where active=1");
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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

<div class="pure-control-group">
<label for="Seed">Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for="activitylabel">Activity: </label>
<select name="activity" id="activity" class='mobile-select'>
<option value = 0 selected disabled>Activity</option>
<?php
$result=$dbcon->query("Select activityName from compost_activities");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= \"$row1[activityName]\">$row1[activityName]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all">
<br clear="all">

<center>
<table id="activitiesTable" class="pure-table pure-table-bordered"
 style="width:auto;">
<thead><tr>
<th>
Pile ID
</th>
</tr></thead>
<tbody>
<tr id="row1">
<td>
<div class='styled-select' id='pileIDDiv'>
<select name='pileID1' id='pileID1' class='mobile-select'>
<option value=0 selected disabled>Pile ID</option>
<?php
$result = $dbcon->query("Select pileID from compost_pile where active=1");
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['pileID']."\">".$row['pileID']."</option>";
}
?>
</select>
</div>
</td>
</tr>
</tbody>
</table>
</center>
<input type='hidden' id='numActivities' name='numActivities' value=1>
<br clear='all'>
<div class="pure-g">
<div class="pure-u-1-2">
<input type='button' class='genericbutton pure-button wide' id='addActivity' name='addActivity' onclick='addActivityToTable();' value='Add Pile'>
</div>
<div class="pure-u-1-2">
<input type='button' class='genericbutton pure-button wide' id='removeActivity' name='removeActivity' onclick='removeActivityFromTable();' value='Remove Pile'>
</div>
</div>
<br clear='all'>

<div class="pure-control-group">
<label for="commentslabel">Comments:</label>
<textarea name='comments' rows='5' cols='30'>
</textarea>
</div>

<br clear="all"/>
<br clear="all"/>

<div class="pure-g">
<div class="pure-u-1-2">
<input onclick="return show_confirm();" type="submit" class = "submitbutton pure-button wide" name="submit" id="submit" value="Submit">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "/Soil/compostReport.php?tab=soil:soil_fert:soil_compost:compost_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();"></form>
</div>
</div>
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
      $sql = "INSERT into compost_activity (actDate, pileID, activity, comments) ".
            "VALUES ('".$date."', :pileID, '".$activity."', 'Combined Into Pile ".$bigPileID."')";
      $sqlAct = "UPDATE compost_pile SET active=0 WHERE pileID=:pileID";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmtAct = $dbcon->prepare($sqlAct);
         for ($i = 2; $i <= $numActivities; $i++) {
            $pileID = escapehtml($_POST["pileID".$i]);
            $commentText .= $pileID.", ";
   
            // Insert into Compost Activity
   /*
            $sql = "INSERT into compost_activity (actDate, pileID, activity, comments) 
               VALUES ('".$date."', '".$pileID."', '".$activity."', 'Combined Into Pile ".$bigPileID."')";
   */
         
            $stmt->bindParam(':pileID', $pileID, PDO::PARAM_STR);
            $stmt->execute();

            // Set combined piles to inactive
   //         $sql = "UPDATE compost_pile SET active=0 WHERE pileID='".$pileID."'";
            $stmtAct->bindParam(':pileID', $pileID, PDO::PARAM_STR);
            $stmtAct->execute();
         }
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
      $commentText .= "into one pile";
      $comments .= "\n".$commentText;

      // Insert into Compost Activity
      $sql = "INSERT INTO compost_activity (actDate, pileID, activity, comments) ".
         "VALUES ('".$date."', '".$bigPileID."', '".$activity."', '".$comments."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }

   } else {
      $sql = "INSERT INTO compost_activity (actDate, pileID, activity, comments) ".
         "VALUES ('".$date."', :pileID, '".$activity."', '".$comments."')";
      try {
         $stmt = $dbcon->prepare($sql);
         for ($i = 1; $i <= $numActivities; $i++) {
            $pileID = escapehtml($_POST["pileID".$i]);
           // $sql = "INSERT INTO compost_activity (actDate, pileID, activity, comments) 
           //    VALUES ('".$date."', '".$pileID."', '".$activity."', '".$comments."')";
            $stmt->bindParam(':pileID', $pileID, PDO::PARAM_STR);
            $stmt->execute();
         }
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
   }

   echo "<script> showAlert(\"Compost Activity Record Entered Successfully\"); </script>";
}
?>
