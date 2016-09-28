<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<body id="add">
<form name='form' class="pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center>
<h2> Update User Status</h2>
</center>

<div class="pure-control-group" id="useriddiv">
<label for="user">Username:</label>
<select name="userid" id="userid" onchange='update();'>
<?php
$sql="select username from users";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo '\n<option value="'.$row['username'].'">'.$row['username'].'</option>';
}
?>
</select>
</div>

<div class="pure-control-group">
<label>Admin:</label> 
<select name="admin" id="adminS" class='mobile-select'>
<option value="0">No</option>
<option value="1">Yes</option>
</select>
</div>

<div class="pure-control-group">
<label for="admin">Active:</label> 
<select name="active" id="active" class='mobile-select'>
<option value="0">No</option>
<option value="1">Yes</option>
</select>
</div>

<script type="text/javascript">
function update() {
   var user = document.getElementById("userid").value;
   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "getUser.php?user=" + encodeURIComponent(user), false);
   xmlhttp.send();
   var info = eval("(" + xmlhttp.responseText + ")");
   document.getElementById("active").selectedIndex = info['active'];
   document.getElementById("adminS").selectedIndex = info['admin'];
}

update();
</script>

<br clear="all">
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit">
<?php
if (!empty($_POST['submit'])){
   $admin=$_POST['admin'];
   $active=$_POST['active'];
   $userid=escapehtml($_POST['userid']);
   if (!empty($userid)) {
      $update = "update users set admin = ".$admin.  ", active = ".$active." where username = '".$userid."'";
      try {$stmt = $dbcon->prepare($update);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not update user".$p->getMessage()."\");</script>";
         die();
      }
      echo "<script>showAlert(\"Updated User successfully!\");</script> \n";
   } else {
      echo "<script>alert(\"Please Select a User!\\n\");</script> \n";
   }
}
?>
</body>
</html>
