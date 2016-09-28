<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<form name="form" class = "pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center><h2><b>Add Email to Sales Target</b></h2></center>

<div class = "pure-control-group">
<label for="target">Sales Target:&nbsp;</label> 
<!--<div id='targDiv' class='styled-select'>-->
<select name="target" id="target">
<option value=0 selected disabled>Sales Target</option>
<?php
$sql = "select targetName from targets where active = 1";
$result=$dbcon->query($sql);
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
  echo '<option value= "'.$row1['targetName'].'">'.$row1['targetName'].'</option>';
}
?>
</select>
</div>

<div class = "pure-control-group">
<label for="email">Email:&nbsp;</label> 
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3" type="text" name="email" id="email">
</div>

<script type="text/javascript">
function show_confirm() {
   var targ = document.getElementById("target").value;
   if (checkEmpty(targ)) {
      alert("Select a Sales Target Name!");
      return false;
   }
   var con="Sales Target: "+ targ + "\n";
   var em = document.getElementById("email").value;
   if (checkEmpty(em)) {
      alert("Enter Email Address!");
      return false;
   }
   con+="Email: "+ em + "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="done" value="Add" onclick="return show_confirm();">

<?php
if (isset($_POST['done'])) {
   $sql="insert into targetEmail values ('".escapehtml($_POST['email'])."','".
     escapehtml($_POST['target'])."')";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert("Could not add target email", $p);
      die();
   }
   echo "<script>showAlert(\"Added Email Successfully!\");</script> \n";
}
?>
