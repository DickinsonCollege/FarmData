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
      $sqlDel="DELETE FROM weedScout WHERE id=".$_GET['id'];
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
   $weed = escapehtml($_GET['weed']);
   $fieldID = escapehtml($_GET['fieldID']);
   $sql="Select id, sDate,fieldID,weed,infestLevel,goneToSeed,comments,filename from weedScout ".
      "where sDate between '". 
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and weed like '".$weed."' and fieldID like '".$fieldID.
      "' order by sDate";
   $result=$dbcon->query($sql);
   if ($weed=="%"){ 
      $var="All";
   }else {
      $var=$_GET['weed'];
   }
   if ($fieldID=="%") {
      $var2="All";
   }else {
      $var2=$_GET['fieldID'];
   }
   echo "<center><h2> Weed Scouting Records for Weed: ".$var." in Field: ".
   $var2."</h2></center>";

   echo "<table class='pure-table pure-table-bordered' >";
   echo "<thead><tr><th>Scout Date</th><th>Field</th><th>Species</th><th>Infestation</th><th>Seed</th>".
      "<th>Comment</th><th>Picture</th>";
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
      echo $row['weed'];
      echo "</td><td>";
      echo $row['infestLevel'];
      echo "</td><td>";
      echo $row['goneToSeed'];
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
         echo "<td><form method='POST' action=\"weedEdit.php?month=".$month.
           "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
           "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
           "&tab=soil:soil_scout:soil_weed:weed_report&fieldID=".
           encodeURIComponent($_GET['fieldID']).
           "&weed=".encodeURIComponent($_GET['weed'])."\">";
         echo "<input type='submit' class='editbutton pure-button wide' value='Edit'/></form>";
         echo "</td><td>";
         echo "<form method='POST' action=\"weedTable.php?month=".$month.
            "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
            "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&tab=soil:soil_scout:soil_weed:weed_report&fieldID=".
            encodeURIComponent($_GET['fieldID']).
            "&weed=".encodeURIComponent($_GET['weed'])."\">";
         echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
         echo "onclick='return warn_delete();'/></form>";
         echo "</td>";
      }
      echo "</tr>";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo "<div class='pure-g'>";
   echo "<div class='pure-u-1-2'>";
   echo "<form name='form' method='POST' action='/down.php'>";
   echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
   echo '<input type="submit" name="submit" class="submitbutton pure-button wide" value="Download Report">';
   echo "</form>";
   echo "</div>";
   echo "<div class='pure-u-1-2'>";
   echo '<form method="POST" action = "weedReport.php?tab=soil:soil_scout:soil_weed:weed_report">'.
     '<input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
   echo "</div>";
   echo "</div>";
   ?>
