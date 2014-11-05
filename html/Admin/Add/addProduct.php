<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h1><b>Add Product<b></h1>
<label for="product">Product Name:&nbsp;</label>
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="product" id="product">
<br clear="all"/>
<label for="unit">Default Unit:&nbsp;</label> 
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="unit" id="unit">
<br clear="all"/>
<label for="dh_unit">Invoice Unit:&nbsp;</label> 
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="dh_unit" id="dh_unit">
<br clear="all"/>
<label for="per">Units per Case:&nbsp;</label>
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="upc" id="upc">
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="add" value="Add">
<?php
if (isset($_POST['add'])) {
   $product = escapehtml(strtoupper($_POST['product']));
   $unit = escapehtml(strtoupper($_POST['unit']));
   $upc = escapehtml($_POST['upc']);
   $dhunit = escapehtml(strtoupper($_POST['dh_unit']));

   if (!empty($product) && !empty($unit) && !empty($upc) && $upc > 0 && !empty($dhunit)) {
      $sql="Insert into product(product,unit,units_per_case, dh_units) values ('".
          $product."','".$unit."','".$upc."', '".$dhunit."')";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add product: Please try again!\\n".mysql_error()."\");</script>\n";
      }else {
         echo "<script>showAlert(\"Added Product Successfully!\");</script> \n";
      }
   } else {
   echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>
