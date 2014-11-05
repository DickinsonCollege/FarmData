<?php session_start(); ?>
<?php
   include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
   include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
   include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<html>
<head>
   <!--Load the AJAX API-->
   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
</head>

<body>
   <?php
      // take the parameters from the link
      $year = $_GET['year'];
      $month= $_GET['month'];
      $day = $_GET['day'];
      $tyear = $_GET['tyear'];
      $tmonth= $_GET['tmonth'];
      $tday = $_GET['tday'];
      $crop = escapehtml($_GET['crop']);
      // find all the fields that are harvested with this particular crop and date range
      $sql = "select distinct fieldID from harvested where crop='".$crop."' and hardate between '".$year."-".$month."-".$day."' and '".$tyear."-".$tmonth."-".$tday."' order by fieldID";
      $sqldata = mysql_query($sql);
      $header = array("date");
      $count = 1;
      while($row = mysql_fetch_array($sqldata)){
         $header[$count] = $row['fieldID'];
         $count++;
      }
      // find all the date which a particular crop is harvested.
      $sql = "select distinct hardate from harvested where crop='".$crop."' and hardate between '".$year."-".$month."-".$day."' and '".$tyear."-".$tmonth."-".$tday."' order by hardate";
      $sqldata = mysql_query($sql);      
      $dateArray = array();      
      $count = 0;
      while($row = mysql_fetch_array($sqldata)){
         $dateArray[$count] = $row['hardate'];
         $count++;
      }
      // build the 2d array of data for the graph      
      $array = array();
      for ($count = 0; $count < count($header); $count++) {
         $array[0][$count] = escapeescapehtml($header[$count]);
      }
      // $array[0] = $header; // set header for the table
      echo '<h4>Quantity harvested in each field of '.$crop.' between '.$year.'-'.$month.'-'.$day.' and '.$tyear.'-'.$tmonth.'-'.$tday.'</h4><br clear="all">';
      $count = 0;
      for ($count; $count < count($dateArray); $count++){
         $sql= "select fieldID, sum(yield) from harvested where crop='".$crop."' and hardate='".$dateArray[$count]."' group by fieldID";
         $sqldata = mysql_query($sql);
         $rowdata[0] = substr($dateArray[$count], 2, 2).'/'.substr($dateArray[$count], 5,2).'/'.substr($dateArray[$count],8,2);
         $fill0 = 1;
         for ($fill0; $fill0 <= count($header)-1;$fill0++){
            $rowdata[$fill0] = 0;
         }
         //$rowdata[0] = $dateArray[$count];
         while($row = mysql_fetch_array($sqldata)){
            $countCheck = 1;
            for ($countCheck; $countCheck <= count($header)-1;$countCheck++){
               if ($row['fieldID']==$header[$countCheck]){
                  $rowdata[$countCheck] = intval($row['sum(yield)']);
               }
            }
         }
         $array[$count+1] = $rowdata;
      }   
      // find the unit of the chart
      $sql = mysql_query("select distinct unit from harvested where crop='".$crop."'");
      $row = mysql_fetch_array($sql);
      echo"<input type='hidden' id='unit' value='".$row['unit']."'/>";
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
      console.log(data2);
      var data = new google.visualization.arrayToDataTable(data2);
      // Set chart options
      var options = {'title':'Quantity Harvested in each Field',
                     'hAxis':{title:'Date', titleTextStyle:{color: 'red'}},
                     'vAxis':{title:document.getElementById('unit').value},
                     'isStacked': 'true',
                     'width':1440,
                     'height':800
                     };
   
      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
   }
 
   </script>
   <!--Div that will hold the pie chart-->
   <div id="chart_div"></div>
</body>
</html>
