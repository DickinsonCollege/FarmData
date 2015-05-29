<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   if(isset($_GET['email']) && isset($_GET['target'])){
      $sqlDel="DELETE FROM targetEmail WHERE email = '".$_GET['email'].
        "' and target = '".$_GET['target']."'";
      mysql_query($sqlDel);
      echo mysql_error();
      // echo $sqlDel;
   }

   $sqlget = "SELECT * from targetEmail";
   $sqldata = mysql_query($sqlget) or die("ERROR");
   echo "<center><h2> Delete Sales Target Email </h2></center>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Email</th><th>Target</th>".
   "<th>Delete</th></tr></thead>";
   while($row = mysql_fetch_array($sqldata)) {
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
