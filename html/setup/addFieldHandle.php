<?php
   $numFields = $_POST['numFields'];
   if ($numFields <= 0) {
      echo "<script>alert(\"Enter at least one field!\");</script> \n";
   } else {
      try {
         $sql="insert into field_GH(fieldID,size,numberOfBeds, length, active) values (:fieldID, ".
            ":size, :beds, :length, 1)";
         $stmt = $dbcon->prepare($sql);
         for ($i = 1; $i <= $numFields; $i++) {
            if (isset($_POST['fieldID'.$i])) {
               $fieldID = escapehtml(strtoupper($_POST['fieldID'.$i]));
               $size = escapehtml($_POST['size'.$i]);
               $beds = escapehtml($_POST['beds'.$i]);
               $length = escapehtml($_POST['length'.$i]);
               $stmt->bindParam(':fieldID', $fieldID, PDO::PARAM_STR);
               $stmt->bindParam(':size', $size, PDO::PARAM_STR);
               $stmt->bindParam(':beds', $beds, PDO::PARAM_STR);
               $stmt->bindParam(':length', $length, PDO::PARAM_STR);
               $stmt->execute();
            }
         }
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not add field: Please try again!\\n".$p->getMessage().
             "\");</script> \n";
         die();
      }
          
      echo "<script>alert(\"Added field(s) successfully!\");</script> \n";
   }
?>
