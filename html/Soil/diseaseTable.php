<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM diseaseScout WHERE id=".$_GET['id'];
      try {
         $stmt = $dbcon->prepare($sqlDel);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
   }

   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $crop = escapehtml($_GET['crop']);
   $stage = escapehtml($_GET['stage']);
   $disease = escapehtml($_GET['disease']);
   $sql="Select id, sDate,fieldID,crops,disease,infest,stage,comments,filename from diseaseScout ".
      "where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' and stage like '".$stage."' and disease like '"
      .$disease."' and crops like '%".$crop."%'";
   $result=$dbcon->query($sql);
   if ($disease=="%"){
      $var="All";
   }else {
      $var=$_GET['disease'];
   }
   if ($fieldID=="%") {
      $var2="All";
   }else {
      $var2=$_GET['fieldID'];
   }
   if ($crop=="%") {
      $var3="All";
   }else {
      $var3=$_GET['crop'];
   }
   if ($stage=="%") {
      $var4="Any";
   }else {
      $var4=$_GET['stage'];
   }
   echo "<center><h2> Disease Scouting Records in Field: ".$var2." for Crop: ".$var3." Disease: ".$var." at Stage: ".$var4."</h2></center>";

   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Scout Date</th><th>Field ID</th><th>Crops</th><th>Disease Species</th>".
      "<th>Infestation Level</th><th>Crop Stage</th><th>Comments</th><th>Picture</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while ( $row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['sDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['crops'];
      echo "</td><td>";
      echo $row['disease'];
      echo "</td><td>";
      echo $row['infest'];
      echo "</td><td>";
      echo $row['stage'];
       echo "</td><td>";
       echo $row['comments'];
       echo "</td><td>";
       $filename = $row['filename'];
       if ($filename == "") {
          echo "&nbsp;";
       } else {
          $width = "200";
          $pos = strrpos($filename, ".");
          $ext = substr($filename, $pos + 1);
          if ($_SESSION['mobile']) {
             $width = "80";
          }
          echo '<img style="width:'.$width.'px" src="'.$filename.'"/>';
       }
        echo "</td>";
        if ($_SESSION['admin']) {
           echo "<td><form method=\"POST\" action=\"diseaseEdit.php?month=".
              $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
              "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
              "&fieldID=".encodeURIComponent($_GET['fieldID']).
              "&crop=".encodeURIComponent($_GET['crop']).
              "&disease=".encodeURIComponent($_GET['disease']).
              "&stage=".encodeURIComponent($_GET['stage']).
              "&tab=soil:soil_scout:soil_disease:disease_report\">";
           echo "<input type=\"submit\" name=\"submitEdit\" class=\"editbutton pure-button wide\" value=\"Edit\"></form></td>";

           echo "<td><form method=\"POST\" action=\"diseaseTable.php?month=".
              $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
              "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
              "&fieldID=".encodeURIComponent($_GET['fieldID']).
              "&crop=".encodeURIComponent($_GET['crop']).
              "&disease=".encodeURIComponent($_GET['disease']).
              "&stage=".encodeURIComponent($_GET['stage']).
              "&tab=soil:soil_scout:soil_disease:disease_report\">";
           echo "<input type=\"submit\" name=\"submit\" class=\"deletebutton pure-button wide\"";
           echo "onclick='return warn_delete();' value=\"Delete\"></form></td>";

        }
        echo "</tr>";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   echo '<div class="pure-g">';
   echo '<div class="pure-u-1-2">';
   echo "<form name='form' method='POST' action='/down.php'>";
   echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
   echo '<input type="submit" class="submitbutton pure-button wide" name="submit" value="Download Report">';
   echo "</form>";
   echo "</div>";
   echo '<div class="pure-u-1-2">';
   echo '<form method="POST" action = "diseaseReport.php?tab=soil:soil_scout:soil_disease:disease_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
   echo "</div>";
   echo "</div>";
?>
