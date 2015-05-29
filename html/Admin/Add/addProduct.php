<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' class = "pure-form pure-form-aligned" method='post' action="<?php $_PHP_SELF ?>">
<center><h2><b>Add Product</b></h2></center>

<div class = "pure-control-group">
<label for="product">Product Name:</label>
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="product" id="product">
</div>

<div class = "pure-control-group">
<label for="unit">Default Unit:</label> 
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="unit" id="unit">
</div>

<div class = "pure-control-group">
<label for="dh_unit">Invoice Unit:</label> 
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="dh_unit" id="dh_unit">
</div>

<div class = "pure-control-group">
<label for="per">Units per Case:</label>
<input onkeypress= "stopSubmitOnEnter(event)"; class="textbox3 mobile-input" type="text" name="upc" id="upc">
</div>

<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="add" value="Add">
<br clear="all"/>
<br clear="all"/>

<?php
if (isset($_POST['add'])) {
   $product = escapehtml(strtoupper($_POST['product']));
   $sql = "select * from plant where crop = '".$product."'";
   $result = mysql_query($sql);
   if (mysql_fetch_array($result)) {
      echo "<script>alert(\"Can not add a product with the same name as a crop: please try again!.\");</script>\n";
   } else {
      $unit = escapehtml(strtoupper($_POST['unit']));
      $upc = escapehtml($_POST['upc']);
      $dhunit = escapehtml(strtoupper($_POST['dh_unit']));

      if (!empty($product) && !empty($unit) && !empty($upc) && $upc > 0 && !empty($dhunit)) {
         $sql="insert into product(product,unit,units_per_case, dh_units, active) values ('".
             $product."','".$unit."','".$upc."', '".$dhunit."', 1)";
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
}
?>
