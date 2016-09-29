<php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='date' class='pure-form pure-form-aligned' method='POST'>
<?php 
 echo '<center>';
echo '<h2>Choose Harvest List Date</h2>';
 echo '</center>';
echo '<div class="pure-control-group">';
echo '<label>Date:</label>';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "</div>";
?>
<br clear="all"/>

<input class="submitbutton pure-button wide"
   type="submit" name="date" value="Choose" >
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

    if ($res=$sql_result->fetch(PDO::FETCH_ASSOC)) {
       $currentID= $res["id"];
       echo ' <meta http-equiv="refresh" content=0;URL="harvestList.php?tab=harvest:harvestList&year='.
         $year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=0 ">';
    }else{
       echo "<script type='text/javascript'>";
       echo "alert('No harvest list for the date specified!');";
       echo "</script>";
    }    


}
?>
