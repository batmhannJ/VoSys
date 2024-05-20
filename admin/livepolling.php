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

            <!-- Column Graphs for President and Vice President -->
            <div class="row">
                <!-- President Column Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- President Column Graph Container -->
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Vice President Column Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President Column Graph Container -->
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
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
<!-- Column Graph Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Function to generate column graph with multiple axes
    function generateColumnGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: {
                text: "Vote Counts"
            },
            axisY: {
                title: "Vote Count",
                includeZero: true
            },
            axisX: {
                title: "Candidates"
            },
            data: [{
                type: "column", // Change type to "column"
                dataPoints: dataPoints
            }]
        });
        chart.render();
        return chart;
    }

    // Initialize charts
    var presidentChart;
    var vicePresidentChart;

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()}, // Pass the selected organization to the server
            success: function(response) {
                // Update president column graph
                if (response.presidentData.length > 0) {
                    if (!presidentChart) {
                        presidentChart = generateColumnGraph(response.presidentData, "presidentGraph");
                    } else {
                        updateColumnGraph(response.presidentData, presidentChart);
                    }
                }

                // Update vice president column graph
                if (response.vicePresidentData.length > 0) {
                    if (!vicePresidentChart) {
                        vicePresidentChart = generateColumnGraph(response.vicePresidentData, "vicePresidentGraph");
                    } else {
                        updateColumnGraph(response.vicePresidentData, vicePresidentChart);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Function to update column graph with animation
    function updateColumnGraph(newDataPoints, chart) {
        for (var i = 0; i < newDataPoints.length; i++) {
            var newVotes = newDataPoints[i].y;
            chart.options.data[0].dataPoints[i].y = newVotes; // Update vote count directly
            animateColumn(i, newVotes, chart); // Animate the column
        }
    }

    // Function to fetch updated data and update graphs
    function updateDataAndGraphs() {
        $.ajax({
            url: 'update_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Update president graph
                presidentChart.options.data[0].dataPoints = response.presidentData;
                presidentChart.render();

                // Update vice president graph
                vicePresidentChart.options.data[0].dataPoints = response.vicePresidentData;
                vicePresidentChart.render();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Initialize charts
    var presidentChart = generateColumnGraph([], "presidentGraph");
    var vicePresidentChart = generateColumnGraph([], "vicePresidentGraph");

    // Call the updateDataAndGraphs function initially
    updateDataAndGraphs();

    // Call the updateDataAndGraphs function every 5 seconds
    setInterval(updateDataAndGraphs, 5000);
</script>
</body>
</html>
