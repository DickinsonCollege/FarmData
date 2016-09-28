<script type='text/javascript'>
   var numRowsMat = document.getElementById('numRowsMat').value;
   function addRowMat(){
      document.getElementById('numRowsMat').value = parseInt(numRowsMat) + 1;
      numRowsMat++;
      var table = document.getElementById("materialTable").getElementsByTagName('tbody')[0];
      var row = table.insertRow(-1);
      var materialSprayed = "<?php
         $sqlM="SELECT sprayMaterial FROM tSprayMaterials";
         $resultM=$dbcon->query($sqlM);
         while($rowM=$resultM->fetch(PDO::FETCH_ASSOC)){
            echo "<option value='".$rowM[sprayMaterial]."'>".$rowM[sprayMaterial]."</option>";
         }?>";

      var cell0 = row.insertCell(0);
      cell0.innerHTML =  "<center><div id =\"material"+numRowsMat+"\" class='styled-select2'><select class='wide' id=\"material2"+numRowsMat+"\" name=\"material2"+numRowsMat+"\"  onChange=\"addInputRates("+numRowsMat+"); calculateSuggested("+numRowsMat+"); addUnit("+numRowsMat+");  \"\n>"+ "<option value=0> MaterialList</option>\n"+materialSprayed+"</select></div></center>";
      var cell1 = row.insertCell(1);
      cell1.innerHTML =  "<center><div id =\"rate"+numRowsMat+
            "\" class='styled-select2'><select class='wide' id='rate2"+numRowsMat+
            "' name='rate2"+numRowsMat+"'  onChange=\"calculateSuggested("+
            numRowsMat+");\">"+"<option value=0 selected> Rates </option> </select></div></center>";
      var cell2 = row.insertCell(2);
      cell2.innerHTML = "<div id=\"unitDiv"+numRowsMat+"\"><label id='unit"+ numRowsMat+"'> Unit </label></div>";
      var cell3 = row.insertCell(3);
      cell3.innerHTML = "<center><div id=\"calculatedTotalDiv"+numRowsMat+"\"><input type=\"text\" id=\"calculatedTotal"+numRowsMat+"\" class='wide' value=0 readonly></div></center>";
      var cell4 = row.insertCell(4);
      cell4.innerHTML = "<center><div id=\"actualTotalDiv"+numRowsMat+"\"><input class='wide' type=\"text\" id=\"actuarialTotal"+numRowsMat+"\" name=\"actuarialTotal"+numRowsMat+"\" value=0></div></center>";
   }
   //addRowMat();
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
         document.getElementById('numRowsMat').value = parseInt(numRowsMat) - 1; 
         numRowsMat--;  
      }
   }
   function addUnit(numU){
      console.log("addUnit");
      console.log(numU);
      var mU = encodeURIComponent(document.getElementById('material2'+numU).value);
      var newdivU=document.getElementById('unit'+numU);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tUnitUpdate.php?material="+mU, false);
      xmlhttp.send();
        
      newdivU.innerHTML="<label style=\"font-size:12pt\"  id='unit"+numU+"'>"+ xmlhttp.responseText +" </label>  ";
   }
  function addInputRates(numM){
      var mat = encodeURIComponent(document.getElementById('material2'+numM).value);
      var newdivM=document.getElementById('rate'+numM);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tRateUpdate.php?material="+mat, false);
      xmlhttp.send();
      newdivM.innerHTML="<div class=styled-select2 id='rate"+numM+"'> <select onchange=\"calculateSuggested("+numM+");\" class='wide' id='rate2"+numM+"' name= 'rate2"+numM+"'>"+xmlhttp.responseText+"</select></div>";
   }  
   
   function calculateSuggested(numS) { 
      var mC = document.getElementById('rate2'+numS);
      var strUser = mC.options[mC.selectedIndex].value;
      var newdivC=document.getElementById('calculatedTotal'+numS);
   //console.log('THE NUMBERS STARTS');
   //console.log(totalFieldAcre);
      var integer= parseFloat(strUser).toFixed(2);
   //console.log(strUser);
   //console.log("THE NUMBERS ENDS!!!");
   
      newdivC.value= (calculateTotal() * strUser).toFixed(2);
   }
   function calculateTotalUpdate() {
      var num = 1;
      while(num <= numRowsMat){
         calculateSuggested(num);
         num++;
      }
   }
</script>
