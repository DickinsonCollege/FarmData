<script type="text/javascript">
   var numRows=document.getElementById('numRows').value;
   function addRow(){
      document.getElementById('numRows').value= parseInt(numRows)+1;
      numRows++;
      var table = document.getElementById("fieldTable").getElementsByTagName('tbody')[0];
      var row = table.insertRow(-1);
      var cell0 = row.insertCell(0);
      var fieldID = '<?php
                        $result=$dbcon->query("Select fieldID from field_GH where active=1");
                        while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
                           echo "<option value = \"".$row1[fieldID]."\">".$row1[fieldID]."</option>";
                        }
                     ?>';
      cell0.innerHTML = '<center><div class="styled-select" id="fieldDiv'+numRows+'"> <select class="wide" name ="field' + numRows +'" id="field' + numRows + '" onChange="addInput('+numRows+'); addAcre('+numRows+'); calculateTotalUpdate(); calculateWater();">' +'<option value = 0 selected disabled> FieldID</option>' +   fieldID + '</select></div></center>';
      var cell1 = row.insertCell(1);
      cell1.innerHTML = "<center><div id=\"maxBed"+numRows+"\" class='styled-select2'> <select class='wide' id=\"maxBed2"+numRows+"\" name=\"maxBed2"+numRows+"\"  onChange=\"addAcre("+numRows+"); calculateTotalUpdate(); calculateWater(); \">"+
                        "<option> Beds </option> </select></div></center>";
      var cell2 = row.insertCell(2);
      cell2.innerHTML = "<center><div id=\"acreDiv"+numRows+"\"><input class='wide' type=\"text\" id=\"acre"+numRows+"\" value=0 readonly></div> </center>";
      
      var cell3 = row.insertCell(3);
      cell3.innerHTML = "<center><input type = 'text' class = 'wide' id='crop" + numRows + "' name = 'crop" + numRows + "'></center>"; 
   }
   //addRow();

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
         document.getElementById('numRows').value = parseInt(numRows) - 1;
         numRows--;
      }
   }
   //addRowMat();

   function addInput(num){
      var fld = encodeURIComponent(document.getElementById('field'+num).value);
      var newdiv=document.getElementById('maxBed'+num);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tupdate.php?field="+fld, false);
      xmlhttp.send();

      newdiv.innerHTML="<div class='styled-select2' id=\"maxBed"+num+"\"><select onchange=\"addAcre("+num+"); calculateTotalUpdate(); calculateWater();\" class='wide' id= \"maxBed2"+num+"\" name= \"maxBed2"+num+"\">"+xmlhttp.responseText+"</select></div>";
   }

   function addAcre(numA){
      var fld = encodeURIComponent(document.getElementById('field'+numA).value);
      var bA = document.getElementById('maxBed2'+numA).value;
      var newdiv=document.getElementById('acre'+numA);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "tAcreUpdate.php?field="+fld+"&beds="+bA, false);
      xmlhttp.send();
      newdiv.value=xmlhttp.responseText;
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
      var mC = document.getElementById('rate2'+numS);
      var strUser = mC.options[mC.selectedIndex].value;
      var newdivC=document.getElementById('calculatedTotal'+numS);
      var integer= parseFloat(strUser).toFixed(2);
   
      newdivC.value= (calculateTotal() * strUser).toFixed(2);
   }
   function checkIfFilled(){
      var fIndex=1;   
      var mIndex=1;
      while(fIndex<= numRows){
         var currentF=document.getElementById('field'+fIndex);
         if(currentF.value==0){
            return false;
         }
      fIndex++;
      }   
   
      while(mIndex<= numRowsMat){
         var currentM=document.getElementById('material2'+mIndex);
         var currentAct=document.getElementById('actuarialTotal'+mIndex).value;
         if(currentM.value==0 || isNaN(parseFloat(currentAct)) ){
            return false;
         }
         mIndex++;
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
      }else{
         alert('Please enter all data!');
      return false;
      }
   }
</script>
