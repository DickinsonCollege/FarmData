<?php
   include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
   include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   //include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
?>
<?php
   $rain = $_POST[rain];
   $date = $_GET[curDate];
   if ($rain != '' && is_numeric($rain)){
      $sqlfindDate = "select * from pump_master where pumpDate='".$date."'";
      $data = mysql_query($sqlfindDate) or die(mysql_error());
      echo mysql_error();
      echo 'number of Rows: '.mysql_num_rows($data);
      $comment = "";
      if ($rain >= 0.5) {
          $comment = "No irrigation due to rain.";
      }
      if (mysql_num_rows($data) == 0){
         $sqlInsert = "insert into pump_master (pumpDate, valve_open, driveHZ, outlet_psi, pump_kwh, solar_kwh, comment, rain) values ('".
           $date."',0,0,0,0,0, '".$comment."', ".$rain.")";
         mysql_query($sqlInsert) or die(mysql_error());
         echo "Rain Updated Successfully!";
      } else{
         $sqlUpdate = "update pump_master set rain=".$rain.", comment='".
            $comment."' where pumpDate='".$date."'";
         mysql_query($sqlUpdate) or die(mysql_error());
         echo "Rain Updated Successfully!";
      }   
   } else {
      echo "<script type='text/javascript'>alert('Please enter a numeric value');</script>";
   }
   echo "<meta http-equiv='refresh' content=0;URL='irrigationReport.php?tab=soil:soil_irrigation:irrigation_report".
         "&day=".$_GET[day]."&month=".$_GET[month]."&year=".$_GET[year]."&tday=".$_GET[tday]."&tmonth=".$_GET[tmonth]."&tyear=".$_GET[tyear]."'>";
?>
