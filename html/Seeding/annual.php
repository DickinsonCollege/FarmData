<script type="text/javascript">

function addLastHarvestDate() {
   var annual = document.getElementById("annual").value;
   var lastharvdiv = document.getElementById("lastharvdiv");
   if (annual == 1) {
      lastharvdiv.innerHTML = "";
   } else {
      var sel = "<label>Year of Last Harvest:</label>";
      sel += "<select class='mobile-select' id='lastYear' name='lastYear'>";
      var lastYear = parseInt('<?php echo $lastYear?>');
      if (!isNaN(lastYear)) {
         sel += "<option value=" + lastYear + ">" + lastYear + "</option>";
      }
      var curYear = parseInt(document.getElementById("year").value);
      for (var yr = curYear + 1; yr < curYear + 6; yr++) {
         sel += "<option value=" + yr + ">" + yr + "</option>";
      }
      sel += "</select>";
      lastharvdiv.innerHTML = sel;
   }
}
</script>
