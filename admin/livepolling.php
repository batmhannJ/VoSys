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
                Election Results
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Organization Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <form method="get" action="">
                                <div class="form-group">
                                    <label for="organization">Select Organization:</label>
                                    <select class="form-control" name="organization" id="organization">
                                        <option value="">All Organizations</option>
                                        <?php
                                        // Fetch and display organizations
                                        $organizationQuery = $conn->query("SELECT DISTINCT organization FROM voters");
                                        while($organizationRow = $organizationQuery->fetch_assoc()){
                                            $selected = ($_GET['organization'] ?? '') == $organizationRow['organization'] ? 'selected' : '';
                                            echo "<option value='".$organizationRow['organization']."' $selected>".$organizationRow['organization']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- President and Vice President Ranking Boxes -->
            <div class="row">
                <!-- President Ranking List Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of President Candidates</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- President Ranking Table -->
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Organization</th>
                                    <th>Candidate</th>
                                    <th>Vote Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Fetch and display president candidate ranking based on vote count and organization filter
                                $organizationFilter = !empty($_GET['organization']) ? " AND voters1.organization = '".$_GET['organization']."'" : "";
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM positions 
                                        LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'President'
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != ''".$organizationFilter."
                                        GROUP BY voters1.organization, candidates.id
                                        ORDER BY vote_count DESC";
                                $query = $conn->query($sql);
                                $rank = 1;
                                while($row = $query->fetch_assoc()){
                                    echo "
                                        <tr>
                                        <td>".$rank."</td>
                                        <td>".$row['organization']."</td>
                                        <td>".$row['candidate_name']."</td>
                                        <td>".$row['vote_count']."</td>
                                        </tr>";
                                    $rank++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Vice President Ranking List Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of Vice President Candidates</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President Ranking Table -->
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Organization</th>
                                    <th>Candidate</th>
                                    <th>Vote Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Fetch and display vice president candidate ranking based on vote count and organization filter
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM positions 
                                        LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President'
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != ''".$organizationFilter."
                                        GROUP BY voters1.organization, candidates.id
                                        ORDER BY vote_count DESC";
                                $query = $conn->query($sql);
                                $rank = 1;
                                while($row = $query->fetch_assoc()){
                                    echo "
                                        <tr>
                                        <td>".$rank."</td>
                                        <td>".$row['organization']."</td>
                                        <td>".$row['candidate_name']."</td>
                                        <td>".$row['vote_count']."</td>
                                        </tr>";
                                    $rank++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Bar Graphs for President and Vice President -->
            <div class="row">
                <!-- President Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- President Bar Graph Container -->
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Vice President Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President Bar Graph Container -->
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <span class="pull-right">
                              <a href="export_results.php?organization=<?php echo $_GET['organization'] ?? ''; ?>" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Export PDF</a>
                            </span>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<!-- ./wrapper -->
<?php include 'includes/scripts.php'; ?>
<!-- Bar Graph Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
  var organizationColors = {
    "JPCS": "#ffcc00",
    "PASOA": "#339966",
    "CSC": "#ff5050",
    "YMF": "#6666ff",
    "CODE-TG": "#cc99ff",
    "HMSO": "#ff9933"
  };

  function updateCharts() {
    var organization = document.getElementById("organization").value;

    updatePresidentChart(organization);
    updateRepresentativeChart(organization);
  }

  function updatePresidentChart(organization) {
    var dataPoints = [];

    // You can fetch data dynamically based on the selected organization here
    // For demonstration, I'm using static data for each organization

    if (organization === "JPCS") {
      dataPoints = [
        { y: 20, label: "President" },
        { y: 30, label: "Vice President" },
        { y: 40, label: "Secretary" }
      ];
    } else if (organization === "PASOA") {
      dataPoints = [
        { y: 25, label: "President" },
        { y: 35, label: "Vice President" },
        { y: 45, label: "Secretary" }
      ];
    } else if (organization === "CSC") {
      dataPoints = [
        { y: 35, label: "President" },
        { y: 25, label: "Vice President" },
        { y: 40, label: "Secretary" }
      ];
    } else if (organization === "YMF") {
      dataPoints = [
        { y: 30, label: "President" },
        { y: 40, label: "Vice President" },
        { y: 30, label: "Secretary" }
      ];
    } else if (organization === "CODE-TG") {
      dataPoints = [
        { y: 20, label: "President" },
        { y: 30, label: "Vice President" },
        { y: 50, label: "Secretary" }
      ];
    } else if (organization === "HMSO") {
      dataPoints = [
        { y: 15, label: "President" },
        { y: 35, label: "Vice President" },
        { y: 50, label: "Secretary" }
      ];
    }
    // Add more else if conditions for other organizations

    var chart = new CanvasJS.Chart("presidentChart", {
      title: { text: "President, Vice President, Secretary" },
      data: [{
        type: "bar",
        dataPoints: dataPoints,
        color: organizationColors[organization] // Set organization-specific color
      }]
    });

    chart.render();

    // Update chart every second
    setInterval(function () {
      updatePresidentDataPoints(organization, chart);
    }, 1000);
  }

  function updateRepresentativeChart(organization) {
    var dataPoints = [];

    // You can fetch data dynamically based on the selected organization here
    // For demonstration, I'm using static data for each organization

    if (organization === "JPCS") {
      dataPoints = [
        { y: 30, label: "Representative 1" },
        { y: 40, label: "Representative 2" }
      ];
    } else if (organization === "PASOA") {
      dataPoints = [
        { y: 20, label: "Representative 1" },
        { y: 30, label: "Representative 2" }
      ];
    } else if (organization === "CSC") {
      dataPoints = [
        { y: 25, label: "Representative 1" },
        { y: 35, label: "Representative 2" }
      ];
    } else if (organization === "YMF") {
      dataPoints = [
        { y: 30, label: "Representative 1" },
        { y: 40, label: "Representative 2" }
      ];
    } else if (organization === "CODE-TG") {
      dataPoints = [
        { y: 20, label: "Representative 1" },
        { y: 30, label: "Representative 2" }
      ];
    } else if (organization === "HMSO") {
      dataPoints = [
        { y: 15, label: "Representative 1" },
        { y: 25, label: "Representative 2" }
      ];
    }
    // Add more else if conditions for other organizations

    var chart = new CanvasJS.Chart("representativeChart", {
      title: { text: "Representatives" },
      data: [{
        type: "bar",
        dataPoints: dataPoints,
        color: organizationColors[organization] // Set organization-specific color
      }]
    });

    chart.render();

    // Update chart every second
    setInterval(function () {
      updateRepresentativeDataPoints(organization, chart);
    }, 1000);
  }

  function updatePresidentDataPoints(organization, chart) {
    // Update dataPoints based on the selected organization
    // For demonstration, I'm using random values for each data point
    var newDataPoints = [];
    for (var i = 0; i < chart.options.data[0].dataPoints.length; i++) {
      newDataPoints.push({ label: chart.options.data[0].dataPoints[i].label, y: Math.random() * 100 });
    }
    chart.options.data[0].dataPoints = newDataPoints;
    chart.render();
  }

  function updateRepresentativeDataPoints(organization, chart) {
    // Update dataPoints based on the selected organization
    // For demonstration, I'm using random values for each data point
    var newDataPoints = [];
    for (var i = 0; i < chart.options.data[0].dataPoints.length; i++) {
      newDataPoints.push({ label: chart.options.data[0].dataPoints[i].label, y: Math.random() * 100 });
    }

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 60 seconds (adjust as needed)
    setInterval(updateData, 60000); // 60000 milliseconds = 60 seconds
</script>

</body>

</html>

