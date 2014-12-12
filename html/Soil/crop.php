<input type="hidden" name="numCropRows" id="numCropRows" value="0">

<br clear="all"/>
<br clear="all"/>
<!--
<center>
-->
<table id="cropTable" style="width:10%">
<tr><th>Crops</th></tr>
</table>
<br clear="all"/>
<input type="button" id="addCrop" name="addCrop" class="submitbutton" onClick="addCropRow();"
value="Add Crop">
&nbsp;&nbsp;&nbsp;
<input type="button" id="removeCrop" name="removeCrop" class="submitbutton" onClick="removeCropRow();"
value="Remove Crop">
<br clear="all"/>
<!--
</center>
-->

<script type="text/javascript">
   var numCropRows = 0;

   function addCropRow() {
      numCropRows++;
      var numCrops = document.getElementById("numCropRows");
      numCrops.value = numCropRows;
      var table = document.getElementById("cropTable");
      // table.style = "width:10%";
      var row    = table.insertRow(numCropRows);
      row.id="cropRow" + numCropRows;
      var cell0 = row.insertCell(-1);
      var cropID = '<?php
         $result=mysql_query("Select crop from plant");
         while ($row1 =  mysql_fetch_array($result)){
             echo "<option value = \"".$row1['crop']."\">".$row1['crop']."</option>";
         }
       ?>';
      cell0.innerHTML = '<div class="styled-select<?php 
  if (!$_SESSION['mobile']) { echo "2"; }?>" id="cropDiv'+numCropRows+
        '"> <select class="mobile-select" name ="crop' + 
        numCropRows +'" id="crop' + numCropRows + '" >' +
       '<option value = 0 selected disabled> Crop </option>' +  cropID + '</select></div>';
   }
   addCropRow();

   function removeCropRow(){
      if (numCropRows > 0){
         var row = document.getElementById("cropRow" + numCropRows);
         row.innerHTML = "";
         numCropRows--;
         var numCrops = document.getElementById("numCropRows");
         numCrops.value = numCropRows;
      }
   }

</script>
