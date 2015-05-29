<script type="text/javascript">
   function getDevices(row) {
      var devDiv = document.getElementById("irrigationDiv"+row);
      var field = document.getElementById("fieldID"+row).value;
      xmlhttp     = new XMLHttpRequest();
      xmlhttp.open('GET', 'get_device.php?fieldID='+field, false);
      xmlhttp.send();
      devDiv.innerHTML= '<div class="styled-select" id="irrigationDiv'+row+'"> <select name ="irrigation' + 
         row + '" id="irrigation' + row + '" class="wide" onchange="enableCheckBox('+row+');">' +
         xmlhttp.responseText + '</select></div>';
      enableCheckBox(row);
   }

      var numRows    = document.getElementById('numRows').value;
      /* add a new row to the table*/
      function addRows(){
         numRows++;
         document.getElementById('numRows').value = numRows;
/*
         var table   = document.getElementById('fieldTable');
         var row     = table.insertRow(numRows);
*/
         var table   = document.getElementById('fieldTable').getElementsByTagName('tbody')[0];
         var row     = table.insertRow(-1);
         var id      = 'row' + numRows;
         var name    = 'row' + numRows;
         xmlhttp     = new XMLHttpRequest();
         xmlhttp.open('GET', 'update_fieldID.php', false);
         xmlhttp.send();
         var cell0   = row.insertCell(0);
         cell0.innerHTML = '<div class="styled-select" id="fieldDiv'+numRows+'"> <select name ="fieldID' + numRows +
             '" id="fieldID' + numRows + '" class="wide" onchange=\'enableCheckBox('+numRows+
             ');getDevices('+numRows+');\'>' +
                           '<option value = 0 selected disabled> Field Name</option>' + xmlhttp.responseText +
                           '</select></div>';
         var cell1   = row.insertCell(1);
         cell1.innerHTML = '<div class="styled-select" id="irrigationDiv'+numRows+'"> <select name ="irrigation' + numRows +
                           '" id="irrigation' + numRows + '" class="wide" onchange="enableCheckBox('+numRows+');">' +
                           '<option value = 0 selected disabled> Device</option>' +
                           '</select></div>';
         var cell2   = row.insertCell(2);
         cell2.innerHTML = '<div id="checkBox'+numRows+'" class="switch"><center><input type="checkbox" class="toggle pure-checkbox" id="check'+numRows+'" name="check'+numRows+'" value="checked'+numRows+'" disabled onchange="setElapsedTime('+numRows+');"><label for="check'+numRows+'" style="margin-right: 0; margin-top:8px;"></label><input type="hidden" name="checked_first'+numRows+'" id="checked_first'+numRows+'" value=false></center></div>';
         var cell3 = row.insertCell(3);
         cell3.innerHTML="0";

      }  
   /* remove the last row of the table and also delete data from db*/
   function removeRow(){
      if (numRows>0){
         var fieldID    = document.getElementById('fieldID' + numRows).value;
         var field      = document.getElementById('fieldID' + numRows);
         field.parentNode.removeChild(field);
         var irrigation = document.getElementById('irrigation' + numRows);
         irrigation.parentNode.removeChild(irrigation);
         var checkbox   = document.getElementById('check'+ numRows);
         checkbox.parentNode.removeChild(checkbox);
         var isChecked  = document.getElementById('checked_first'+numRows);
         isChecked.parentNode.removeChild(isChecked);
         var table      = document.getElementById("fieldTable");
         table.deleteRow(numRows);
         numRows--;
         document.getElementById('numRows').value = numRows;
         xmlhttp = new XMLHttpRequest();
            xmlhttp.open('GET', 'deleteField_irrigation.php?fieldID=' + fieldID, false);
         xmlhttp.send();
         console.log(xmlhttp.responseText);
      }
   }
   /* Enable the CheckBox and add new row to field_irrigation table if user chooses both fieldID and irrigation device*/
   function enableCheckBox(count){
      var field   = document.getElementById('fieldID'+count).value;
      var irr_dev = document.getElementById('irrigation'+count).value;
      if (field != 0 && irr_dev != 0){
         document.getElementById('check'+count).disabled=false;
      }
   }
   /* if box is checked, insert start time to the table, if it is unchecked, calculate the amount of time and add to elapsed time*/
   function setElapsedTime(count){
      xmlhttp = new XMLHttpRequest();
      if(document.getElementById('checked_first'+count).value == 'false'){
         document.getElementById('checked_first'+count).value = 'true';
         var field   = encodeURIComponent(document.getElementById('fieldID'+count).value);
         var irr_dev = encodeURIComponent(document.getElementById('irrigation'+count).value);
         xmlhttp.open('GET', 'insertTofield_irrigation.php?fieldID=' +field+ '&irr_dev='+irr_dev, false);
         xmlhttp.send();
         console.log(xmlhttp.responseText);
         document.getElementById('fieldID'+count).disabled = true;
         document.getElementById('irrigation'+count).disabled = true;
      }
      var fieldID = encodeURIComponent(document.getElementById('fieldID'+count).value);
      if (document.getElementById('check'+count).checked){
         updatePumpLog(true);
         xmlhttp.open('GET', 'updateElapsedTime.php?fieldID='+fieldID, false);
         xmlhttp.send();
         console.log(xmlhttp.responseText);
      } else {
         xmlhttp.open('GET', 'addElapsedTime.php?fieldID='+fieldID, false);
         xmlhttp.send();
         console.log(xmlhttp.responseText);
      }
   }
    /* update the pump log temp table*/ 
   function updatePumpLog(fromField){
      var month    = document.getElementById('month').value;
      var day      = document.getElementById('day').value;
      var year     = document.getElementById('year').value;
      var valve    = document.getElementById('valve').value;
  <?php 
    if ($_SESSION['pump']) {
       echo "
         var drive    = document.getElementById('drive').value;
         var outlet   = document.getElementById('outlet').value;
         var pump_kwh = document.getElementById('pump_kwh').value;";
      if ($farm == "dfarm") {
       echo "
         var solarKWH = document.getElementById('solar_KWH').value;";
       } else {
         echo "var solarKWH = 0;";
       }
    } else {
        echo "
         var drive    = 0.01;
         var outlet   = 0.01;
         var pump_kwh = 0.01;
         var solarKWH = 0.01;";
    }
     ?>
        var comment     = encodeURIComponent(document.getElementById('comment').value);
      if (!fromField && (month == '' || day == '' || year == '' || 
                         drive == '' || outlet == '') ){
         alert("Please Complete the Form Before Updating Pump Log Information");
      } 
      else if (!fromField && (isNaN(drive) || drive < 0 || isNaN(outlet) ||
          outlet < 0 || isNaN(pump_kwh) || pump_kwh < 0 || isNaN(solarKWH) || 
          solarKWH < 0)){
         alert("Value(s) Must Be Positive Number(s)");
      } else {
         xmlhttp = new XMLHttpRequest();
         upd = 'updatePumpLog.php?month='+month+'&day='+day+'&year='+year+'&valve='+encodeURIComponent(valve)+'&drive='+drive+'&outlet='+outlet+'&pump_kwh='+pump_kwh+'&solarKWH='+solarKWH+'&comment='+comment;
         console.log(upd);
         xmlhttp.open('GET', upd, false);
         xmlhttp.send();
         console.log(xmlhttp.responseText);
         if (!fromField) {
            var butt = document.getElementById("updatePump");
           <?php if ($_SESSION['pump']) {
              echo 'butt.value = "Pump Log Updated!";';
            } else {
              echo 'butt.value = "Comments Updated!";';
            }?>
            // butt.style.background="green";
            butt.style.setProperty("background", "green", "important");
            window.setTimeout(changePumpButBack, 2000);
         }
         //showAlert("Pump Log Updated!");
      }
   }
 
   function changePumpButBack() {
         var butt = document.getElementById("updatePump");
         <?php if ($_SESSION['pump']) {
           echo 'butt.value = "Update Pump Log";';
         } else {
           echo 'butt.value = "Update Comments";';
         }?>
         butt.style.background="peru";
   }

   function cancelIt() {
      return confirm("Are you sure that you want to cancel?");
   }

    function show_confirm(){
       <?php
        if ($_SESSION['pump']) {
        echo "var input_array = ['drive', 'outlet','pump_kwh' ";
        if ($farm == "dfarm") { echo ", 'solar_KWH'"; }
        echo "];
        for(i = 0; i < input_array.length; i++){
            var input = document.getElementById(input_array[i]).value;
            console.log('input is '+input);
            if (input == '' || isNaN(input) || input <= 0){
                alert('Please Enter ' + input_array[i] + 
           '.\\n The value must be a positive number.');
                return false;
            }
        }";
        }
        ?>
        if (numRows == 0){
            alert ('Irrigation Table Must Contain at Least One Row');
            return false;
        }
        alert('Irrigation Form Successfully Submitted!');
        return true;
    }
</script>
