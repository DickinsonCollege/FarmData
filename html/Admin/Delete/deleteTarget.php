<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
$farm = $_SESSION['db'];
?>


<script type="text/javascript">
function addInput() {
   xmlhttp = new XMLHttpRequest();
   var target = encodeURIComponent(document.getElementById("target").value);
   xmlhttp.open("GET", "update_target.php?target="+target, false);
   xmlhttp.send();
   if (xmlhttp.responseText == "\n") {
       phpAlert("Error fetching target information", $p);
       die();     
   }
   var js_array = eval(xmlhttp.responseText);
   var thediv = document.getElementById('renamediv');
   thediv.innerHTML = '<div class="pure-control-group" id="renamediv">' +
      '<label for="rename">Rename Target:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="rename" id="rename" value="' +
      js_array[0] + '"></div>';

   thediv = document.getElementById('prefixdiv');
   thediv.innerHTML = '<div class="pure-control-group" id="prefixdiv">' +
      '<label for="prefix">Change Invoice Prefix:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="prefix" ' +
      'id="prefix" value="' +
      js_array[1] + '"></div>';

   thediv = document.getElementById('activediv');
   var str = '<div class="pure-control-group" id="activediv">' +
      '<label for="active">Change Active Status:</label> ' +
      '<select name="active" id="active">';
   if (js_array[2] == 1) {
      str += '<option value="1" selected>Active</option>' +
            '<option value="0">Inactive</option>';
   } else {
      str += '<option value="1">Active</option>' +
            '<option value="0" selected>Inactive</option>';
   }
   str += '</select></div>';
   thediv.innerHTML = str;
}
</script>

<body id= "delete">
<center>
<h2> Edit/Delete Sales Target</h2>
</center>
<form name='form' class="pure-form pure-form-aligned" method='POST' action='<?php $_SERVER['PHP_SELF']?>'>

<div class="pure-control-group" id="target2">
<label for="target">Sales Target:</label>
<select name='target' id='target' onChange='addInput();' class='mobile-select'>
<?php
$result = $dbcon->query("SELECT targetName from targets");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
   echo '\n<option value= "'.$row1['targetName'].'">'.
      $row1['targetName'].'</option>';
   }
echo "</select></div>";
?>

<div class="pure-control-group" id="renamediv">
<label for="rename">Rename Target:</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="rename" id="rename" value="">
</div>

<div class="pure-control-group" id="prefixdiv">
<label for="prefix">Change Invoice Prefix:</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="prefix"
   id="prefix">
</div>

<div class="pure-control-group" id="activediv">
<label for="active">Change Active Status:</label>
   <select name="active" id="active" class='mobile-select'>
      <option value="1" selected>Active</option>
      <option value="0">Inactive</option>
   </select>
</div>
<br clear="all"> 
<script type="text/javascript">
window.onload=function() {addInput();}
</script>
<input class="submitbutton pure-button wide" name="submit" type="submit" id="submit" value="Submit">

<?php
if(!empty($_POST['submit'])) {
   $target = escapehtml($_POST['target']);
   $rename = escapehtml($_POST['rename']);
   $prefix = escapehtml($_POST['prefix']);
   $active = escapehtml($_POST['active']);

   $sql = "select * from targets where targetName='".$target."'";
   $result = $dbcon->query($sql);
   $row = $result->fetch(PDO::FETCH_ASSOC);

   if (trim($rename) == "") {
      $rename = $target;
   }

   if (trim($prefix) == "") {
      $prefix = $row['prefix']; 
   }

   $sql = "UPDATE targets SET targetName='".$rename."', 
      prefix='".$prefix."', active=".$active."
      WHERE targetName='".$target."'";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      echo "<script>alert(\"Could not update sales target".$p->getMessage()."\");</script>";
      die();
   }

   echo '<script> alert("Changed sales target successfully!"); </script>';
}
?>
</form>
</body>
</html>
