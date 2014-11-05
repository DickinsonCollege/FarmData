<?php session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<?php
   $editto = $_POST[edit];
   $crop = $_GET['crop'];    
   $unit = $_GET['unit'];    
   if ($_POST[edit] != '' && is_numeric($_POST[edit])){
      $editto = $_POST[edit] - $_GET[amount];
      $sql = "select conversion, default_unit from units where crop = '".$crop."' and unit = '".$unit."'";
      // echo "<script type='text/javascript'>alert(\"".$sql."\");</script>";
      $result = mysql_query($sql);
      if (mysql_num_rows($result) > 0) {
         $convrow = mysql_fetch_array($result);
         $conversion = $convrow['conversion'];
         $unit = $convrow['default_unit'];
         $editto = $editto / $conversion;
      }
      $sqlInsert = "Insert into correct (correctDate, crop_product, grade, amount, unit) values('".
         date('Y-m-d')."', '".$crop."', ".$_GET[gradeupdate].", ".$editto.", '".$unit."')";
      mysql_query($sqlInsert) or die(mysql_error());
      echo "Edited Data Successfully!";
   } else {
      echo "<script type='text/javascript'>alert('Please enter the amount you want to edit to');</script>";
   }
   echo "<meta http-equiv='refresh' content=\"0;URL=inventoryTable.php?crop_product=".
       encodeURIComponent($_GET[crop_product])."&grade=".$_GET[grade].
       "&tab=admin:admin_sales:inventory\">";
?>
