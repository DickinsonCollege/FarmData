<?php session_start();?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';

   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $crop = escapehtml($_GET['crop']);
   $fieldID = escapehtml($_GET['fieldID']);
   $task = escapehtml($_GET['task']);
   $origCrop = escapehtml($_GET['crop']);
   $origFieldID = escapehtml($_GET['fieldID']);
   $origTask = escapehtml($_GET['task']);

   echo "<center><h2>  Labor Report for ";
   if ($origTask == '%') {
      echo "All Tasks";
   } else {
      echo $origTask;
   }
   echo " for ";
   if ($origCrop == '%') {
      echo "All Crops";
   } else {
      echo $origCrop;
   }
   echo " in ";
   if ($origFieldID == '%') {
      echo "All Fields";
   } else {
      echo "Field: ".$origFieldID;
   }
   echo "</h2></center>";
   if ($_SESSION['admin']) {
      echo '<h3>Note for administrative users: use "Harvest->Report", ';
      echo '"Seed->Direct Seeding->Report" or ';
      echo '"Seed->Transplanting->Report" to edit/delete records for ';
      echo 'those tasks</h3>';
      echo "<br clear='all'/>";
   }
   if (isset($_GET['id'])) {
      $sqlDel="DELETE FROM labor WHERE id=".$_GET['id'];
      try {
         $stmt = $dbcon->prepare($sqlDel);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
   }
   $sql = "SELECT id, username, ldate,crop,fieldID,task,hours,comments FROM (".
      "select id, username, ldate, crop, fieldId, task, hours, comments ".
      "from labor union ".
      "select -1 as id, username, hardate as ldate, crop, fieldID, ".
      "'HARVESTING' as task, hours, comments from harvested union ".
      "select -1 as id, username, transdate as ldate, crop, fieldID, ".
      "'TRANSPLANTING' as task, hours, comments from transferred_to union ".
      "select -1 as id, username, plantdate as ldate, crop, fieldID, ".
      "'DIRECT PLANTING' as task, hours, comments from dir_planted) as tmp ".
      "where ldate BETWEEN '".  $year."-".$month."-".$day."' AND '".
       $tcurYear."-".$tcurMonth.
       "-".$tcurDay."' and crop like '" .$crop."' and fieldID like '".
       $fieldID."' and task like '".$task.
       "' and hours > 0 order by crop, ldate";
   $sqldata = $dbcon->query($sql);
   echo "<table class = 'pure-table pure-table-bordered'>";
   if($fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = "Field ".$fieldID;
   }
   if ($crop == "%") {
      $crp = "All Crops";
   } else {
      $crp = $crop;
   }
   if ($task == "%") {
      $tsk = "All Tasks";
   } else {
      $tsk = $task;
   }
   echo "<thead><tr><th>Date of Labor</th><th>Crop</th><th>Name of Field</th><th>Task</th><th>Hours</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>User</th><th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['ldate'];
      echo "</td><td>";
      echo $row['crop'];       
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['task'];
      echo "</td><td>";
      echo number_format((float) $row['hours'], 2, '.', '');
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td>".$row['username']."</td>";
       if ($row['id'] > 0) {
         echo "<td><form method='POST' action=\"laborEdit.php?month=".$month.
            "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
            "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&crop=".encodeURIComponent($origCrop).
            "&fieldID=".encodeURIComponent($origFieldID).
            "&tab=labor:labor_report&task=".encodeURIComponent($origTask)."\">";
         echo "<input type='submit' class='editbutton' value='Edit'/></form>";
         echo "</td><td>";
         echo "<form method='POST' action=\"laborTable.php?month=".$month.
            "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
            "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&crop=".encodeURIComponent($origCrop).
            "&fieldID=".encodeURIComponent($origFieldID).
            "&tab=labor:labor_report&task=".encodeURIComponent($origTask)."\">";
         echo "<input type='submit' class='deletebutton' value='Delete'";
         echo "onclick='return warn_delete();'/></form>";
         echo "</td>";
        } else {
           echo "<td>&nbsp;</td><td>&nbsp;</td>";
        }
      }
      echo "</tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   $sql2 = "SELECT sum(hours) as total FROM laborview where ldate BETWEEN '".
       $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
       $tcurDay."' and crop like '" .$crop."' and fieldID like '".$fieldID.
       "' and task like '".$task."'";
   $result=$dbcon->query($sql2);
   while ($row1 = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<div class = 'pure-form pure-form-aligned'><div class = 'pure-control-group'><label for='total'>Total Hours:&nbsp</label>";
      echo "<input class='textbox2 mobile-input' type ='text' readonly value=".
         number_format((float) $row1['total'], 1, '.', '')."></div></div>";
   }
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
echo '<div class = "pure-g">';
echo '<div class = "pure-u-1-2">';
echo "<form name='form' method='POST' action='/down.php'>";
   echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Report">';
echo '</form>';
echo '</div>';
echo '<div class = "pure-u-1-2">';
echo '<form method="POST" action = "/Labor/laborReport.php?tab=labor:labor_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
echo '</div>';
?>
</body>
</html>
