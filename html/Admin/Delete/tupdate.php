<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT numberOfBeds as numberOfBeds FROM field_GH where fieldID='".
   escapehtml($_GET['field'])."'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
$ind=1;	
while($ind<=$row['numberOfBeds']){
   echo "<option value=\"".$ind."\">".$ind."</option> \n";
   $ind++;
}

}
?>

