<?php
   $newfile = "";
   $delete = 0;
   if (!empty($_POST['del'])) {
      $delete = 1;
   }
   if ($delete) {
      unlink($filename);
      $newfile = "null";
   }

   if (isset($_FILES['fileIn']) && isset($_FILES['fileIn']['error']) &&
       $_FILES['fileIn']['error'] == 1) {
      echo "<script>alert(\"File too large to upload!\");</script> \n";
      die();
   } else if (isset($_FILES['fileIn']) && isset($_FILES['fileIn']['tmp_name']) &&
      $_FILES['fileIn']['tmp_name'] != "") {
      $fname = '../files/'.$_SESSION['db'].'/'.$_FILES['fileIn']['name'];
      if (file_exists($fname)) {
         echo "<script>alert(\"File ".$fname." already exists - try a different file name.\");</script> \n";
         die();
      }
      if (!move_uploaded_file($_FILES['fileIn']['tmp_name'], $fname)) {
         echo "<script>alert(\"Error uploading file.\");</script> \n";
         die();
      }
      if ($filename != "" && !$delete) {
         unlink($filename);
      }
      $newfile = $fname;
   }
?>
