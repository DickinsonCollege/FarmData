<?php
   $numRows = $_POST['numRows'];
   if ($numRows <= 0) {
      echo "<script>alert(\"Enter at least one row!\");</script> \n";
   } else {
      $units = array();
      $sql = "select unit from extUnits";
      $res = $dbcon->query($sql);
      while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
         $units[] = $row['unit'];
      }
      for ($i = 1; $i <= $numRows; $i++) {
         $convRow = array();
         for ($j = 0; $j < count($units) + 2; $j++) {
            $convRow[] = escapehtml(strtoupper($_POST['cell'.$i."at".$j]));
         }
         try {
            $sql = "insert into units(crop, default_unit, unit, conversion) values (:crop, :du, :un, :conv)";
            $stmt = $dbcon->prepare($sql);
            for ($j = 2; $j < count($convRow); $j++) {
                if ($convRow[$j] != "" && $units[$j - 2] != $convRow[1]) {// not default unit
/*
                   $sql = "insert into units(crop, default_unit, unit, conversion) values ('".$convRow[0].
                       "', '".$convRow[1]."', '".$units[$j - 2]."', ".$convRow[$j].")";
*/
                   $stmt->bindParam(':crop', $convRow[0], PDO::PARAM_STR);
                   $stmt->bindParam(':du', $convRow[1], PDO::PARAM_STR);
                   $stmt->bindParam(':un', $units[$j - 2], PDO::PARAM_STR);
                   $stmt->bindParam(':conv', $convRow[$j], PDO::PARAM_STR);
                   $stmt->execute();
                }
            }
         } catch (PDOException $p) {
             echo "<script>alert(\"Could not insert data: Please try again!\\n".$p->getMessage().
                 "\");</script> \n";
             die();
         }
      }
      echo "<script>alert(\"Added Data Successfully!\");</script> \n";
   }
?>
