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
        <label for="organization">Select Organization:</label>
        <select id="organization" onchange="filterData()">
          <option value="all">All Organizations</option>
          <option value="JPCS">JPCS</option>
          <option value="CODE-TG">CODE-TG</option>
          <option value="CSC">CSC</option>
          <option value="YMF">YMF</option>
          <option value="HMSO">HMSO</option>
          <option value="PASOA">PASOA</option>
        </select>
      </div>
      <!-- Display the CanvasJS pie chart -->
      <div id="chartContainer" style="height: 370px; width: 100%;"></div>

      <?php
      // Assuming you have the $dataPoints array from the previous chart
      $dataPoints = array( 
        array("organization" => "JPCS", "label"=>"Remaining Voters", "y"=>1000), // Assuming 1000 remaining voters for JPCS
        array("organization" => "JPCS", "label"=>"Voters Voted", "y"=>500), // Assuming 500 voters voted for JPCS
        array("organization" => "CODE-TG", "label"=>"Remaining Voters", "y"=>800), // Assuming 800 remaining voters for CODE-TG
        array("organization" => "CODE-TG", "label"=>"Voters Voted", "y"=>300), // Assuming 300 voters voted for CODE-TG
        array("organization" => "CSC", "label"=>"Remaining Voters", "y"=>1200), // Assuming 1200 remaining voters for CSC
        array("organization" => "CSC", "label"=>"Voters Voted", "y"=>600), // Assuming 600 voters voted for CSC
        array("organization" => "YMF", "label"=>"Remaining Voters", "y"=>900), // Assuming 900 remaining voters for YMF
        array("organization" => "YMF", "label"=>"Voters Voted", "y"=>400), // Assuming 400 voters voted for YMF
        array("organization" => "HMSO", "label"=>"Remaining Voters", "y"=>700), // Assuming 700 remaining voters for HMSO
        array("organization" => "HMSO", "label"=>"Voters Voted", "y"=>300) // Assuming 300 voters voted for HMSO
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
