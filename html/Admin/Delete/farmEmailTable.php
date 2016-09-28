<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   if(isset($_GET['email'])){
      $sqlDel="DELETE FROM email WHERE username = '".$_GET['email']."'";
      try {
         $stmt = $dbcon->prepare($sqlDel);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not delete farm email".$p->getMessage()."\");</script>";
         die();
      }
   }

   $sqlget = "SELECT * from email";
   $sqldata = $dbcon->query($sqlget);
   echo "<center><h2> Delete Farm Email </h2><center>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Email</th><th>Delete</th></tr></thead>";
   while($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['username'];
      echo "</td><td>";
      echo "<form method=\"POST\" action=\"farmEmailTable.php?&email=".
         encodeURIComponent($row['username']).
        "&tab=admin:admin_delete:deletesales:delete_farmemail&submit=Submit\">";
      echo "<input type=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"></form> </td>";
      
      echo "</tr>";
   }
   echo "</table>";
?>
