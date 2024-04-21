<?php
include 'includes/session.php';
include 'includes/header.php';
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_jpcs.php'; ?>
  <?php include 'includes/menubar_jpcs.php'; ?>

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
        $colors = array(
          "JPCS" => array("remaining" => "#95d097", "voted" => "#4CAF50")  // Example color for HMSO
        );
        $sql_voters_voted = "SELECT * 
        FROM votes 
        JOIN voters ON votes.voters_id = voters.id 
        WHERE voters.organization = 'JPCS' 
        GROUP BY votes.voters_id";
        $query_voters_voted = $conn->query($sql_voters_voted);
        $num_voters_voted = $query_voters_voted->num_rows;

        // Query to get the number of remaining voters
        $sql_remaining_voters = "SELECT voters.id, voters.lastname
            FROM voters
            LEFT JOIN votes ON voters.id = votes.voters_id
            WHERE votes.voters_id IS NULL
            AND voters.organization = 'JPCS'";
        $query_remaining_voters = $conn->query($sql_remaining_voters);
        $num_remaining_voters = $query_remaining_voters->num_rows;
        $conn->close();
      // Assuming you have the $dataPoints array from the previous chart
      $dataPoints = array( 
        array("organization" => "JPCS", "label" => "Remaining Voters", "y" => $num_remaining_voters, "color" => $colors["JPCS"]["remaining"]),
        array("organization" => "JPCS", "label" => "Voters Voted", "y" => $num_voters_voted, "color" => $colors["JPCS"]["voted"])
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
