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

$colors = array(
  "JPCS" => array("remaining" => "#95d097", "voted" => "#4CAF50"), // Example color for JPCS
  "CODE-TG" => array("remaining" => "#ff6666", "voted" => "#800000"), // Example color for CODE-TG
  "CSC" => array("remaining" => "#595959", "voted" => "#000000"), // Example color for CSC
  "YMF" => array("remaining" => "#4d4dff", "voted" => "#00008b"), // Example color for YMF
  "HMSO" => array("remaining" => "#e6cc7f", "voted" => "#cba328"),
  "PASOA" => array("remaining" => "#fff080", "voted" => "#e6cc00")  // Example color for HMSO
);
// Query to get the number of voters voted for all organizations

// Query to count the total number of voters who have voted per organization
$sql_voters_voted = "SELECT voters.organization, COUNT(DISTINCT votes.voters_id) AS voters_voted_count
                     FROM votes 
                     JOIN voters ON votes.voters_id = voters.id 
                     GROUP BY voters.organization";
$query_voters_voted = $conn->query($sql_voters_voted);

// Constructing an associative array to store the number of voters voted for each organization
$voters_voted_by_organization = array();
while ($row = $query_voters_voted->fetch_assoc()) {
    $voters_voted_by_organization[$row['organization']] = $row['voters_voted_count'];
}

// Query to get the number of remaining voters for all organizations
$sql_remaining_voters = "SELECT voters.organization, COUNT(*) AS remaining_voters_count
                         FROM voters
                         LEFT JOIN votes ON voters.id = votes.voters_id
                         WHERE votes.voters_id IS NULL
                         GROUP BY voters.organization";
$query_remaining_voters = $conn->query($sql_remaining_voters);

// Constructing an associative array to store the number of remaining voters for each organization
$remaining_voters_by_organization = array();
while ($row = $query_remaining_voters->fetch_assoc()) {
    $remaining_voters_by_organization[$row['organization']] = $row['remaining_voters_count'];
}

// Combine the data to construct the $dataPoints array
$dataPoints = array();
foreach ($voters_voted_by_organization as $organization => $voters_voted_count) {
    $remaining_voters_count = $remaining_voters_by_organization[$organization];
    $dataPoints[] = array("organization" => $organization, "label" => "Remaining Voters", "y" => $remaining_voters_count, "color" => $colors[$organization]["remaining"]);
    $dataPoints[] = array("organization" => $organization, "label" => "Voters Voted", "y" => $voters_voted_count, "color" => $colors[$organization]["voted"]);
}

// Close connection
$conn->close();

// Outputting the data points (optional)
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
      text: "Voters Turnout by Organization"
    },
    data: [{
      type: "pie",
      showInLegend: true,
      legendText: "{label}",
      indexLabel: "{label}: {y}",
      dataPoints: dataPoints
    }]
  });
  var legend = chart.options.data[0].legendText;
    for (var i = 0; i < dataPoints.length; i += 2) {
        var organization = dataPoints[i].organization;
        var remainingColor = dataPoints[i].color;
        var votedColor = dataPoints[i + 1].color;
        legend = legend.replace("Remaining Voters", "<span style='color:" + remainingColor + "'>" + organization + " - Remaining Voters</span>");
        legend = legend.replace("Voters Voted", "<span style='color:" + votedColor + "'>" + organization + " - Voters Voted</span>");
    }
    chart.options.data[0].legendText = legend;
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
