<?php
   include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
   include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   //include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
?>
<?php
   $rain = $_POST[rain];
   $date = $_GET[curDate];
   if ($rain != '' && is_numeric($rain)){
      $sqlfindCount = "select count(*) as num from pump_master where pumpDate='".$date."'";
      $res = $dbcon->query($sqlfindCount);
      $row = $res->fetch(PDO::FETCH_ASSOC);
      $numRows = $row['num'];
      $sqlfindDate = "select * from pump_master where pumpDate='".$date."'";
      $data = $dbcon->query($sqlfindDate);
      //echo 'number of Rows: '.$numRows;
      $comment = "";
      if ($rain >= 0.5) {
          $comment = "No irrigation due to rain.";
      }
      if ($numRows == 0){
         $sqlInsert = "insert into pump_master (pumpDate, valve_open, driveHZ, outlet_psi, pump_kwh, solar_kwh, comment, rain) values ('".
           $date."',0,0,0,0,0, '".$comment."', ".$rain.")";
         try {
            $stmt = $dbcon->prepare($sqlInsert);
            $stmt->execute();
         } catch (PDOException $p) {
            die($p->getMessage());
         }
         //echo "Rain Updated Successfully!";
      } else{
         $sqlUpdate = "update pump_master set rain=".$rain.", comment='".
            $comment."' where pumpDate='".$date."'";
         try {
            $stmt = $dbcon->prepare($sqlUpdate);
            $stmt->execute();
         } catch (PDOException $p) {
            die($p->getMessage());
         }
         //echo "Rain Updated Successfully!";
      }   
   } else {
      echo "<script type='text/javascript'>alert('Please enter a numeric value');</script>";
   }
   echo "<meta http-equiv='refresh' content=0;URL='irrigationReport.php?tab=soil:soil_irrigation:irrigation_report".
         "&day=".$_GET[day]."&month=".$_GET[month]."&year=".$_GET[year]."&tday=".$_GET[tday]."&tmonth=".$_GET[tmonth]."&tyear=".$_GET[tyear]."'>";
?>
