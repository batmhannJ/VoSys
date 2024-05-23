<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>

<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_csc.php'; ?>
  <?php include 'includes/menubar_csc.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Voter Turnout
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Voter Turnout</li>
      </ol>
    </section>

    <section class="content">
      <div>
        <label for="organization">Organization:</label>
        <span id="organization">CSC</span>
      </div>
      <div id="chartContainer" style="height: 370px; width: 100%;"></div>
      <?php
        $colors = array(
          "CSC" => array("remaining" => "#595959", "voted" => "#000000")
        );
        
        $sql_voters_voted = "SELECT * 
                             FROM votes_csc 
                             JOIN voters ON votes.voters_id = voters.id 
                             WHERE voters.organization = 'CSC' 
                             GROUP BY votes_csc.voters_id";
        $query_voters_voted = $conn->query($sql_voters_voted);
        $num_voters_voted = $query_voters_voted->num_rows;

        $sql_remaining_voters = "SELECT voters.id, voters.lastname
                                 FROM voters
                                 LEFT JOIN votes ON voters.id = votes.voters_id
                                 WHERE votes.voters_id IS NULL
                                 AND voters.organization = 'CSC'";
        $query_remaining_voters = $conn->query($sql_remaining_voters);
        $num_remaining_voters = $query_remaining_voters->num_rows;
        $conn->close();

        $dataPoints = array(
          array("organization" => "CSC", "label" => "Remaining Voters", "y" => $num_remaining_voters, "color" => $colors["CSC"]["remaining"]),
          array("organization" => "CSC", "label" => "Voters Voted", "y" => $num_voters_voted, "color" => $colors["CSC"]["voted"])
        );
      ?>
    </section>   
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/voters_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
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
