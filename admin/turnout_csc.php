<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>

<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_csc.php'; ?>
  <?php include 'includes/menubar_csc.php'; ?>

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
        <span id="organization">CSC</span>
      </div>
      <!-- Display the CanvasJS pie chart -->
      <div id="chartContainer" style="height: 370px; width: 100%;"></div>
      <?php
        $colors = array(
          "CSC" => array("remaining" => "#595959", "voted" => "#000000")  // Example color for HMSO
        );
        $sql_voters_voted = "SELECT * 
                             FROM votes_csc 
                             JOIN voters ON votes_csc.voters_id = voters.id 
                             GROUP BY votes_csc.voters_id";
        $query_voters_voted = $conn->query($sql_voters_voted);
        $num_voters_voted = $query_voters_voted->num_rows;

        // Query to get the number of remaining voters
        $sql_remaining_voters = "SELECT voters.id, voters.lastname
                                 FROM voters
                                 LEFT JOIN votes_csc ON voters.id = votes_csc.voters_id
                                 WHERE votes_csc.voters_id IS NULL";
        $query_remaining_voters = $conn->query($sql_remaining_voters);
        $num_remaining_voters = $query_remaining_voters->num_rows;
        $conn->close();
      // Assuming you have the $dataPoints array from the previous chart
      $dataPoints = array( 
        array("organization" => "CSC", "label" => "Remaining Voters", "y" => $num_remaining_voters, "color" => $colors["CSC"]["remaining"]),
        array("organization" => "CSC", "label" => "Voters Voted", "y" => $num_voters_voted, "color" => $colors["CSC"]["voted"])
      );
      ?>

    </section>   
  </div>
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
