<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Live Polling
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Live Polling </li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label for="organization">Select Organization:</label>
            <select class="form-control" id="organization">
              <option value="JPCS">JPCS</option>
              <option value="PASOA">PASOA</option>
              <option value="CSC">CSC</option>
              <option value="YMF">YMF</option>
              <option value="CODE-TG">CODE-TG</option>
              <option value="HMSO">HMSO</option>
            </select>
          </div>
          <div class="form-group">
            <label for="position">Select Position:</label>
            <select class="form-control" id="position">
              <option value="president">President</option>
              <option value="vice_president">Vice President</option>
              <option value="secretary">Secretary</option>
            </select>
          </div>
          <button onclick="applyFilter()" class="btn btn-primary">Filter</button>
        </div>
      </div>
      <div class="row">
        <?php
 
$dataPoints = array();
$y = 5;
for($i = 0; $i < 10; $i++){
  $y += rand(-1, 1) * 0.1; 
  array_push($dataPoints, array("x" => $i, "y" => $y));
}
 
?>
<?php
 
$dataPoints = array(
  array("label"=> "Core 1", "y"=> 20),
  array("label"=> "Core 2", "y"=> 65),
  array("label"=> "Core 3", "y"=> 11),
  array("label"=> "Core 4", "y"=> 5),
  array("label"=> "Core 5", "y"=> 48),
  array("label"=> "Core 6", "y"=> 8),
  array("label"=> "Core 7", "y"=> 2),
  array("label"=> "Core 8", "y"=> 18)
);
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
  title: {
    text: "CPU Usage in 8-Core Processor"
  },
  axisY: {
    minimum: 0,
    maximum: 100,
    suffix: "%"
  },
  data: [{
    type: "column",
    yValueFormatString: "#,##0.00\"%\"",
    indexLabel: "{y}",
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});
 
function updateChart() {
  var color,deltaY, yVal;
  var dps = chart.options.data[0].dataPoints;
  for (var i = 0; i < dps.length; i++) {
    deltaY = (2 + Math.random() * (-2 - 2));
    yVal =  Math.min(Math.max(deltaY + dps[i].y, 0), 90);
    color = yVal > 75 ? "#FF2500" : yVal >= 50 ? "#FF6000" : yVal < 50 ? "#41CF35" : null;
    dps[i] = {label: "Core "+(i+1) , y: yVal, color: color};
  }
  chart.options.data[0].dataPoints = dps;
  chart.render();
};
updateChart();
 
setInterval(function () { updateChart() }, 1000);
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 90%; margin-left: 40px; margin-top: 20px"></div>
<script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>               
      </div>
    </section>
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<!-- ./wrapper -->
<?php include 'includes/scripts.php'; ?>
</body>
</html>
