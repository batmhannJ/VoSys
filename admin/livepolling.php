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

            <!-- Column Charts for President and Vice President -->
            <div class="row">
                <!-- President Column Chart Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- President Column Chart Container -->
                            <div id="presidentChartContainer" style="height: 300px; width: 100%;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <!-- Vice President Column Chart Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President Column Chart Container -->
                            <div id="vicePresidentChartContainer" style="height: 300px; width: 100%;"></div>
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
<!-- Column Chart Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Function to generate column chart with multiple axes
    function generateColumnChart(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Vote Counts"
            },
            axisY: {
                title: "Vote Count",
                includeZero: true
            },
            toolTip: {
                shared: true
            },
            data: [{
                type: "column",
                name: "President",
                showInLegend: true,
                dataPoints: dataPoints[0]
            },
            {
                type: "column",
                name: "Vice President",
                showInLegend: true,
                dataPoints: dataPoints[1]
            }]
        });
        chart.render();
        return chart;
    }

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php',
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()},
            success: function(response) {
                if (response.success) {
                    // Update president and vice president column charts
                    if (response.columnChartData.length > 0) {
                        updateColumnChart(response.columnChartData, presidentVicePresidentChart);
                    }
                } else {
                    console.error('Error updating data: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Function to update column chart with animation
    function updateColumnChart(newDataPoints, chart) {
        for (var i = 0; i < newDataPoints.length; i++) {
            for (var j = 0; j < newDataPoints[i].length; j++) {
                chart.options.data[i].dataPoints[j].y = newDataPoints[i][j].y; // Update vote count directly
                animateColumn(i, j, newDataPoints[i][j].y, chart); // Animate the column
            }
        }
    }

    // Function to animate column
    function animateColumn(seriesIndex, dataIndex, newValue, chart) {
        var dataSeries = chart.options.data[seriesIndex];
        var dataPoint = dataSeries.dataPoints[dataIndex];
        var oldValue = dataPoint.y;
        var delta = newValue - oldValue;
        var duration = 1000; // Animation duration in milliseconds
        var frameRate = 30; // Number of frames per second
        var framesCount = Math.ceil(duration / 1000 * frameRate);
        var frameNumber = 0;

        var interval = setInterval(function() {
            frameNumber++;
            var progress = frameNumber / framesCount;
            var deltaY = delta * progress;
            dataPoint.y = oldValue + deltaY;
            chart.render();

            if (frameNumber == framesCount) {
                clearInterval(interval);
            }
        }, 1000 / frameRate);
    }

    // Initialize chart
    var presidentVicePresidentChart = generateColumnChart([[], []], "presidentVicePresidentChartContainer");

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 5 seconds
    setInterval(updateData, 5000);
</script>
</body>
</html>
