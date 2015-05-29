<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];
if ($farm == 'wahlst_spiralpath') {
    echo "<option value=4>4</option> \n";
    echo "<option value=8 selected>8</option> \n";
} else {
   $sql="SELECT numberOfBeds as numberOfBeds FROM field_GH where fieldID='".
      escapehtml($_GET['field'])."'";
   $result=mysql_query($sql);
   while ($row=mysql_fetch_array($result)) {
      $ind=1;	
      $max = $row['numberOfBeds'];
      if ($max <1) {
	$max =1;
      }
      while($ind<=$max){
         echo "<option value=\"".$ind."\">".$ind."</option> \n";
         $ind++;
      }
   }
}
?>

