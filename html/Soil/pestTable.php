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
      $sql = "select filename from pestScout where id=".$_GET['id'];
      $result=$dbcon->query($sql);
      $row = $result->fetch(PDO::FETCH_ASSOC);
      $filename = $row['filename'];
      if ($filename != "") {
         unlink($filename);
      }

      $sqlDel="DELETE FROM pestScout WHERE id=".$_GET['id'];
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
   $crop = escapehtml($_GET['crop']);
   $fieldID = escapehtml($_GET['fieldID']);
   $pest = escapehtml($_GET['pest']);
   $sql="select id,sDate,crops,fieldID,pest,avgCount,comments,filename from pestScout ".
      "where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and crops like '%".$crop."%' and fieldID like '".
      $fieldID."' and pest like '".$pest."' order by sDate";
   $result=$dbcon->query($sql);
   if ($crop=="%"){
      $var="All Crops";
   }else {
      $var=$_GET['crop'];
   }
   if ($fieldID=="%") {
      $var2="All Fields";
   }else {
      $var2="Field ".$_GET['fieldID'];
   }
   if ($pest=="%") {
      $var3="All Insects";
   }else {
      $var3=$_GET['pest'];
   }
   echo "<center><h2> Insect Scouting Report for ".$var." in ".
    $var2." for ".$var3."</h2></center>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Scout Date</th><th>Crops</th><th>Field ID</th><th>Insect</th>";
   echo "<th>Average Count</th><th>Comments</th><th>Picture</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
      while ( $row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo $row['sDate'];
        echo "</td><td>";
        echo $row['crops'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['pest'];
        echo "</td><td>";
        echo $row['avgCount'];
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
           echo "<td><form method='POST' action=\"pestEdit.php?month=".$month.
              "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
              "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
              "&fieldID=".encodeURIComponent($_GET['fieldID']).
              "&crop=".encodeURIComponent($_GET['crop']).
              "&pest=".encodeURIComponent($_GET['pest']).
              "&tab=soil:soil_scout:soil_pest:pest_report\">";
           echo "<input type='submit' class='editbutton pure-button wide' value='Edit'/></form>";
           echo "</td><td>";
           echo "<form method='POST' action=\"pestTable.php?month=".$month.
              "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
              "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
              "&fieldID=".encodeURIComponent($_GET['fieldID']).
              "&crop=".encodeURIComponent($_GET['crop']).
              "&pest=".encodeURIComponent($_GET['pest']).
              "&tab=soil:soil_scout:soil_pest:pest_report\">";
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
  echo '<input type="submit" class="submitbutton pure-button wide" name="submit" value="Download Report">';
echo "</form>";
  echo "</div>";
  echo "<div class='pure-u-1-2'>";
echo '<form method="POST" action = "pestReport.php?tab=soil:soil_scout:soil_pest:pest_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
  echo "</div>";
  echo "</div>";
?>

