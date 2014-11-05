

<?php
 include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
 ?>

 <script type="text/javascript">
 function addInput(){
      var newdiv = document.getElementById('unit');
        //for (i=0;i<newdiv.options.length-1;i++) {
        //ne/wdiv.remove(i);
//      }
//document.getElementById(div).appendChild(newdiv);
var e = document.getElementById("crop");
var strUser = e.options[e.selectedIndex].text;
console.log(strUser);
xmlhttp= new XMLHttpRequest();
xmlhttp.open("GET", "hupdate.php?crop="+strUser, false);
xmlhttp.send();
console.log(xmlhttp.responseText);
newdiv.innerHTML="<select name= 'unit' id = 'unit'>"+xmlhttp.responseText+"</select>";
}https://farmdata.dickinson.edu/Harvest/harvest.php
 </script>
<div id="dynamicInput">
<select name="unit" id="unit";


</select>
</div>


