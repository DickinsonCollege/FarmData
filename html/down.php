<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
header("Content-type: application/octet-stream");
header("Content-disposition: attachment;filename=\"report.csv\"");
$result=mysql_query($_POST['query']) or die(mysql_error());

$first=true;
while ($row1 =  mysql_fetch_array($result)){
   if ($first) {
      $first=false;
      $head=array_keys($row1);
      for ($i=1;$i<count($head);$i=$i+2) {
           echo "\"".$head[$i]."\"".",";
      }

      echo "\n";
   }
   for ($i=0;$i<count($row1)/2;$i=$i+1) {
      echo "\"".htmlspecialchars_decode($row1[$i], ENT_QUOTES)."\"".",";
   }
   
   echo "\n";
}

?>
