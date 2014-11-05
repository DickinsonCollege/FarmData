<?php
   $numFields = $_POST['numFields'];
   if ($numFields <= 0) {
      echo "<script>alert(\"Enter at least one field!\");</script> \n";
   } else {
      $success = true;
      for ($i = 1; $i <= $numFields; $i++) {
         if (isset($_POST['fieldID'.$i])) {
            $fieldID = escapehtml(strtoupper($_POST['fieldID'.$i]));
            $size = escapehtml($_POST['size'.$i]);
            $beds = escapehtml($_POST['beds'.$i]);
            $length = escapehtml($_POST['length'.$i]);
            $sql="insert into field_GH(fieldID,size,numberOfBeds, length, active) values ('".$fieldID."', ".
               $size.", ".$beds.", ".$length.",1)";
            $result=mysql_query($sql);
            if (!$result) {
               echo "<script>alert(\"Could not add field: Please try again!\\n".mysql_error().
                   "\");</script> \n";
                 $success = false;
            } 
         }
      }
      if ($success) {
        echo "<script>alert(\"Added field(s) successfully!\");</script> \n";
      }
   }
?>
