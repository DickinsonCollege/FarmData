<input type="hidden" name="numCropRows" id="numCropRows" value="0">

<br clear="all"/>
<br clear="all"/>
<center>
<?php
  echo ' <table id="cropTable"';
//  if (!$_SESSION['mobile']) {
//     echo ' style="width:10%"';
//  }
     echo ' style="width:auto" class="pure-table pure-table-bordered" ';
  echo '>';
?>
<thead><tr><th>Crops</th></tr></thead>
<tbody></tbody>
</table>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" id="addCrop" name="addCrop" class="submitbutton pure-button wide" 
  onClick="addCropRow('');" value="Add Crop">
</div>
<div class="pure-u-1-2">
<input type="button" id="removeCrop" name="removeCrop" class="submitbutton pure-button wide" onClick="removeCropRow();"
value="Remove Crop">
</div>
</div>
<br clear="all"/>
</center>

<script type="text/javascript">
   var numCropRows = 0;

   function addCropRow(crp) {
      numCropRows++;
      var numCrops = document.getElementById("numCropRows");
      numCrops.value = numCropRows;
      //var table = document.getElementById("cropTable");
      // table.style = "width:10%";
      //var row    = table.insertRow(numCropRows);
      var table = document.getElementById("cropTable").getElementsByTagName('tbody')[0];
      var row = table.insertRow(-1);

      row.id="cropRow" + numCropRows;
      var cell0 = row.insertCell(-1);
      var cropID = "";
      if (crp != "") {
         cropID = "<option value='" + crp + "'>" + crp + "</option>";
      }
      cropID += '<?php
      $result=$dbcon->query("Select crop from plant where active=1");
      while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
          echo "<option value = \"".$row1['crop']."\">".$row1['crop']."</option>";
      }
       ?>';
      cell0.innerHTML = '<div class="styled-select<?php 
  if (!$_SESSION['mobile']) { echo "2"; }?>" id="cropDiv'+numCropRows+
        '"> <select class="wide" name ="crop' + 
        numCropRows +'" id="crop' + numCropRows + '" >' +
      //  '<option value = 0 selected disabled> Crop </option>' +  
       cropID + '</select></div>';
   }
<?php
if (!isset($_POST['numCropRows'])) {
   echo 'addCropRow("");';
}
?>

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

<?php
if (isset($_POST['numCropRows'])) {
  echo "<script type='text/javascript'>";
  for ($i = 1; $i <= $_POST['numCropRows']; $i++) {
     // array_push($crps, escapehtml($_POST['crop'.$i]));
     echo "addCropRow('".escapehtml($_POST['crop'.$i])."');";
   }
   echo "</script>";
}
?>
