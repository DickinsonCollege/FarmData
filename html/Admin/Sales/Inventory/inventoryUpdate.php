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
      $result = $dbcon->query($sql);
      if ($convrow = $result->fetch(PDO::FETCH_ASSOC)) {
         $conversion = $convrow['conversion'];
         $unit = $convrow['default_unit'];
         $editto = $editto / $conversion;
      }
      $sqlInsert = "Insert into correct (correctDate, crop_product, grade, amount, unit) values('".
         date('Y-m-d')."', '".$crop."', ".$_GET[gradeupdate].", ".$editto.", '".$unit."')";
      try {
         $stmt = $dbcon->prepare($sqlInsert);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not update inventory".$p->getMessage()."\");</script>";
         die();
      }
      echo "<script>alert(\"Updated Inventory Successfully!\");</script>";
   } else {
      echo "<script type='text/javascript'>alert('Please enter the amount you want to update to.');</script>";
   }
   echo "<meta http-equiv='refresh' content=\"0;URL=inventoryTable.php?crop_product=".
       encodeURIComponent($_GET[crop_product])."&grade=".$_GET[grade].
       "&tab=admin:admin_sales:inventory\">";
?>
