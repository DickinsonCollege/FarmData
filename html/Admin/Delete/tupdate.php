<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT numberOfBeds as numberOfBeds FROM field_GH where fieldID='".
   escapehtml($_GET['field'])."'";
$result=$dbcon->query($sql);
while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
$ind=1;	
while($ind<=$row['numberOfBeds']){
   echo "<option value=\"".$ind."\">".$ind."</option> \n";
   $ind++;
}

}
?>

