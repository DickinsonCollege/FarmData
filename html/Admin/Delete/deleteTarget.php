<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
$farm = $_SESSION['db'];
?>


<script type="text/javascript">
function addInput() {
   xmlhttp = new XMLHttpRequest();
   var target = encodeURIComponent(document.getElementById("target").value);
   xmlhttp.open("GET", "update_target.php?target="+target, false);
   xmlhttp.send();
   if (xmlhttp.responseText == "\n") {
      
   }
   var js_array = eval(xmlhttp.responseText);
   var thediv = document.getElementById('renamediv');
   thediv.innerHTML = '<div id="renamediv">'+
      '<input type="text" onchange="stopSubmitOnEnter(event)" name="rename" id="rename" class="textbox25 mobile-input" value="'+js_array[0]+'"></div>';

   thediv = document.getElementById('prefixdiv');
   thediv.innerHTML = '<div id="prefixdiv">'+
      '<input type="text" onchange="stopSubmitOnEnter(event)" name="prefix" id="prefix" class="textbox25 mobile-input" value="'+js_array[1]+'"></div>';

   thediv = document.getElementById('activediv');
   if (js_array[2] == 1) {
      thediv.innerHTML = '<div id="activediv" class="styled-select">'+
         '<select name="active" id="active" class="mobile-select">'+
            '<option value="1" selected>Active</option>'+
            '<option value="0">Inactive</option>'+
         '</select></div>';
   } else {
      thediv.innerHTML = '<div id="activediv" class="styled-select">'+
         '<select name="active" id="active" class="mobile-select">'+
            '<option value="1">Active</option>'+
            '<option value="0" selected>Inactive</option>'+
         '</select></div>';
   }
}
</script>

<body id= "delete">
<h3> Edit/Delete Sales Target</h3>
<br>
<form name='form' method='POST' action='<?php $_SERVER['PHP_SELF']?>'>
<label for="target"><b>Sales Target:&nbsp;</b></label>
<div id='target2' class='styled-select'>
<select name='target' id='target' onChange='addInput();' class='mobile-select'>
<option disabled selected></option>
<?php
$result = mysql_query("SELECT targetName from targets");
while ($row1 =  mysql_fetch_array($result)){
   echo '\n<option value= "'.$row1['targetName'].'">'.
      $row1['targetName'].'</option>';
   }
echo "</select>";
?>
<br clear="all"/>

<label for="rename">Rename Target:&nbsp;</label>
<div id="renamediv">
<input onkeypress='stopSubmitOnEnter(event)' type="text" name="rename" id="rename" class="textbox25 mobile-input" value="">
</div>
<br clear="all">

<label for="prefix">Change Invoice Prefix:&nbsp;</label>
<div id="prefixdiv">
<input onkeypress='stopSubmitOnEnter(event)' type="text" name="prefix"
   id="prefix" class="textbox25 mobile-input">
</div>
<br clear="all">

<label for="active">Change Active Status:</label>
<div id="activediv" class="styled-select">
   <select name="active" id="active" class='mobile-select'>
      <option value="1" selected>Active</option>
      <option value="0">Inactive</option>
   </select>
</div>
<br clear="all"> 
<br clear="all">


<input class="submitbutton" name="submit" type="submit" id="submit" value="Submit">

<?php
if(!empty($_POST['submit'])) {
   $target = escapehtml($_POST['target']);
   $rename = escapehtml($_POST['rename']);
   $prefix = escapehtml($_POST['prefix']);
   $active = escapehtml($_POST['active']);

   $sql = "select * from targets where targetName='".$target."'";
   $result = mysql_query($sql);
   $row = mysql_fetch_assoc($result);

   if (trim($rename) == "") {
      $rename = $target;
   }

   if (trim($prefix) == "") {
      $prefix = $row['prefix']; 
   }

   $sql = "UPDATE targets SET targetName='".$rename."', 
      prefix='".$prefix."', active=".$active."
      WHERE targetName='".$target."'";

   $query = mysql_query($sql) or die(mysql_error());

   if (!$query) {
      echo '<script> alert("Could not sales target, please try again"); </script>';
   } else {
      echo '<script> alert("Changed sales target successfully!"); </script>';
   }
}
?>
</form>
</body>
</html>
