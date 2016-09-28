<?php
   $numRows = $_POST['numRows'];
   if ($numRows <= 0) {
      echo "<script>alert(\"Enter at least one row!\");</script> \n";
   } else {
      for ($i = 1; $i <= $numRows; $i++) {
         if (isset($_POST['cell'.$i.'at0'])) {
            $sql="insert into ".$table."(".$cols.") values(";
            $j = 0;
            $vals = array();
            while (isset($_POST['cell'.$i."at".$j])) {
               $val = $_POST['cell'.$i."at".$j];
               if ($upper[$j]) {
                  $val = strtoupper($val);
               }
               $val = escapehtml($val);
               if ($val == 'TRUE') {
                  $val = 1;
               } else if ($val == 'FALSE') {
                  $val = 0;
               }
               // $sql .= "'".$val."',";
               $vals[$j] = $val;
               $j++;
            }
            for ($j = 0; $j < count($vals); $j++) {
                $val = $vals[$j];
                $sql .= "'".$val."',";
            }
            $sql= substr($sql, 0, strlen($sql) - 1).")";
            try {
               $stmt = $dbcon->prepare($sql);
               $stmt->execute();
            } catch (PDOException $p) {
               echo "<script>alert(\"Could not insert data: Please try again!\\n".$p->getMessage().
                   "\");</script> \n";
               die();
            }
            if ($table == "plant") {
              $sql="insert into units(crop, default_unit, unit, conversion) values('".$vals[0]."','".
                  $vals[1]."','".$vals[1]."', 1)";
              try {
                  $stmt = $dbcon->prepare($sql);
                  $stmt->execute();
              } catch (PDOException $p) {
                  echo "<script>alert(\"Could not insert data: Please try again!\\n".$p->getMessage().
                      "\");</script> \n";
                  die();
              }
           } else if ($table == "coverCrop") {
              $sql = "insert into coverVariety values('".$vals[0]."', '".$vals[0]."')";
              try {
                  $stmt = $dbcon->prepare($sql);
                  $stmt->execute();
              } catch (PDOException $p) {
                  echo "<script>alert(\"Could not insert data: Please try again!\\n".$p->getMessage().
                      "\");</script> \n";
                  die();
              }
           }
         }
      }
      echo "<script>alert(\"Added Data Successfully!\");</script> \n";
   }
?>
