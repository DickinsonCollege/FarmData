<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   if(isset($_GET['email']) && isset($_GET['target'])){
      $sqlDel="DELETE FROM targetEmail WHERE email = '".$_GET['email'].
        "' and target = '".$_GET['target']."'";
      try {
         $stmt = $dbcon->prepare($sqlDel);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not delete target email".$p->getMessage()."\");</script>";
         die();
      }
   }

   $sqlget = "SELECT * from targetEmail";
   $sqldata = $dbcon->query($sqlget);
   echo "<center><h2> Delete Sales Target Email </h2></center>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Email</th><th>Target</th>".
   "<th>Delete</th></tr></thead>";
   while($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['email'];
      echo "</td><td>";
      echo $row['target'];
      echo "</td><td>";
      echo "<form method=\"POST\" action=\"targetEmailTable.php?&email=".
         encodeURIComponent($row['email']).
        "&target=".encodeURIComponent($row['target']).
        "&tab=admin:admin_delete:deletesales:delete_targetemail&submit=Submit\">";
      echo "<input type=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"></form> </td>";
      
      echo "</tr>";
   }
   echo "</table>";
?>
