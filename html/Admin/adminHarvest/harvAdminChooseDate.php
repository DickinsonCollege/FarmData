<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='date' class='pure-form pure-form-aligned'  method='POST'>
<center>
<h2> Date of Harvest List (to Create or Edit): </h2>
</center>

<div class="pure-control-group">
<label>Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<br clear="all">
<input class="submitbutton pure-button wide" type="submit" name="date" value="Choose" >
</form>

<?php
if(isset($_POST['date'])){
   $day=$_POST['day'];
   $month=$_POST['month'];
   $year=$_POST['year'];
   echo "<br>";
   $listDate=$year."-".$month."-".$day;
   echo "<br>";

   $sql_result=$dbcon->query("SELECT id FROM harvestList WHERE harDate='$listDate'");
   $row = $sql_result->fetch(PDO::FETCH_ASSOC);

   if ($row) {
      $currentID= $row["id"];
   }else{
      $sql="INSERT INTO harvestList(harDate, comment) VALUES('$listDate','')";
      echo "<br>";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
      $currentID = $dbcon->lastInsertId();
   }   

   echo ' <meta http-equiv="refresh" content=0;URL="harvestListAdmin.php?tab=admin:admin_add:admin_harvestlist&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=0 ">';

}
?>
</body>
</html>
