<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
   $sql = "select fieldID, size, numberOfBeds, length from field_GH";
   $sqldata = mysql_query($sql) or die(mysql_error());
   echo "<table>";
   echo "<caption> Information on All Fields </caption>";
   
   echo "<tr><th>Field</th><th>Size (acres)</th><th>Number of Beds</th><th>Length (feet)</th></tr>";
   while ($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['size'];
      echo "</td><td>";
      echo $row['numberOfBeds'];       
      echo "</td><td>";
      echo $row['length'];
      echo $row['comments'];
      echo "</td><tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';

   echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';

   echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
   echo "</form>";
?>
</div>
</body>
</html>
