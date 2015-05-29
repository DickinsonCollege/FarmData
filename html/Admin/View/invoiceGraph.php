<?php session_start(); ?>
<?php
   include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
   include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
   include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<head>
   <!--Load the AJAX API-->
   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
</head>

<body>
   <?php
      $year = $_GET['year'];
      $month= $_GET['month'];
      $day = $_GET['day'];
      $tyear = $_GET['tyear'];
      $tmonth= $_GET['tmonth'];
      $tday = $_GET['tday'];
      $array = array();
      $array[0] = array("Product", "Amount($)");
      echo '<center><h2>Total income from invoices between '.$year.'-'.$month.'-'.$day.' and '.$tyear.'-'.$tmonth.'-'.$tday.'</h2></center>'; 
      $sql = "select product, sum(cases* price_case) as income from invoice_master natural join invoice_entry where salesDate between '".$year."-".$month."-".$day."' and '".$tyear."-".$tmonth."-".$tday."' group by product";
      $sqldata = mysql_query($sql);
      $count=0;
      while($row = mysql_fetch_array($sqldata)){
         $array[$count+1] = array(escapeescapehtml($row['product']), floatval($row['income']));
         $count++;
      }
      $json = json_encode($array);
   ?>
   <script type="text/javascript">
   // Load the visualization API and the chart package
   google.load('visualization', '1.0', {'packages':['corechart']});
   // Set a callback to run when the google visualization API is loaded.
   google.setOnLoadCallback(drawChart);
   // callback that creates and populates a data table, instantiates the chart, passes in the data and draws it.
   function drawChart() {
      // Create the data table.
      var data2 = eval(<?php echo $json;?>);
      var data = new google.visualization.arrayToDataTable(data2);
      // Set chart options
      var options = {'title':'Total income from Product',
                     'is3D' : 'true',
                     //'pieHole' : '0.4',
                     'width':800,
                     'height':600
                     };
   
      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, options);
   }
 
   </script>
   <!--Div that will hold the pie chart-->
  <center><div id="chart_div"></div></center>
</body>
</html>
