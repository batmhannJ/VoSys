<?php
include 'includes/session.php';
include 'includes/header.php';
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Voter Turnout
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Voter Turnout</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Add organization dropdown -->
      <div>
        <label for="organization">Organization:</label>
        <span id="organization">JPCS</span>
      </div>
      <!-- Display the CanvasJS pie chart -->
      <div id="chartContainer" style="height: 370px; width: 100%;"></div>
      <?php
      // Assuming you have the $dataPoints array from the previous chart
      $dataPoints = array( 
        array("organization" => "JPCS", "label"=>"Remaining Voters", "y"=>1000), // Assuming 1000 remaining voters for JPCS
        array("organization" => "JPCS", "label"=>"Voters Voted", "y"=>500)
      );
      ?>

    </section>   
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/voters_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<!-- CanvasJS library -->
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<!-- Script to render CanvasJS chart -->
<script>
window.onload = function() {
  renderChart(<?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>);
}

function renderChart(dataPoints) {
  var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    title: {
      text: "Voter Turnout by Organization"
    },
    data: [{
      type: "pie",
      showInLegend: true,
      legendText: "{label}",
      indexLabel: "{label}: {y}",
      dataPoints: dataPoints
    }]
  });
  chart.render();
}

function filterData() {
  var organization = document.getElementById("organization").value;
  var filteredDataPoints;
  
  if (organization === "all") {
    filteredDataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
  } else {
    filteredDataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>.filter(function(item) {
      return item.organization === organization;
    });
  }
  
  renderChart(filteredDataPoints);
}
</script>

</body>
</html>
