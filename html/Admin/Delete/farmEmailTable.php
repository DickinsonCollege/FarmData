<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   if(isset($_GET['email'])){
      $sqlDel="DELETE FROM email WHERE username = '".$_GET['email']."'";
      mysql_query($sqlDel);
      echo mysql_error();
      // echo $sqlDel;
   }

   $sqlget = "SELECT * from email";
   $sqldata = mysql_query($sqlget) or die("ERROR");
   echo "<center><h2> Delete Farm Email </h2><center>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Email</th><th>Delete</th></tr></thead>";
   while($row = mysql_fetch_array($sqldata)) {
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
