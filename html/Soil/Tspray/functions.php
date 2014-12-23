<input type="hidden" id="numRows">
<input type="hidden" id="numRowsMat">

<script type="text/javascript">
   var numRows=0;
   var numRowsMat=0;
   var farm = "<?php echo $_SESSION['db'];?>";

   function addRow(){
      numRows++;
      var table = document.getElementById("fieldTable");
      var row    = table.insertRow(numRows);
      var cell0 = row.insertCell(0);
      var fieldID = '<?php
         $result=mysql_query("Select fieldID from field_GH where active=1");
         while ($row1 =  mysql_fetch_array($result)){
             echo "<option value = \"".$row1[fieldID]."\">".$row1[fieldID]."</option>";
         }
       ?>';
      cell0.innerHTML = '<center><div class="styled-select" id="fieldDiv'+numRows+'"> <select class="mobile-select" name ="field' + numRows +'" id="field' + numRows + '" onChange="addInput('+numRows+'); addAcre('+numRows+'); calculateTotalUpdate(); calculateWater();">' +'<option value = 0 selected disabled> FieldID</option>' +   fieldID + '</select></div></center>';
      var cell1 = row.insertCell(1);
      cell1.innerHTML = "<center><div id=\"maxBed"+numRows+"\" class='styled-select2'> <select class=\"mobile-select\" id=\"maxBed2"+numRows+"\" name=\"maxBed2"+numRows+"\"  onChange=\"addAcre("+numRows+"); calculateTotalUpdate(); calculateWater(); \">"+
                        "<option> Beds </option> </select></div></center>";
      var cell2 = row.insertCell(2);
      cell2.innerHTML = "<center><div id=\"acreDiv"+numRows+"\"><input class='textbox mobile-input inside_table' type=\"text\" id=\"acre"+numRows+"\" value=0 readonly style='width:100%'></div> </center>";
   }
   addRow();
   function removeRow(){
      if (numRows > 0){
         var field = document.getElementById('field'+numRows);
         field.parentNode.removeChild(field);
         var maxBed = document.getElementById('maxBed2'+numRows);
         maxBed.parentNode.removeChild(maxBed);
         var acre = document.getElementById('acre'+numRows);
                        acre.parentNode.removeChild(acre);
         var table = document.getElementById('fieldTable');
         table.deleteRow(numRows);
         numRows--;
      }
   }
   function addRowMat(){
      numRowsMat++;
      var table = document.getElementById("materialTable");
      var row = table.insertRow(numRowsMat);
      var materialSprayed = "<?php
         $sqlM="SELECT sprayMaterial FROM tSprayMaterials";
         $resultM=mysql_query($sqlM);
         //echo mysql_error();
         while($rowM=mysql_fetch_array($resultM)){
            echo "<option value='".$rowM[sprayMaterial]."'>".$rowM[sprayMaterial]."</option>";
         }?>";

      var cell0 = row.insertCell(0);
      cell0.innerHTML =  "<center><div id =\"material"+numRowsMat+"\" class='styled-select2'><select class=\"mobile-select\" id=\"material2"+numRowsMat+"\" name=\"material2"+numRowsMat+"\"  onChange=\"addInputRates("+numRowsMat+"); calculateSuggested("+numRowsMat+"); addUnit("+numRowsMat+");  addPPE("+numRowsMat+"); addREI("+numRowsMat+"); \">"+ 
  "<option value=0> Material</option>\n"+
        materialSprayed+"</select></div></center>";
      var cell1 = row.insertCell(1);
      cell1.innerHTML =  "<center><div id =\"rate"+numRowsMat+
            "\" class='styled-select2'><select class=\"mobile-select\" id='rate2"+numRowsMat+
            "' name='rate2"+numRowsMat+"'  onChange=\"calculateSuggested("+
            numRowsMat+");\">"+"<option value=0 selected> Rates </option> </select></div></center>";
      var cell2 = row.insertCell(2);
       cell2.innerHTML = "<div id=\"unitDiv"+numRowsMat+"\"><label style=\"font-size:12pt\" id='unit"+ numRowsMat+"'> Unit </label></div>";
      var cell3 = row.insertCell(3);
      cell3.innerHTML = "<center><div id=\"calculatedTotalDiv"+numRowsMat+"\"><input type=\"text\" id=\"calculatedTotal"+numRowsMat+"\" class='textbox mobile-input inside_table' value=0 readonly style='width:100%'></div></center>";
      var cell4 = row.insertCell(4);
      cell4.innerHTML = "<center><div id=\"actualTotalDiv"+numRowsMat+
         "\"><input class='textbox mobile-input inside_table' type=\"text\" id=\"actuarialTotal"+numRowsMat+
         "\" name=\"actuarialTotal"+numRowsMat+"\" value=0 style='width:100%'></div></center>";
      var cell5 = row.insertCell(5);
//      cell5.innerHTML = "<center><div id=\"ppe"+numRowsMat+"\" class='styled-select2'>" + 
 //           "<select class='mobile-select' id='ppe2"+numRowsMat+"' name='ppe2"+numRowsMat+"'>" + 
  //          "<option value=0 selected disabled> PPE </option></select></div></center>";
      cell5.innerHTML = "<center><div id=\"ppe"+numRowsMat+"\" >" + 
        "<input class='textbox mobile-input inside_table' readonly type='text' id='ppe2"+numRowsMat+
       "' name='ppe2"+numRowsMat + "' value='' style='width:100%'>" + 
         "</div></center>";
      var cell6 = row.insertCell(6);
      cell6.innerHTML = "<center><div id=\"rei"+numRowsMat+"\" >" + 
        "<input class='textbox mobile-input inside_table' readonly type='text' id='rei2"+
         numRowsMat+"' name='rei2"+numRowsMat+"' value='0' style='width:100%'>" + 
         "</div></center>";
   }
   addRowMat();
   function removeRowMat(){
      if (numRowsMat >0){
         var matSpray = document.getElementById("material2"+numRowsMat);
         matSpray.parentNode.removeChild(matSpray);
         var rate = document.getElementById("rate2"+numRowsMat);
         rate.parentNode.removeChild(rate);
         var unit = document.getElementById("unit"+numRowsMat);
               unit.parentNode.removeChild(unit); 
              var calcTotal = document.getElementById("calculatedTotal"+numRowsMat);
         calcTotal.parentNode.removeChild(calcTotal);
         var actualTotal = document.getElementById("actuarialTotal"+numRowsMat);
         actualTotal.parentNode.removeChild(actualTotal);
         var table = document.getElementById("materialTable");
         table.deleteRow(numRowsMat);
         numRowsMat--;  
      }
   }
   function addInput(num){
      var fld = encodeURIComponent(document.getElementById('field'+num).value);
      var newdiv=document.getElementById('maxBed'+num);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tupdate.php?field="+fld, false);
      xmlhttp.send();

      newdiv.innerHTML="<div class='styled-select2' id=\"maxBed"+num+"\"><select class=\"mobile-select\" onchange=\"addAcre("+num+"); calculateTotalUpdate(); calculateWater();\" id= \"maxBed2"+num+"\" name= \"maxBed2"+num+"\">"+xmlhttp.responseText+"</select></div>";
   }


   function addInputRates(numM){
   
      var mat = encodeURIComponent(document.getElementById('material2'+numM).value);
      var newdivM=document.getElementById('rate'+numM);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tRateUpdate.php?material="+mat, false);
      xmlhttp.send();
      newdivM.innerHTML="<div class=styled-select2 id='rate"+numM+"'> <select class=\"mobile-select\" onchange=\"calculateSuggested("+numM+");\" id='rate2"+numM+"' name= 'rate2"+numM+"'>"+xmlhttp.responseText+"</select></div>";
   }   

   function addUnit(numU){
      var mU = encodeURIComponent(document.getElementById('material2'+numU).value);
      var newdivU=document.getElementById('unit'+numU);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tUnitUpdate.php?material="+mU, false);
      xmlhttp.send();
        
      newdivU.innerHTML="<label style=\"font-size:12pt\"  id='unit"+numU+"'>"+ xmlhttp.responseText +" </label>  ";
   }

   function addPPE(numU) {
      var mU = encodeURIComponent(document.getElementById('material2'+numU).value);
      var newDivP = document.getElementById('ppe'+numU);
      xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", "getPPE.php?material="+mU, false);
      xmlhttp.send();
      
//      newDivP.innerHTML = "<div class='styled-select2 id='ppe"+numU+"'>" + 
//         "<select class='mobile-select' id='ppe"+numU+"' name='ppe"+numU+"'>" + xmlhttp.responseText + "</select></div>";
      newDivP.innerHTML = "<center><div id=\"ppe"+numU+"\" >" + 
        "<input class='textbox mobile-input inside_table' readonly type='text' id='ppe2"+numU+"' name='ppe"+numU+"' value='"
         + xmlhttp.responseText + "' style='width:100%'>" + 
         "</div></center>";
   }

   function addREI(numU) {
      var mU = encodeURIComponent(document.getElementById('material2'+numU).value);
      var newDivP = document.getElementById('rei'+numU);
      xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", "getREI.php?material="+mU, false);
      xmlhttp.send();

      newDivP.innerHTML = "<center><div id=\"rei"+numU+"\" >" + 
        "<input class='textbox mobile-input inside_table' readonly type='text' id='rei2"+numU+
         "' name='rei2"+numU+"' style='width:100%' value='" + xmlhttp.responseText + "'>" + 
         "</div></center>";
   }

   function addAcre(numA){
      var fld = encodeURIComponent(document.getElementById('field'+numA).value);
      var bA = document.getElementById('maxBed2'+numA).value;
      var newdiv=document.getElementById('acre'+numA);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tAcreUpdate.php?field="+fld+"&beds="+bA, false);
      xmlhttp.send();
      newdiv.value=xmlhttp.responseText;
//        newdiv.innerHTML="<select id= 'maxBed<?php echo $numFieldInd  ?>' name= 'maxBed'>"+xmlhttp.responseText+"</select>";
   
   }

   function calculateTotal(){
      var ind=1;
      var totalFieldAcre=0;
      var maxField= numRows;
   
      while(ind<= maxField){
         var eachFieldAcre=document.getElementById('acre'+ind).value;
         totalFieldAcre=parseFloat(totalFieldAcre)+ parseFloat(eachFieldAcre);
         ind++;
      }   
      return totalFieldAcre;
   }
//        var formatTotalFieldAcre=totalFieldAcre.toFixed(2); 
   
//input -1 when just input water
   function calculateWater() {
      var w = document.getElementById('waterPerAcre');
      var newdivW=document.getElementById('totalWater');
      newdivW.value=(calculateTotal() * w.value).toFixed(2);
   }
   function calculateTotalUpdate() {
      var num = 1;
      while(num <= numRowsMat){
         calculateSuggested(num);
         num++;
      }
   } 
   function calculateSuggested(numS) {   
      var mC = document.getElementById('rate2'+numS).value;
      var newdivC=document.getElementById('calculatedTotal'+numS);
      var integer= parseFloat(mC).toFixed(2);
   
      var total = (calculateTotal() * mC).toFixed(2);
      newdivC.value = total;
      if (farm == 'wahlst_spiralpath') {
         var actual = document.getElementById('actuarialTotal'+numS);
         actual.value = total;
      }
    
   }
   
   function checkIfFilled(){
      var fIndex=1;   
      var mIndex=1;
      while(fIndex<= numRows){
         var currentF=document.getElementById('field'+fIndex);
         if(currentF.value==0){
            alert("Please select a field in row " + fIndex);
            return false;
         }
      fIndex++;
      }   
   
      while(mIndex<= numRowsMat){
         var currentM=document.getElementById('material2'+mIndex);
         var currentAct=document.getElementById('actuarialTotal'+mIndex).value;
         if(currentM.value==0 || isNaN(parseFloat(currentAct)) ){
            alert("Please select a material in row " + mIndex);
            return false;
         }
         mIndex++;
      }   
       
      for (var i = 1; i <= numCropRows; i++) {
          if (document.getElementById("crop" + i).value == 0) {
            alert("Please select a crop in row " + i);
            return false;
          }
      }
      return true;
   }

   function show_confirm(){
      if(checkIfFilled()){
         var numRow = document.getElementById("numField");
         numRow.value = numRows;
         var numMat = document.getElementById("numMaterial");
         numMat.value = numRowsMat;
         return confirm("Confirm submit?");
      } else {
         return false;
      }
   }
</script>
