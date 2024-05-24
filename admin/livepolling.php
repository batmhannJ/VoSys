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
                <div class="col-md-3">
                    <div class="box">
                        <div class="box-body">
                            <form method="get" action="">
                                <div class="form-group">
                                    <label for="organization">Select Organization:</label>
                                    <select class="form-control" name="organization" id="organization">
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

            <!-- Bar Graphs for President, Vice President, and Secretary -->
            <div class="row">
                <!-- President Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President Candidates</b></h3>
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
                            <h3 class="box-title"><b>Vice President Candidates</b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President Bar Graph Container -->
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Secretary Bar Graph Box -->
            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary Candidates</b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Secretary Bar Graph Container -->
                            <div id="secretaryGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
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
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  var organizationColors = {
    "JPCS": "#4CAF50",
    "PASOA": "#e6cc00",
    "CSC": "#595959",
    "YMF": "#00008b",
    "CODE-TG": "#800000",
    "HMSO": "#fff080"
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

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 60 seconds (adjust as needed)
    setInterval(updateData, 3000); // 60000 milliseconds = 60 seconds
</script>

</body>
</html>
